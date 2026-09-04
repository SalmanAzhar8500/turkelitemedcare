<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInquiry;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ContentController extends Controller
{
    public function index(): View
    {
        SitePage::seedDefaults();

        return view('dashboard', [
            'pageCount' => SitePage::count(),
            'inquiryCount' => SiteInquiry::count(),
            'latestInquiries' => SiteInquiry::latest()->take(5)->get(),
            'pages' => SitePage::query()
                ->orderByRaw("CASE WHEN slug = 'home' THEN 0 ELSE 1 END")
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function pagesIndex(): View
    {
        SitePage::seedDefaults();

        return view('admin.pages.index');
    }

    public function pagesData(): JsonResponse
    {
        return DataTables::eloquent(SitePage::query())
            ->editColumn('title', function (SitePage $page): string {
                $description = filled($page->description) ? '<small>'.e($page->description).'</small>' : '';

                return '<strong>'.e($page->title).'</strong>'.$description;
            })
            ->editColumn('is_active', fn (SitePage $page): string => '<span class="admin-status '.($page->is_active ? 'published' : 'draft').'">'.($page->is_active ? 'Published' : 'Draft').'</span>')
            ->addColumn('public_url', function (SitePage $page): string {
                $routes = [
                    'home' => '/',
                    'about' => '/about',
                    'contact' => '/contact',
                    'for-clinics' => '/for-clinics',
                    'how-it-works' => '/how-it-works',
                    'patient-services' => '/patient-services',
                    'stories.index' => '/patient-stories',
                    'treatments.index' => '/treatments',
                    'legal' => '/legal',
                ];

                return e($routes[$page->slug] ?? '/');
            })
            ->addColumn('content_sections', function (SitePage $page): string {
                $sections = array_keys($page->content ?? []);

                return e(implode(' | ', array_map(fn (string $section): string => str($section)->headline()->toString(), $sections)));
            })
            ->addColumn('action', fn (SitePage $page): string => $this->recordActions(
                route('admin.pages.edit', $page),
                route('admin.pages.destroy', $page)
            ))
            ->rawColumns(['title', 'is_active', 'action'])
            ->toJson();
    }

    public function pagesEdit(SitePage $page): View
    {
        $defaults = config('site-pages.'.$page->slug, []);
        $page = SitePage::resolve($page->slug, is_array($defaults) ? $defaults : []);

        return view('admin.pages.edit', [
            'page' => $page,
        ]);
    }

    public function pagesUpdate(Request $request, SitePage $page): RedirectResponse
    {
        $request->merge(['seo_robots' => $request->input('seo_robots') ?: 'index,follow']);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'template' => ['required', 'string', 'max:100'],
            'content_fields' => ['required', 'array'],
            'title_de' => ['nullable', 'string', 'max:255'],
            'description_de' => ['nullable', 'string'],
            'content_de_fields' => ['nullable', 'array'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'seo_robots' => ['required', Rule::in(['index,follow', 'noindex,nofollow'])],
            'seo_title_de' => ['nullable', 'string', 'max:60'],
            'seo_description_de' => ['nullable', 'string', 'max:160'],
            'seo_keywords_de' => ['nullable', 'string', 'max:500'],
            'seo_robots_de' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow'])],
            'title_tr' => ['nullable', 'string', 'max:255'],
            'description_tr' => ['nullable', 'string'],
            'content_tr_fields' => ['nullable', 'array'],
            'seo_title_tr' => ['nullable', 'string', 'max:60'],
            'seo_description_tr' => ['nullable', 'string', 'max:160'],
            'seo_keywords_tr' => ['nullable', 'string', 'max:500'],
            'seo_robots_tr' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow'])],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'content_ar_fields' => ['nullable', 'array'],
            'seo_title_ar' => ['nullable', 'string', 'max:60'],
            'seo_description_ar' => ['nullable', 'string', 'max:160'],
            'seo_keywords_ar' => ['nullable', 'string', 'max:500'],
            'seo_robots_ar' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow'])],
        ]);

        $content = $validated['content_fields'];
        $germanContent = $validated['content_de_fields'] ?? [];

        // Keep imported legacy copy intact while the editor presents it as read-only context.
        $existingImportedContent = data_get($page->content, 'imported_static_content');
        if ($existingImportedContent !== null && ! array_key_exists('imported_static_content', $content)) {
            $content['imported_static_content'] = $existingImportedContent;
        }

        $existingGermanImportedContent = data_get($page->translations, 'de.content.imported_static_content');
        if ($existingGermanImportedContent !== null && ! array_key_exists('imported_static_content', $germanContent)) {
            $germanContent['imported_static_content'] = $existingGermanImportedContent;
        }

        $translations = $page->translations ?? [];
        $this->mergeAdditionalTranslations($validated, $translations);
        $translations['de'] = array_filter([
            'title' => $validated['title_de'] ?? null,
            'description' => $validated['description_de'] ?? null,
            'content' => $germanContent,
            'seo_title' => $validated['seo_title_de'] ?? null,
            'seo_description' => $validated['seo_description_de'] ?? null,
            'seo_keywords' => $validated['seo_keywords_de'] ?? null,
            'seo_robots' => $validated['seo_robots_de'] ?? null,
        ], static fn (mixed $value): bool => filled($value));

        $page->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'template' => $validated['template'],
            'content' => $content,
            'translations' => $translations,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'seo_keywords' => $validated['seo_keywords'] ?? null,
            'seo_robots' => $validated['seo_robots'],
        ]);

        app(\App\Services\SitemapGenerator::class)->write();

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page saved successfully.');
    }

    public function pagesDestroy(SitePage $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted successfully.');
    }

    public function inquiriesIndex(): View
    {
        return view('admin.inquiries.index');
    }

    public function inquiriesData(): JsonResponse
    {
        return $this->inquiryData(
            SiteInquiry::query()->where('type', '!=', 'treatment-plan'),
            'admin.inquiries.show',
            'admin.inquiries.destroy'
        );
    }

    public function treatmentRequestsIndex(): View
    {
        return view('admin.treatment-requests.index');
    }

    public function treatmentRequestsData(): JsonResponse
    {
        return $this->inquiryData(
            SiteInquiry::query()->where('type', 'treatment-plan'),
            'admin.treatment-requests.show',
            'admin.treatment-requests.destroy'
        );
    }

    public function treatmentRequestsShow(SiteInquiry $inquiry): View
    {
        abort_unless($inquiry->type === 'treatment-plan', 404);

        return view('admin.treatment-requests.show', compact('inquiry'));
    }

    public function treatmentRequestsUpdate(Request $request, SiteInquiry $inquiry): RedirectResponse
    {
        abort_unless($inquiry->type === 'treatment-plan', 404);
        $inquiry->update($this->validatedInquiryStatus($request));

        return back()->with('status', 'Treatment request status saved.');
    }

    public function treatmentRequestsDestroy(SiteInquiry $inquiry): RedirectResponse
    {
        abort_unless($inquiry->type === 'treatment-plan', 404);
        $inquiry->delete();

        return redirect()->route('admin.treatment-requests.index')->with('status', 'Treatment request deleted successfully.');
    }

    private function inquiryData($query, string $showRoute, string $destroyRoute): JsonResponse
    {
        return DataTables::eloquent($query)
            ->editColumn('name', fn (SiteInquiry $inquiry): string => e($inquiry->name ?: 'Unnamed'))
            ->addColumn('specialty', fn (SiteInquiry $inquiry): string => e(data_get($inquiry->metadata, 'specialty', '---')))
            ->addColumn('country', fn (SiteInquiry $inquiry): string => e(data_get($inquiry->metadata, 'country', '---')))
            ->editColumn('status', fn (SiteInquiry $inquiry): string => '<span class="admin-status '.e($inquiry->status === 'new' ? 'draft' : 'published').'">'.e(ucfirst($inquiry->status)).'</span>')
            ->editColumn('created_at', fn (SiteInquiry $inquiry): string => e($inquiry->created_at?->format('Y-m-d H:i') ?? '---'))
            ->addColumn('action', fn (SiteInquiry $inquiry): string => $this->recordActions(
                route($showRoute, $inquiry),
                route($destroyRoute, $inquiry),
                'Open'
            ))
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function inquiriesShow(SiteInquiry $inquiry): View
    {
        return view('admin.inquiries.show', [
            'inquiry' => $inquiry,
        ]);
    }

    public function inquiriesUpdate(Request $request, SiteInquiry $inquiry): RedirectResponse
    {
        $inquiry->update($this->validatedInquiryStatus($request));

        return back()->with('status', 'Inquiry updated successfully.');
    }

    public function inquiriesDestroy(SiteInquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('status', 'Inquiry deleted successfully.');
    }

    private function recordActions(string $editUrl, string $deleteUrl, string $label = 'Edit'): string
    {
        return '<div class="data-table-actions">'
            .'<a class="auth-button secondary" href="'.e($editUrl).'">'.e($label).'</a>'
            .'<form method="POST" action="'.e($deleteUrl).'" data-confirm-delete>'
            .'<input type="hidden" name="_token" value="'.e(csrf_token()).'">'
            .'<input type="hidden" name="_method" value="DELETE">'
            .'<button class="auth-button danger" type="submit">Delete</button>'
            .'</form></div>';
    }

    /** @return array{status: string} */
    private function validatedInquiryStatus(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'in:new,reviewing,contacted,closed'],
        ]);
    }
    private function mergeAdditionalTranslations(array $validated, array &$translations): void
    {
        foreach (['tr', 'ar'] as $locale) {
            $translation = array_filter([
                'title' => $validated['title_'.$locale] ?? null,
                'description' => $validated['description_'.$locale] ?? null,
                'content' => $validated['content_'.$locale.'_fields'] ?? null,
                'seo_title' => $validated['seo_title_'.$locale] ?? null,
                'seo_description' => $validated['seo_description_'.$locale] ?? null,
                'seo_keywords' => $validated['seo_keywords_'.$locale] ?? null,
                'seo_robots' => $validated['seo_robots_'.$locale] ?? null,
            ], static fn (mixed $value): bool => filled($value));
            if ($translation !== []) {
                $translations[$locale] = array_replace_recursive($translations[$locale] ?? [], $translation);
            }
        }
    }
}
