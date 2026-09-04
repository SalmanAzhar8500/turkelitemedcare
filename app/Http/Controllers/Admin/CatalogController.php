<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Guide;
use App\Models\Procedure;
use App\Models\PatientService;
use App\Models\PatientStory;
use App\Models\Specialty;
use App\Services\SitemapGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CatalogController extends Controller
{
    public function index(string $type): View
    {
        Specialty::seedDefaults();
        PatientService::seedDefaults();
        $definition = $this->definition($type);

        return view($type === 'doctors' ? 'admin.doctors.index' : 'admin.catalog.index', $this->viewData($type, compact('definition')));
    }

    public function data(string $type): JsonResponse
    {
        $definition = $this->definition($type);
        $records = $definition['model']::query()
            ->when($type === 'procedures', fn ($query) => $query->with('specialty'))
            ->when($type === 'doctors', fn ($query) => $query->with(['clinic', 'treatments']));

        return DataTables::eloquent($records)
            ->editColumn('name', function (Model $record): string {
                $summary = filled($record->summary) ? '<small>'.e($record->summary).'</small>' : '';

                return '<strong>'.e($record->name).'</strong>'.$summary;
            })
            ->addColumn('parent', function (Model $record) use ($type): string {
                if ($type === 'procedures') {
                    return e($record->specialty?->name ?? '---');
                }

                if ($type === 'doctors') {
                    return e($record->clinic?->name ?? $record->specialty ?? '---');
                }

                return e($record->location ?? '---');
            })
            ->editColumn('is_active', fn (Model $record): string => '<span class="admin-status '.($record->is_active ? 'published' : 'draft').'">'.($record->is_active ? 'Published' : 'Draft').'</span>')
            ->addColumn('action', fn (Model $record): string => $this->recordActions(
                route('admin.catalog.edit', [$type, $record]),
                route('admin.catalog.destroy', [$type, $record])
            ))
            ->rawColumns(['name', 'parent', 'is_active', 'action'])
            ->toJson();
    }

    public function create(string $type): View
    {
        return view($type === 'doctors' ? 'admin.doctors.form' : 'admin.catalog.form', $this->viewData($type, ['record' => null]));
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $definition = $this->definition($type);
        $data = $this->validated($request, $type);
        $bulletPoints = $this->extractBulletPoints($data, $type);
        $relationships = $this->extractDoctorRelationships($data, $type);
        $translations = $this->extractTranslations($data, $type);
        if ($bulletPoints !== null) {
            $data['bullet_points'] = $bulletPoints;
        }
        $data['image_path'] = $this->storeImage($request, $type, null, 'image');
        if ($type === 'treatments') {
            $data['breadcrumb_image_path'] = $this->storeImage($request, $type, null, 'breadcrumb_image');
        }
        $record = $definition['model']::query()->create($data);
        $this->syncDoctorRelationships($record, $relationships);
        $this->saveTranslations($record, $translations);
        $this->regenerateSitemap();

        return redirect()->route('admin.catalog.edit', [$type, $record])->with('status', $definition['singular'].' created successfully.');
    }

    public function edit(string $type, string $record): View
    {
        return view($type === 'doctors' ? 'admin.doctors.form' : 'admin.catalog.form', $this->viewData($type, ['record' => $this->record($type, $record)]));
    }

    public function update(Request $request, string $type, string $record): RedirectResponse
    {
        $model = $this->record($type, $record);
        $data = $this->validated($request, $type, $model);
        $bulletPoints = $this->extractBulletPoints($data, $type);
        $relationships = $this->extractDoctorRelationships($data, $type);
        $translations = $this->extractTranslations($data, $type);
        if ($bulletPoints !== null) {
            $data['bullet_points'] = $bulletPoints;
        }
        $imagePath = $this->storeImage($request, $type, $model, 'image', $type === 'treatments' ? $model->breadcrumb_image_path : null);

        if ($imagePath !== null) {
            $data['image_path'] = $imagePath;
        }

        if ($type === 'treatments') {
            $breadcrumbImagePath = $this->storeImage($request, $type, $model, 'breadcrumb_image', $model->image_path);
            if ($breadcrumbImagePath !== null) {
                $data['breadcrumb_image_path'] = $breadcrumbImagePath;
            }
        }

        $model->update($data);
        $this->syncDoctorRelationships($model, $relationships);
        $this->saveTranslations($model, $translations);
        $this->regenerateSitemap();

        return back()->with('status', $this->definition($type)['singular'].' saved successfully.');
    }

    public function destroy(string $type, string $record): RedirectResponse
    {
        $model = $this->record($type, $record);
        $this->deleteStoredImage($model->image_path);
        if ($type === 'treatments') {
            $this->deleteStoredImage($model->breadcrumb_image_path);
        }
        $model->delete();
        $this->regenerateSitemap();

        return redirect()->route('admin.catalog.index', $type)->with('status', $this->definition($type)['singular'].' deleted successfully.');
    }

    private function record(string $type, string $identifier): Model
    {
        $model = $this->definition($type)['model'];

        return $model::query()
            ->whereKey($identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();
    }

    /** @return array{model: class-string<Model>, singular: string, plural: string} */
    private function definition(string $type): array
    {
        return match ($type) {
            'treatments' => ['model' => Specialty::class, 'singular' => 'Treatment', 'plural' => 'Treatments'],
            'clinics' => ['model' => Clinic::class, 'singular' => 'Clinic', 'plural' => 'Clinics'],
            'procedures' => ['model' => Procedure::class, 'singular' => 'Procedure', 'plural' => 'Procedures'],
            'patient-services' => ['model' => PatientService::class, 'singular' => 'Patient Service', 'plural' => 'Patient Services'],
            'patient-stories' => ['model' => PatientStory::class, 'singular' => 'Patient Story', 'plural' => 'Patient Stories'],
            'doctors' => ['model' => Doctor::class, 'singular' => 'Doctor', 'plural' => 'Doctors'],
            'guides' => ['model' => Guide::class, 'singular' => 'Guide', 'plural' => 'Guides'],
            default => abort(404),
        };
    }

    /** @return array<string, mixed> */
    private function viewData(string $type, array $data): array
    {
        return array_merge($data, [
            'type' => $type,
            'definition' => $this->definition($type),
            'specialties' => Specialty::published()->get(),
            'clinics' => Clinic::published()->get(),
            'procedures' => Procedure::published()->with('specialty')->get(),
        ]);
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, string $type, ?Model $record = null): array
    {
        $request->merge(['seo_robots' => $request->input('seo_robots') ?: 'index,follow']);
        $table = $this->definition($type)['model']::query()->getModel()->getTable();
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique($table, 'slug')->ignore($record?->getKey())],
            'summary' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];

        foreach (['de', 'ar'] as $locale) {
            foreach ($this->translatedFields($type) as $field) {
                $rules[$field.'_'.$locale] = ['nullable', 'string', $field === 'summary' ? 'max:500' : 'max:65535'];
            }
        }

        if ($type === 'clinics') {
            $rules += ['location' => ['nullable', 'string', 'max:255'], 'website' => ['nullable', 'url', 'max:255'], 'email' => ['nullable', 'email', 'max:255'], 'phone' => ['nullable', 'string', 'max:100'], 'description' => ['nullable', 'string']];
        } elseif ($type === 'treatments') {
            $rules += ['description' => ['nullable', 'string']];
        } elseif ($type === 'procedures') {
            $rules += ['specialty_id' => ['required', 'exists:specialties,id'], 'content' => ['nullable', 'string']];
        } elseif (in_array($type, ['patient-services', 'patient-stories'], true)) {
            $rules += ['content' => ['nullable', 'string'], 'bullet_points' => ['nullable', 'array'], 'bullet_points.*' => ['nullable', 'string', 'max:255'], 'bullet_points_de' => ['nullable', 'array'], 'bullet_points_de.*' => ['nullable', 'string', 'max:255'], 'bullet_points_ar' => ['nullable', 'array'], 'bullet_points_ar.*' => ['nullable', 'string', 'max:255'], 'action_label' => ['nullable', 'string', 'max:255'], 'action_label_de' => ['nullable', 'string', 'max:255'], 'action_label_ar' => ['nullable', 'string', 'max:255']];
        } elseif ($type === 'doctors') {
            $rules += ['clinic_id' => ['nullable', 'exists:clinics,id'], 'designation' => ['nullable', 'string', 'max:255'], 'specialty' => ['nullable', 'string', 'max:255'], 'profile_highlight' => ['nullable', 'string', 'max:255'], 'experience_years' => ['nullable', 'integer', 'min:0', 'max:100'], 'languages' => ['nullable', 'string', 'max:255'], 'consultation_method' => ['nullable', 'string', 'max:255'], 'response_time' => ['nullable', 'string', 'max:255'], 'verification_notes' => ['nullable', 'string'], 'treatment_ids' => ['required', 'array', 'min:1'], 'treatment_ids.*' => ['integer', 'exists:specialties,id'], 'procedure_ids' => ['nullable', 'array'], 'procedure_ids.*' => ['integer', 'exists:procedures,id'], 'content' => ['nullable', 'string']];
        } else {
            $rules += ['content' => ['nullable', 'string']];
        }

        $rules['image'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        if ($type === 'treatments') $rules['breadcrumb_image'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        $rules += ['seo_title' => ['nullable', 'string', 'max:60'], 'seo_description' => ['nullable', 'string', 'max:160'], 'seo_keywords' => ['nullable', 'string', 'max:500'], 'seo_robots' => ['required', Rule::in(['index,follow', 'noindex,nofollow'])]];
        foreach (['de', 'ar'] as $locale) {
            $rules += [
                'seo_title_'.$locale => ['nullable', 'string', 'max:60'],
                'seo_description_'.$locale => ['nullable', 'string', 'max:160'],
                'seo_keywords_'.$locale => ['nullable', 'string', 'max:500'],
                'seo_robots_'.$locale => ['nullable', Rule::in(['index,follow', 'noindex,nofollow'])],
            ];
        }

        $data = $request->validate($rules);

        if ($type === 'doctors' && filled($data['procedure_ids'] ?? [])) {
            $selectedProcedures = array_unique($data['procedure_ids']);
            $validProcedures = Procedure::query()->whereIn('id', $selectedProcedures)->whereIn('specialty_id', $data['treatment_ids'])->count();
            if ($validProcedures !== count($selectedProcedures)) {
                throw ValidationException::withMessages(['procedure_ids' => 'Each selected procedure must belong to one of the selected treatments.']);
            }
        }

        unset($data['image'], $data['breadcrumb_image']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    /** @return array<int, string> */
    private function translatedFields(string $type): array
    {
        return match ($type) {
            'treatments', 'clinics' => ['name', 'summary', 'description'],
            default => ['name', 'summary', 'content'],
        };
    }

    private function supportsBulletPoints(string $type): bool
    {
        return in_array($type, ['patient-services', 'patient-stories'], true);
    }

    /** @return array<int, string>|null */
    private function extractBulletPoints(array &$data, string $type): ?array
    {
        if (! $this->supportsBulletPoints($type)) {
            unset($data['bullet_points']);
            return null;
        }

        $points = array_values(array_filter(array_map(static fn ($point): string => trim((string) $point), $data['bullet_points'] ?? []), static fn (string $point): bool => $point !== ''));
        unset($data['bullet_points']);

        return $points;
    }

    /** @return array<int, string> */
    private function defaultBulletPoints(string $type): array
    {
        return match ($type) {
            'patient-services' => ['Planning around the confirmed treatment timetable', 'One coordination contact from arrival to return home', 'Support tailored to the patient journey'],
            'patient-stories' => ['Published patient journey', 'Research and planning example', 'Independent provider assessment required'],
            default => [],
        };
    }

    /** @param array<string, mixed> $data
     *  @return array{de: array<string, mixed>, ar: array<string, mixed>, _action_label_en?: string} */
    private function extractTranslations(array &$data, string $type): array
    {
        $translations = [];
        foreach (['de', 'ar'] as $locale) {
            $translation = [];
            foreach ($this->translatedFields($type) as $field) {
                $key = $field.'_'.$locale;
                $value = $data[$key] ?? null;
                unset($data[$key]);
                if (filled($value)) {
                    $translation[$field] = $value;
                }
            }

            foreach (['seo_title', 'seo_description', 'seo_keywords', 'seo_robots'] as $field) {
                $key = $field.'_'.$locale;
                $value = $data[$key] ?? null;
                unset($data[$key]);
                if (filled($value)) {
                    $translation[$field] = $value;
                }
            }

            if ($this->supportsBulletPoints($type)) {
                $points = array_values(array_filter(
                    array_map(static fn ($point): string => trim((string) $point), $data['bullet_points_'.$locale] ?? []),
                    static fn (string $point): bool => $point !== ''
                ));
                unset($data['bullet_points_'.$locale]);
                if ($points !== []) {
                    $translation['bullet_points'] = $points;
                }

                $actionLabel = trim((string) ($data['action_label_'.$locale] ?? ''));
                unset($data['action_label_'.$locale]);
                if ($actionLabel !== '') {
                    $translation['action_label'] = $actionLabel;
                }
            }

            $translations[$locale] = $translation;
        }

        if ($this->supportsBulletPoints($type)) {
            $englishActionLabel = trim((string) ($data['action_label'] ?? ''));
            unset($data['action_label']);
            if ($englishActionLabel !== '') {
                $translations['_action_label_en'] = $englishActionLabel;
            }
        }

        return $translations;
    }

    /** @param array<string, mixed> $translations */
    private function saveTranslations(Model $record, array $translations): void
    {
        $stored = $record->translations ?? [];
        $englishActionLabel = $translations['_action_label_en'] ?? null;
        unset($translations['_action_label_en']);

        foreach (['de', 'ar'] as $locale) {
            $stored[$locale] = array_replace($stored[$locale] ?? [], $translations[$locale] ?? []);
        }
        if (filled($englishActionLabel)) {
            $stored['en']['action_label'] = $englishActionLabel;
        }

        $record->forceFill(['translations' => $stored])->save();
    }

    /** @param array<string, mixed> $data
     *  @return array{treatment_ids: array<int, int>, procedure_ids: array<int, int>} */
    private function extractDoctorRelationships(array &$data, string $type): array
    {
        if ($type !== 'doctors') {
            return ['treatment_ids' => [], 'procedure_ids' => []];
        }

        $relationships = [
            'treatment_ids' => array_map('intval', $data['treatment_ids'] ?? []),
            'procedure_ids' => array_map('intval', $data['procedure_ids'] ?? []),
        ];
        unset($data['treatment_ids'], $data['procedure_ids']);

        return $relationships;
    }

    /** @param array{treatment_ids: array<int, int>, procedure_ids: array<int, int>} $relationships */
    private function syncDoctorRelationships(Model $record, array $relationships): void
    {
        if (! $record instanceof Doctor) {
            return;
        }

        $record->treatments()->sync($relationships['treatment_ids']);
        $record->procedures()->sync($relationships['procedure_ids']);
    }

    private function recordActions(string $editUrl, string $deleteUrl): string
    {
        return '<div class="data-table-actions">'
            .'<a class="auth-button secondary" href="'.e($editUrl).'">Edit</a>'
            .'<form method="POST" action="'.e($deleteUrl).'" data-confirm-delete>'
            .'<input type="hidden" name="_token" value="'.e(csrf_token()).'">'
            .'<input type="hidden" name="_method" value="DELETE">'
            .'<button class="auth-button danger" type="submit">Delete</button>'
            .'</form></div>';
    }

    private function storeImage(Request $request, string $type, ?Model $record = null, string $input = 'image', ?string $preservePath = null): ?string
    {
        if (! $request->hasFile($input)) {
            return null;
        }

        if ($record) {
            $oldPath = $input === 'breadcrumb_image' ? $record->breadcrumb_image_path : $record->image_path;
            if (! $this->sameStoredImage($oldPath, $preservePath)) {
                $this->deleteStoredImage($oldPath);
            }
        }

        $path = $request->file($input)->store('catalog/'.$type, 'uploads');

        return '/uploads/'.ltrim($path, '/');
    }

    private function deleteStoredImage(?string $imagePath): void
    {
        if (! $imagePath) {
            return;
        }

        $storedPath = parse_url($imagePath, PHP_URL_PATH) ?: $imagePath;

        if (str_starts_with($storedPath, '/uploads/')) {
            Storage::disk('uploads')->delete(ltrim(substr($storedPath, strlen('/uploads/')), '/'));
        } elseif (str_starts_with($storedPath, '/storage/')) {
            // Legacy V101 upload path. Kept so old records can still be cleaned up safely.
            Storage::disk('public')->delete(ltrim(substr($storedPath, strlen('/storage/')), '/'));
        }
    }

    private function sameStoredImage(?string $first, ?string $second): bool
    {
        if (blank($first) || blank($second)) {
            return false;
        }

        $firstPath = parse_url($first, PHP_URL_PATH) ?: $first;
        $secondPath = parse_url($second, PHP_URL_PATH) ?: $second;

        return ltrim($firstPath, '/') === ltrim($secondPath, '/');
    }

    private function regenerateSitemap(): void
    {
        app(SitemapGenerator::class)->write();
    }
}

