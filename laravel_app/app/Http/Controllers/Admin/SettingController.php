<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use App\Models\PatientGuide;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function profile()
    {
        return view('admin.settings.profile');
    }

    public function security()
    {
        return view('admin.settings.security');
    }
    public function heroSections()
    {

    }
    public function siteServices()
    {
        return view('admin.sitesetting.services.siteservices');
    }

    public function siteServicesData()
    {
        $query = Service::whereNull('parentid'); // Only root services
        $total = $query->count();

        if(request()->has('search') && request('search')['value']){
            $search = request('search')['value'];
            $query->where(function($q) use ($search){
                $q->where('name','like',"%{$search}%")
                    ->orWhere('slug','like',"%{$search}%");
            });
        }

        $filtered = $query->count();

        $start = request('start',0);
        $length = request('length',10);

        $services = $query->skip($start)->take($length)->get();

        $data = $services->map(function($service,$index) use($start){

            $hasChildren = Service::where('parentid',$service->id)->exists();

            $viewBtn = $hasChildren
                ? '<button class="btn btn-sm btn-info viewServiceBtn" data-slug="'.$service->slug.'">View</button>'
                : '';

            return [
                'DT_RowIndex' => $index+1+$start,
                'name' => $service->name,
                'slug' => $service->slug,
                'action' => $viewBtn.'<button class="btn btn-sm btn-primary editServiceBtn" data-id="'.$service->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteServiceBtn" data-id="'.$service->id.'">Delete</button>'
            ];
        });

        return response()->json([
            'draw'=>request('draw',1),
            'recordsTotal'=>$total,
            'recordsFiltered'=>$filtered,
            'data'=>$data
        ]);
    }

    public function addSiteService()
    {
        $services = Service::orderBy('id', 'desc')->get();

        return view('admin.sitesetting.services.add', compact('services'));
    }

    public function storeSiteService(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_word_count(strip_tags($value)) > 200) {
                        $fail('Description may not be greater than 200 words.');
                    }
                },
            ],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'parentid' => ['nullable', 'integer', 'exists:services,id'],
            'childid'  => ['nullable', 'integer', 'exists:services,id'],
            'detail_sidebar_title' => ['nullable', 'string', 'max:255'],
            'detail_cta_text' => ['nullable', 'string', 'max:255'],
            'detail_cta_title' => ['nullable', 'string', 'max:255'],
            'detail_cta_button_text' => ['nullable', 'string', 'max:255'],
            'detail_cta_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'detail_highlights_title' => ['nullable', 'string', 'max:255'],
            'detail_highlights_text' => ['nullable', 'string'],
            'detail_highlights_items' => ['nullable', 'string'],
            'detail_features_title' => ['nullable', 'string', 'max:255'],
            'detail_features' => ['nullable', 'array'],
            'detail_features.*.title' => ['nullable', 'string', 'max:255'],
            'detail_features.*.description' => ['nullable', 'string'],
            'detail_features.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'detail_features.*.icon' => ['nullable', 'string'],
            'detail_features.*.icon_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'detail_features.*.existing_image' => ['nullable', 'string'],
            'detail_feature1_title' => ['nullable', 'string', 'max:255'],
            'detail_feature1_description' => ['nullable', 'string'],
            'detail_feature2_title' => ['nullable', 'string', 'max:255'],
            'detail_feature2_description' => ['nullable', 'string'],
            'detail_steps_heading' => ['nullable', 'string', 'max:255'],
            'detail_faq_heading' => ['nullable', 'string', 'max:255'],
            'detail_steps_title' => ['nullable', 'string', 'max:255'],
            'detail_steps_text' => ['nullable', 'string'],
            'detail_step1_title' => ['nullable', 'string', 'max:255'],
            'detail_step1_icon' => ['nullable', 'string'],
            'detail_step1_description' => ['nullable', 'string'],
            'detail_step2_title' => ['nullable', 'string', 'max:255'],
            'detail_step2_icon' => ['nullable', 'string'],
            'detail_step2_description' => ['nullable', 'string'],
            'detail_step3_title' => ['nullable', 'string', 'max:255'],
            'detail_step3_icon' => ['nullable', 'string'],
            'detail_step3_description' => ['nullable', 'string'],
            'detail_faq_title' => ['nullable', 'string', 'max:255'],
            'detail_faqs' => ['nullable', 'array'],
            'detail_faqs.*.question' => ['nullable', 'string', 'max:255'],
            'detail_faqs.*.answer' => ['nullable', 'string'],
            'detail_faq1_question' => ['nullable', 'string', 'max:255'],
            'detail_faq1_answer' => ['nullable', 'string'],
            'detail_faq2_question' => ['nullable', 'string', 'max:255'],
            'detail_faq2_answer' => ['nullable', 'string'],
            'detail_faq3_question' => ['nullable', 'string', 'max:255'],
            'detail_faq3_answer' => ['nullable', 'string'],
            'detail_faq4_question' => ['nullable', 'string', 'max:255'],
            'detail_faq4_answer' => ['nullable', 'string'],
            'detail_faq5_question' => ['nullable', 'string', 'max:255'],
            'detail_faq5_answer' => ['nullable', 'string'],
        ]);

        $parentService = null;
        $childService = null;

        if (!empty($validated['parentid'])) {
            $parentService = Service::find($validated['parentid']);

            if (!$parentService || $parentService->type !== 'main') {
                return response()->json([
                    'message' => 'Selected parent service must be a main service.',
                    'errors' => ['parentid' => ['Selected parent service must be a main service.']]
                ], 422);
            }
        }

        if (!empty($validated['childid'])) {
            $childService = Service::find($validated['childid']);

            if (!$childService || !$parentService || (int) $childService->parentid !== (int) $parentService->id) {
                return response()->json([
                    'message' => 'Selected child service does not belong to selected main service.',
                    'errors' => ['childid' => ['Selected child service does not belong to selected main service.']]
                ], 422);
            }
        }

        // Determine type and id to save
        if(!empty($validated['childid']) && !empty($validated['parentid'])){
            // Child selected
            $type = 'prechild';
            $finalId = $childService->id;
        } elseif(!empty($validated['parentid'])&& empty($validated['childid'])){
            // Only parent selected
            $type = 'child';
            $finalId = $parentService->id;
        } elseif(empty($validated['parentid']) && empty($validated['childid'])){
            // Both empty → still save as main
            $type = 'main';
            $finalId = null;
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('site-settings/services', 'public');
        }

        $ctaImagePath = null;
        if ($request->hasFile('detail_cta_image')) {
            $ctaImagePath = $request->file('detail_cta_image')->store('site-settings/services/cta', 'public');
        }

        $featureItems = $this->resolveServiceFeatureItems($request, $validated);

        // Create service
        Service::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'detail_content' => $this->buildServiceDetailContent($validated, $ctaImagePath, $featureItems),
            'slug' => $slug,
            'parentid' => $finalId,
            'type' => $type,
        ]);

        return redirect()->route('admin.site.services')->with('success', 'Service added successfully.');
    }

    public function getParentServices()
    {
        $parents = Service::where('type', 'main')->orderBy('name')->get();

        return response()->json($parents);
    }

    public function getServiceData($slug)
    {
        $service = Service::with('parent')->where('slug',$slug)->firstOrFail();

        $breadcrumb = [];
        $current = $service;

        while($current){
            array_unshift($breadcrumb, $current->name);
            $current = $current->parent;
        }

        return response()->json([
            'breadcrumb' => $breadcrumb
        ]);
    }

    public function getServiceChildren($slug)
    {
        $parent = Service::where('slug',$slug)->firstOrFail();

        $query = Service::where('parentid',$parent->id);
        $total = $query->count();

        if(request()->has('search') && request('search')['value']){
            $search = request('search')['value'];
            $query->where(function($q) use ($search){
                $q->where('name','like',"%{$search}%")
                    ->orWhere('slug','like',"%{$search}%");
            });
        }

        $filtered = $query->count();

        $start = request('start',0);
        $length = request('length',10);

        $children = $query->skip($start)->take($length)->get();

        $data = $children->map(function($child,$index) use($start){

            $hasChildren = Service::where('parentid',$child->id)->exists();

            $viewBtn = $hasChildren
                ? '<button class="btn btn-sm btn-info viewServiceBtn" data-slug="'.$child->slug.'">View</button>'
                : '';

            return [
                'DT_RowIndex'=>$index+1+$start,
                'name'=>$child->name,
                'slug'=>$child->slug,
                'action'=>$viewBtn.'<button class="btn btn-sm btn-primary editServiceBtn" data-id="'.$child->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteServiceBtn" data-id="'.$child->id.'">Delete</button>'
            ];
        });

        return response()->json([
            'draw'=>request('draw',1),
            'recordsTotal'=>$total,
            'recordsFiltered'=>$filtered,
            'data'=>$data
        ]);
    }
    public function deleteService($id)
    {
        $service = Service::findOrFail($id);

        $this->deleteRecursive($service);

        return response()->json([
            'success'=>true,
            'message'=>'Service deleted successfully'
        ]);
    }

    private function deleteRecursive($service)
    {
        foreach($service->children as $child){
            $this->deleteRecursive($child);
        }

        $service->delete();
    }

    public function getServicesByParent($parentId)
    {
        $children = Service::where('parentid', $parentId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($children);
    }

    public function editService($id)
    {
        $service = Service::with('parent')->findOrFail($id);
        $detailContent = $this->extractServiceDetailContent($service);


        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'image_url' => $service->image ? asset('storage/' . $service->image) : null,
            'parent_name' => $service->parent->name ?? null,
            'type' => $service->type,
            'detail_content' => $detailContent,
        ]);
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && str_word_count(strip_tags($value)) > 200) {
                        $fail('Description may not be greater than 200 words.');
                    }
                },
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'detail_sidebar_title' => ['nullable', 'string', 'max:255'],
            'detail_cta_text' => ['nullable', 'string', 'max:255'],
            'detail_cta_title' => ['nullable', 'string', 'max:255'],
            'detail_cta_button_text' => ['nullable', 'string', 'max:255'],
            'detail_cta_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_detail_cta_image' => ['nullable', 'boolean'],
            'detail_highlights_title' => ['nullable', 'string', 'max:255'],
            'detail_highlights_text' => ['nullable', 'string'],
            'detail_highlights_items' => ['nullable', 'string'],
            'detail_features_title' => ['nullable', 'string', 'max:255'],
            'detail_features' => ['nullable', 'array'],
            'detail_features.*.title' => ['nullable', 'string', 'max:255'],
            'detail_features.*.description' => ['nullable', 'string'],
            'detail_features.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'detail_features.*.icon' => ['nullable', 'string'],
            'detail_features.*.icon_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'detail_features.*.existing_image' => ['nullable', 'string'],
            'detail_features.*.existing_icon' => ['nullable', 'string'],
            'detail_features.*.remove_image' => ['nullable', 'boolean'],
            'detail_features.*.remove_icon' => ['nullable', 'boolean'],
            'detail_feature1_title' => ['nullable', 'string', 'max:255'],
            'detail_feature1_description' => ['nullable', 'string'],
            'detail_feature2_title' => ['nullable', 'string', 'max:255'],
            'detail_feature2_description' => ['nullable', 'string'],
            'detail_steps_heading' => ['nullable', 'string', 'max:255'],
            'detail_faq_heading' => ['nullable', 'string', 'max:255'],
            'detail_steps_title' => ['nullable', 'string', 'max:255'],
            'detail_steps_text' => ['nullable', 'string'],
            'detail_step1_title' => ['nullable', 'string', 'max:255'],
            'detail_step1_icon' => ['nullable', 'string'],
            'detail_step1_description' => ['nullable', 'string'],
            'detail_step2_title' => ['nullable', 'string', 'max:255'],
            'detail_step2_icon' => ['nullable', 'string'],
            'detail_step2_description' => ['nullable', 'string'],
            'detail_step3_title' => ['nullable', 'string', 'max:255'],
            'detail_step3_icon' => ['nullable', 'string'],
            'detail_step3_description' => ['nullable', 'string'],
            'detail_faq_title' => ['nullable', 'string', 'max:255'],
            'detail_faqs' => ['nullable', 'array'],
            'detail_faqs.*.question' => ['nullable', 'string', 'max:255'],
            'detail_faqs.*.answer' => ['nullable', 'string'],
            'detail_faq1_question' => ['nullable', 'string', 'max:255'],
            'detail_faq1_answer' => ['nullable', 'string'],
            'detail_faq2_question' => ['nullable', 'string', 'max:255'],
            'detail_faq2_answer' => ['nullable', 'string'],
            'detail_faq3_question' => ['nullable', 'string', 'max:255'],
            'detail_faq3_answer' => ['nullable', 'string'],
            'detail_faq4_question' => ['nullable', 'string', 'max:255'],
            'detail_faq4_answer' => ['nullable', 'string'],
            'detail_faq5_question' => ['nullable', 'string', 'max:255'],
            'detail_faq5_answer' => ['nullable', 'string'],
        ]);

        // Slug regenerate only if name changed
        if($service->name != $validated['name']){
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;

            while(Service::where('slug',$slug)->where('id','!=',$service->id)->exists()){
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            $service->slug = $slug;
        }

        if ($request->boolean('remove_image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $service->image = null;
        }

        if ($request->hasFile('image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $service->image = $request->file('image')->store('site-settings/services', 'public');
        }

        $existingDetailContent = is_array($service->detail_content) ? $service->detail_content : [];
        $ctaImagePath = $existingDetailContent['cta_image'] ?? null;

        if ($request->boolean('remove_detail_cta_image')) {
            if (
                filled($ctaImagePath)
                && !Str::startsWith((string) $ctaImagePath, ['http://', 'https://'])
                && Storage::disk('public')->exists($ctaImagePath)
            ) {
                Storage::disk('public')->delete($ctaImagePath);
            }

            $ctaImagePath = null;
        }

        if ($request->hasFile('detail_cta_image')) {
            if (
                filled($ctaImagePath)
                && !Str::startsWith((string) $ctaImagePath, ['http://', 'https://'])
                && Storage::disk('public')->exists($ctaImagePath)
            ) {
                Storage::disk('public')->delete($ctaImagePath);
            }

            $ctaImagePath = $request->file('detail_cta_image')->store('site-settings/services/cta', 'public');
        }

        $existingFeatures = collect($existingDetailContent['features'] ?? [])->values()->all();
        $featureItems = $this->resolveServiceFeatureItems($request, $validated, $existingFeatures);
        $this->deleteRemovedServiceFeatureImages($existingFeatures, $featureItems);

        $service->name = $validated['name'];
        $service->description = $validated['description'] ?? null;
        $service->detail_content = $this->buildServiceDetailContent($validated, $ctaImagePath, $featureItems);
        $service->save();

        return response()->json([
            'success'=>true,
            'message'=>'Service updated successfully'
        ]);
    }

    private function buildServiceDetailContent(array $validated, ?string $ctaImagePath = null, ?array $featureItems = null): ?array
    {
        $highlights = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['detail_highlights_items'] ?? '')))
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        $steps = collect([
            [
                'title' => $validated['detail_step1_title'] ?? null,
                'icon' => $validated['detail_step1_icon'] ?? null,
                'description' => $validated['detail_step1_description'] ?? null,
            ],
            [
                'title' => $validated['detail_step2_title'] ?? null,
                'icon' => $validated['detail_step2_icon'] ?? null,
                'description' => $validated['detail_step2_description'] ?? null,
            ],
            [
                'title' => $validated['detail_step3_title'] ?? null,
                'icon' => $validated['detail_step3_icon'] ?? null,
                'description' => $validated['detail_step3_description'] ?? null,
            ],
        ])->filter(fn ($step) => filled($step['title']) || filled($step['description']) || filled($step['icon']))
            ->values()
            ->all();

        $faqs = collect($validated['detail_faqs'] ?? [])
            ->map(function ($faq) {
                return [
                    'question' => isset($faq['question']) ? trim((string) $faq['question']) : null,
                    'answer' => isset($faq['answer']) ? trim((string) $faq['answer']) : null,
                ];
            })
            ->filter(fn ($faq) => filled($faq['question']) || filled($faq['answer']))
            ->values();

        if ($faqs->isEmpty()) {
            $faqs = collect([
                [
                    'question' => $validated['detail_faq1_question'] ?? null,
                    'answer' => $validated['detail_faq1_answer'] ?? null,
                ],
                [
                    'question' => $validated['detail_faq2_question'] ?? null,
                    'answer' => $validated['detail_faq2_answer'] ?? null,
                ],
                [
                    'question' => $validated['detail_faq3_question'] ?? null,
                    'answer' => $validated['detail_faq3_answer'] ?? null,
                ],
                [
                    'question' => $validated['detail_faq4_question'] ?? null,
                    'answer' => $validated['detail_faq4_answer'] ?? null,
                ],
                [
                    'question' => $validated['detail_faq5_question'] ?? null,
                    'answer' => $validated['detail_faq5_answer'] ?? null,
                ],
            ])->filter(fn ($faq) => filled($faq['question']) || filled($faq['answer']))
                ->values();
        }

        $faqs = $faqs->all();

        $resolvedFeatures = $featureItems;
        if (!is_array($resolvedFeatures)) {
            $resolvedFeatures = [
                [
                    'title' => $validated['detail_feature1_title'] ?? null,
                    'description' => $validated['detail_feature1_description'] ?? null,
                    'image' => null,
                    'icon' => null,
                ],
                [
                    'title' => $validated['detail_feature2_title'] ?? null,
                    'description' => $validated['detail_feature2_description'] ?? null,
                    'image' => null,
                    'icon' => null,
                ],
            ];
        }

        $resolvedFeatures = collect($resolvedFeatures)
            ->map(function ($feature) {
                return [
                    'title' => $feature['title'] ?? null,
                    'description' => $feature['description'] ?? null,
                    'image' => $feature['image'] ?? null,
                    'icon' => $feature['icon'] ?? null,
                ];
            })
            ->filter(fn ($feature) => filled($feature['title']) || filled($feature['description']) || filled($feature['image']) || filled($feature['icon']))
            ->values()
            ->all();

        $content = [
            'sidebar_title' => $validated['detail_sidebar_title'] ?? null,
            'cta_text' => $validated['detail_cta_text'] ?? null,
            'cta_title' => $validated['detail_cta_title'] ?? null,
            'cta_button_text' => $validated['detail_cta_button_text'] ?? null,
            'cta_image' => $ctaImagePath,
            'highlights_title' => $validated['detail_highlights_title'] ?? null,
            'highlights_text' => $validated['detail_highlights_text'] ?? null,
            'highlights' => $highlights,
            'features_title' => $validated['detail_features_title'] ?? null,
            'features' => $resolvedFeatures,
            'steps_heading' => $validated['detail_steps_heading'] ?? null,
            'steps_title' => $validated['detail_steps_title'] ?? null,
            'steps_text' => $validated['detail_steps_text'] ?? null,
            'steps' => $steps,
            'faq_heading' => $validated['detail_faq_heading'] ?? null,
            'faq_title' => $validated['detail_faq_title'] ?? null,
            'faqs' => $faqs,
        ];

        $hasAnyValue = collect($content)->flatten()->contains(fn ($value) => filled($value));

        return $hasAnyValue ? $content : null;
    }

    private function resolveServiceFeatureItems(Request $request, array $validated, array $existingFeatures = []): array
    {
        $features = collect($validated['detail_features'] ?? [])->values()->map(function ($feature, $index) use ($request, $existingFeatures) {
            $title = isset($feature['title']) ? trim((string) $feature['title']) : null;
            $description = isset($feature['description']) ? trim((string) $feature['description']) : null;
            $icon = isset($feature['icon']) ? trim((string) $feature['icon']) : null;
            $existingImage = $feature['existing_image'] ?? ($existingFeatures[$index]['image'] ?? null);
            $existingIcon = $feature['existing_icon'] ?? ($existingFeatures[$index]['icon'] ?? null);
            $removeImage = filter_var($feature['remove_image'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $removeIcon = filter_var($feature['remove_icon'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($removeImage) {
                if (
                    filled($existingImage)
                    && !Str::startsWith((string) $existingImage, ['http://', 'https://'])
                    && Storage::disk('public')->exists($existingImage)
                ) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = null;
            }

            if ($request->hasFile("detail_features.$index.image")) {
                if (
                    filled($existingImage)
                    && !Str::startsWith((string) $existingImage, ['http://', 'https://'])
                    && Storage::disk('public')->exists($existingImage)
                ) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = $request->file("detail_features.$index.image")->store('site-settings/services/features', 'public');
            }

            if ($removeIcon) {
                if (
                    filled($existingIcon)
                    && !Str::startsWith((string) $existingIcon, ['http://', 'https://'])
                    && !preg_match('/\s/', (string) $existingIcon)
                    && Storage::disk('public')->exists($existingIcon)
                ) {
                    Storage::disk('public')->delete($existingIcon);
                }

                $existingIcon = null;
                $icon = null;
            }

            if ($request->hasFile("detail_features.$index.icon_image")) {
                if (
                    filled($existingIcon)
                    && !Str::startsWith((string) $existingIcon, ['http://', 'https://'])
                    && !preg_match('/\s/', (string) $existingIcon)
                    && Storage::disk('public')->exists($existingIcon)
                ) {
                    Storage::disk('public')->delete($existingIcon);
                }

                $icon = $request->file("detail_features.$index.icon_image")->store('site-settings/services/features/icons', 'public');
            } elseif (!filled($icon) && !$removeIcon) {
                $icon = $existingIcon;
            }

            return [
                'title' => $title,
                'description' => $description,
                'image' => $existingImage,
                'icon' => $icon,
            ];
        })->filter(fn ($feature) => filled($feature['title']) || filled($feature['description']) || filled($feature['image']) || filled($feature['icon']))
            ->values();

        if ($features->isEmpty()) {
            $features = collect([
                [
                    'title' => $validated['detail_feature1_title'] ?? null,
                    'description' => $validated['detail_feature1_description'] ?? null,
                    'image' => $existingFeatures[0]['image'] ?? null,
                    'icon' => $existingFeatures[0]['icon'] ?? null,
                ],
                [
                    'title' => $validated['detail_feature2_title'] ?? null,
                    'description' => $validated['detail_feature2_description'] ?? null,
                    'image' => $existingFeatures[1]['image'] ?? null,
                    'icon' => $existingFeatures[1]['icon'] ?? null,
                ],
            ])->filter(fn ($feature) => filled($feature['title']) || filled($feature['description']) || filled($feature['image']) || filled($feature['icon']))
                ->values();
        }

        return $features->all();
    }

    private function deleteRemovedServiceFeatureImages(array $existingFeatures, array $resolvedFeatures): void
    {
        $oldImages = collect($existingFeatures)
            ->pluck('image')
            ->filter(fn ($path) => filled($path) && !Str::startsWith((string) $path, ['http://', 'https://']))
            ->unique();

        $newImages = collect($resolvedFeatures)
            ->pluck('image')
            ->filter(fn ($path) => filled($path) && !Str::startsWith((string) $path, ['http://', 'https://']))
            ->unique();

        $oldIcons = collect($existingFeatures)
            ->pluck('icon')
            ->filter(fn ($path) => filled($path) && !Str::startsWith((string) $path, ['http://', 'https://']))
            ->unique();

        $newIcons = collect($resolvedFeatures)
            ->pluck('icon')
            ->filter(fn ($path) => filled($path) && !Str::startsWith((string) $path, ['http://', 'https://']))
            ->unique();

        $oldImages->diff($newImages)->each(function ($path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        });

        $oldIcons->diff($newIcons)->each(function ($path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        });
    }

    private function extractServiceDetailContent(Service $service): array
    {
        $content = is_array($service->detail_content) ? $service->detail_content : [];

        $features = collect($content['features'] ?? [])->values();
        $steps = collect($content['steps'] ?? [])->values();
        $faqs = collect($content['faqs'] ?? [])->values();
        $ctaImage = $content['cta_image'] ?? '';

        return [
            'sidebar_title' => $content['sidebar_title'] ?? '',
            'cta_text' => $content['cta_text'] ?? '',
            'cta_title' => $content['cta_title'] ?? '',
            'cta_button_text' => $content['cta_button_text'] ?? '',
            'cta_image' => $ctaImage,
            'cta_image_url' => filled($ctaImage)
                ? (Str::startsWith((string) $ctaImage, ['http://', 'https://']) ? $ctaImage : asset('storage/' . ltrim((string) $ctaImage, '/')))
                : '',
            'highlights_title' => $content['highlights_title'] ?? '',
            'highlights_text' => $content['highlights_text'] ?? '',
            'highlights_items' => collect($content['highlights'] ?? [])->implode("\n"),
            'features_title' => $content['features_title'] ?? '',
            'features' => $features
                ->map(function ($feature) {
                    $featureImage = $feature['image'] ?? '';
                    $featureIcon = $feature['icon'] ?? '';

                    return [
                        'title' => $feature['title'] ?? '',
                        'description' => $feature['description'] ?? '',
                        'image' => $featureImage,
                        'image_url' => filled($featureImage)
                            ? (Str::startsWith((string) $featureImage, ['http://', 'https://']) ? $featureImage : asset('storage/' . ltrim((string) $featureImage, '/')))
                            : '',
                        'icon' => $featureIcon,
                        'icon_url' => filled($featureIcon)
                            ? (Str::startsWith((string) $featureIcon, ['http://', 'https://']) ? $featureIcon : asset('storage/' . ltrim((string) $featureIcon, '/')))
                            : '',
                    ];
                })
                ->values()
                ->all(),
            'feature1_title' => $features->get(0)['title'] ?? '',
            'feature1_description' => $features->get(0)['description'] ?? '',
            'feature2_title' => $features->get(1)['title'] ?? '',
            'feature2_description' => $features->get(1)['description'] ?? '',
            'steps_heading' => $content['steps_heading'] ?? '',
            'steps_title' => $content['steps_title'] ?? '',
            'steps_text' => $content['steps_text'] ?? '',
            'step1_title' => $steps->get(0)['title'] ?? '',
            'step1_icon' => $steps->get(0)['icon'] ?? '',
            'step1_description' => $steps->get(0)['description'] ?? '',
            'step2_title' => $steps->get(1)['title'] ?? '',
            'step2_icon' => $steps->get(1)['icon'] ?? '',
            'step2_description' => $steps->get(1)['description'] ?? '',
            'step3_title' => $steps->get(2)['title'] ?? '',
            'step3_icon' => $steps->get(2)['icon'] ?? '',
            'step3_description' => $steps->get(2)['description'] ?? '',
            'faq_heading' => $content['faq_heading'] ?? '',
            'faq_title' => $content['faq_title'] ?? '',
            'faqs' => $faqs
                ->map(function ($faq) {
                    return [
                        'question' => $faq['question'] ?? '',
                        'answer' => $faq['answer'] ?? '',
                    ];
                })
                ->values()
                ->all(),
            'faq1_question' => $faqs->get(0)['question'] ?? '',
            'faq1_answer' => $faqs->get(0)['answer'] ?? '',
            'faq2_question' => $faqs->get(1)['question'] ?? '',
            'faq2_answer' => $faqs->get(1)['answer'] ?? '',
            'faq3_question' => $faqs->get(2)['question'] ?? '',
            'faq3_answer' => $faqs->get(2)['answer'] ?? '',
            'faq4_question' => $faqs->get(3)['question'] ?? '',
            'faq4_answer' => $faqs->get(3)['answer'] ?? '',
            'faq5_question' => $faqs->get(4)['question'] ?? '',
            'faq5_answer' => $faqs->get(4)['answer'] ?? '',
        ];
    }


//    Pages Home

    public function siteHome()
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);

        return view('admin.sitesetting.pages.home', compact('homeSetting'));
    }

    public function siteAbout()
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);

        return view('admin.sitesetting.pages.about', compact('homeSetting'));
    }

    public function siteContactPage()
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $contactData = is_array($homeSetting->contact_data) ? $homeSetting->contact_data : [];

        return view('admin.sitesetting.pages.contact', compact('contactData'));
    }

    public function siteServicesPage()
    {
        $mainServices = Service::whereNull('parentid')->orderBy('name')->get(['id', 'name']);

        return view('admin.sitesetting.pages.services', compact('mainServices'));
    }

    public function sitePatientGuidePage()
    {
        $mainPatientGuides = PatientGuide::whereNull('parentid')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.sitesetting.pages.patientguide', compact('mainPatientGuides'));
    }

    public function siteSmtpPage()
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $mailData = is_array($homeSetting->mail_data) ? $homeSetting->mail_data : [];

        return view('admin.sitesetting.pages.smtp', compact('mailData'));
    }

    public function siteHeaderFooterPage()
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $headerFooterData = is_array($homeSetting->header_footer_data) ? $homeSetting->header_footer_data : [];

        return view('admin.sitesetting.pages.header-footer', compact('headerFooterData'));
    }

public function storeHeaderFooterSettings(Request $request)
    {
        $validated = $request->validate([
            'website_name' => ['nullable', 'string', 'max:255'],
            'website_tagline' => ['nullable', 'string', 'max:255'],
            'header_help_text' => ['nullable', 'string', 'max:255'],
            'header_phone' => ['nullable', 'string', 'max:255'],
            'footer_about_text' => ['nullable', 'string', 'max:500'],
            'footer_phone_label' => ['nullable', 'string', 'max:255'],
            'footer_phone' => ['nullable', 'string', 'max:255'],
            'footer_support_label' => ['nullable', 'string', 'max:255'],
            'footer_email' => ['nullable', 'email', 'max:255'],
            'footer_copyright' => ['nullable', 'string', 'max:255'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg', 'max:2048'],
            'header_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'footer_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $data = is_array($homeSetting->header_footer_data) ? $homeSetting->header_footer_data : [];

        if ($request->hasFile('header_logo')) {
            if (!empty($data['header_logo']) && Storage::disk('public')->exists($data['header_logo'])) {
                Storage::disk('public')->delete($data['header_logo']);
            }
            $data['header_logo'] = $request->file('header_logo')->store('site-settings/header-footer', 'public');
        }

        if ($request->hasFile('footer_logo')) {
            if (!empty($data['footer_logo']) && Storage::disk('public')->exists($data['footer_logo'])) {
                Storage::disk('public')->delete($data['footer_logo']);
            }
            $data['footer_logo'] = $request->file('footer_logo')->store('site-settings/header-footer', 'public');
        }

        if ($request->hasFile('favicon')) {
            if (!empty($data['favicon']) && Storage::disk('public')->exists($data['favicon'])) {
                Storage::disk('public')->delete($data['favicon']);
            }
            $data['favicon'] = $request->file('favicon')->store('site-settings/header-footer', 'public');
        }

        $data['website_name'] = $validated['website_name'] ?? ($data['website_name'] ?? config('app.name'));
        $data['website_tagline'] = $validated['website_tagline'] ?? ($data['website_tagline'] ?? 'Trusted care for every patient.');
        $data['header_help_text'] = $validated['header_help_text'] ?? ($data['header_help_text'] ?? 'need help !');
        $data['header_phone'] = $validated['header_phone'] ?? ($data['header_phone'] ?? '(+01) 789 987 645');
        $data['footer_about_text'] = $validated['footer_about_text'] ?? ($data['footer_about_text'] ?? 'Committed to compassionate care and better outcomes.');
        $data['footer_phone_label'] = $validated['footer_phone_label'] ?? ($data['footer_phone_label'] ?? 'Toll free customer care');
        $data['footer_phone'] = $validated['footer_phone'] ?? ($data['footer_phone'] ?? '+123 456 789');
        $data['footer_support_label'] = $validated['footer_support_label'] ?? ($data['footer_support_label'] ?? 'Need live support!');
        $data['footer_email'] = $validated['footer_email'] ?? ($data['footer_email'] ?? 'info@domainname.com');
        $data['footer_copyright'] = $validated['footer_copyright'] ?? ($data['footer_copyright'] ?? 'Copyright © 2025 All Rights Reserved.');

        $homeSetting->header_footer_data = $data;
        $homeSetting->save();

        return redirect()->route('admin.site.header-footer')->with('success', 'Header & Footer settings updated successfully.');
    }
    public function storeSmtpSettings(Request $request)
    {
        $request->merge([
            'mail_host' => filled($request->input('mail_host')) ? $request->input('mail_host') : null,
            'mail_port' => filled($request->input('mail_port')) ? $request->input('mail_port') : null,
            'mail_encryption' => filled($request->input('mail_encryption')) ? $request->input('mail_encryption') : null,
            'mail_username' => filled($request->input('mail_username')) ? $request->input('mail_username') : null,
            'mail_password' => filled($request->input('mail_password')) ? $request->input('mail_password') : null,
            'mail_from_address' => filled($request->input('mail_from_address')) ? $request->input('mail_from_address') : null,
            'mail_from_name' => filled($request->input('mail_from_name')) ? $request->input('mail_from_name') : null,
            'mail_admin_email' => filled($request->input('mail_admin_email')) ? $request->input('mail_admin_email') : null,
        ]);

        $validated = $request->validate([
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'integer', 'min:1'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_from_address' => ['nullable', 'email', 'max:255'],
            'mail_from_name' => ['nullable', 'string', 'max:255'],
            'mail_admin_email' => ['nullable', 'email', 'max:255'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $homeSetting->mail_data = [
            'host' => $validated['mail_host'] ?? '',
            'port' => $validated['mail_port'] ?? '',
            'encryption' => $validated['mail_encryption'] ?? '',
            'username' => $validated['mail_username'] ?? '',
            'password' => $validated['mail_password'] ?? '',
            'from_address' => $validated['mail_from_address'] ?? '',
            'from_name' => $validated['mail_from_name'] ?? '',
            'admin_email' => $validated['mail_admin_email'] ?? '',
        ];
        $homeSetting->save();

        return redirect()->route('admin.site.smtp')->with('success', 'SMTP settings updated successfully.');
    }

    public function getPatientGuidePageData($id)
    {
        $guide = PatientGuide::findOrFail($id);
        $detail = is_array($guide->detail_content) ? $guide->detail_content : [];
        $headImageRaw = $detail['head_image'] ?? null;
        $headImageUrl = filled($headImageRaw)
            ? (\Illuminate\Support\Str::startsWith((string) $headImageRaw, ['http://', 'https://'])
                ? $headImageRaw
                : asset('storage/' . ltrim((string) $headImageRaw, '/')))
            : null;

        return response()->json([
            'id' => $guide->id,
            'name' => $guide->name,
            'description' => $guide->description,
            'detail_content' => [
                'form_mode' => $detail['form_mode'] ?? 'tabs',
                'tab_one_label' => $detail['tab_one_label'] ?? '',
                'tab_two_label' => $detail['tab_two_label'] ?? '',
                'tab_one_title' => $detail['tab_one_title'] ?? '',
                'tab_two_title' => $detail['tab_two_title'] ?? '',
                'sidebar_title' => $detail['sidebar_title'] ?? '',
                'content_title' => $detail['content_title'] ?? '',
                'content_text' => $detail['content_text'] ?? '',
                'head_image' => $headImageRaw,
                'head_image_url' => $headImageUrl,
            ],
        ]);
    }

    public function updatePatientGuidePageData(Request $request, $id)
    {
        $guide = PatientGuide::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'detail_form_mode' => ['nullable', 'in:tabs,analysis_only'],
            'detail_tab_one_label' => ['nullable', 'string', 'max:255'],
            'detail_tab_two_label' => ['nullable', 'string', 'max:255'],
            'detail_tab_one_title' => ['nullable', 'string', 'max:255'],
            'detail_tab_two_title' => ['nullable', 'string', 'max:255'],
            'detail_sidebar_title' => ['nullable', 'string', 'max:255'],
            'detail_content_title' => ['nullable', 'string', 'max:255'],
            'detail_content_text' => ['nullable', 'string'],
            'detail_head_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $existingDetail = is_array($guide->detail_content) ? $guide->detail_content : [];

        $headImagePath = $existingDetail['head_image'] ?? null;
        if ($request->hasFile('detail_head_image')) {
            if (!empty($headImagePath) && Storage::disk('public')->exists($headImagePath)) {
                Storage::disk('public')->delete($headImagePath);
            }

            $headImagePath = $request->file('detail_head_image')->store('site-settings/patient-guide', 'public');
        }

        $guide->name = $validated['name'];
        $guide->description = $validated['description'] ?? null;
        $guide->detail_content = [
            'form_mode' => $validated['detail_form_mode'] ?? ($existingDetail['form_mode'] ?? 'tabs'),
            'tab_one_label' => $validated['detail_tab_one_label'] ?? ($existingDetail['tab_one_label'] ?? 'Online Hair Analysis'),
            'tab_two_label' => $validated['detail_tab_two_label'] ?? ($existingDetail['tab_two_label'] ?? 'Booking'),
            'tab_one_title' => $validated['detail_tab_one_title'] ?? ($existingDetail['tab_one_title'] ?? 'Get a Free Online Hair Analysis'),
            'tab_two_title' => $validated['detail_tab_two_title'] ?? ($existingDetail['tab_two_title'] ?? 'Book Consultation'),
            'sidebar_title' => $validated['detail_sidebar_title'] ?? ($existingDetail['sidebar_title'] ?? ''),
            'content_title' => $validated['detail_content_title'] ?? ($existingDetail['content_title'] ?? ''),
            'content_text' => $validated['detail_content_text'] ?? ($existingDetail['content_text'] ?? ''),
            'head_image' => $headImagePath,
        ];
        $guide->save();

        return response()->json([
            'success' => true,
            'message' => 'Patient guide updated successfully.',
        ]);
    }

    public function storeAboutPage(Request $request)
    {
        $validated = $request->validate([
            'page_header_title' => ['nullable', 'string', 'max:255'],
            'page_header_highlight' => ['nullable', 'string', 'max:255'],
            'breadcrumb_home_text' => ['nullable', 'string', 'max:255'],
            'breadcrumb_home_link' => ['nullable', 'string', 'max:255'],
            'breadcrumb_current' => ['nullable', 'string', 'max:255'],

            'about_subtitle' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string'],
            'about_funded_amount' => ['nullable', 'string', 'max:255'],
            'about_funded_label' => ['nullable', 'string', 'max:255'],
            'about_support_title' => ['nullable', 'string', 'max:255'],
            'about_support_description' => ['nullable', 'string'],
            'about_donate_button_text' => ['nullable', 'string', 'max:255'],
            'about_donate_button_link' => ['nullable', 'string', 'max:255'],
            'about_helped_fund_count' => ['nullable', 'string', 'max:255'],
            'about_helped_fund_title' => ['nullable', 'string', 'max:255'],
            'about_helped_fund_description' => ['nullable', 'string'],

            'approach_subtitle' => ['nullable', 'string', 'max:255'],
            'approach_title' => ['nullable', 'string', 'max:255'],
            'approach_description' => ['nullable', 'string'],
            'approach_button_text' => ['nullable', 'string', 'max:255'],
            'approach_button_link' => ['nullable', 'string', 'max:255'],
            'approach_mission_title' => ['nullable', 'string', 'max:255'],
            'approach_mission_description' => ['nullable', 'string'],
            'approach_vision_title' => ['nullable', 'string', 'max:255'],
            'approach_vision_description' => ['nullable', 'string'],
            'approach_value_title' => ['nullable', 'string', 'max:255'],
            'approach_value_description' => ['nullable', 'string'],

            'why_subtitle' => ['nullable', 'string', 'max:255'],
            'why_title' => ['nullable', 'string', 'max:255'],
            'why_description' => ['nullable', 'string'],
            'why_points' => ['nullable', 'string'],
            'why_counter1_number' => ['nullable', 'string', 'max:255'],
            'why_counter1_label' => ['nullable', 'string', 'max:255'],
            'why_counter2_number' => ['nullable', 'string', 'max:255'],
            'why_counter2_label' => ['nullable', 'string', 'max:255'],
            'why_counter3_number' => ['nullable', 'string', 'max:255'],
            'why_counter3_label' => ['nullable', 'string', 'max:255'],

            'how_subtitle' => ['nullable', 'string', 'max:255'],
            'how_title' => ['nullable', 'string', 'max:255'],
            'how_description' => ['nullable', 'string'],
            'how_points' => ['nullable', 'string'],
            'how_button_text' => ['nullable', 'string', 'max:255'],
            'how_button_link' => ['nullable', 'string', 'max:255'],
            'how_item1_title' => ['nullable', 'string', 'max:255'],
            'how_item1_description' => ['nullable', 'string'],
            'how_item2_title' => ['nullable', 'string', 'max:255'],
            'how_item2_description' => ['nullable', 'string'],
            'how_item3_title' => ['nullable', 'string', 'max:255'],
            'how_item3_description' => ['nullable', 'string'],
            'how_item4_title' => ['nullable', 'string', 'max:255'],
            'how_item4_description' => ['nullable', 'string'],

            'features_subtitle' => ['nullable', 'string', 'max:255'],
            'features_title' => ['nullable', 'string', 'max:255'],
            'features_description' => ['nullable', 'string'],
            'features_item1_percent' => ['nullable', 'string', 'max:255'],
            'features_item1_title' => ['nullable', 'string', 'max:255'],
            'features_item1_description' => ['nullable', 'string'],
            'features_item2_percent' => ['nullable', 'string', 'max:255'],
            'features_item2_title' => ['nullable', 'string', 'max:255'],
            'features_item2_description' => ['nullable', 'string'],
            'features_item3_percent' => ['nullable', 'string', 'max:255'],
            'features_item3_title' => ['nullable', 'string', 'max:255'],
            'features_item3_description' => ['nullable', 'string'],

            'fact_subtitle' => ['nullable', 'string', 'max:255'],
            'fact_title' => ['nullable', 'string', 'max:255'],
            'fact_description' => ['nullable', 'string'],
            'fact_counter1_number' => ['nullable', 'string', 'max:255'],
            'fact_counter1_label' => ['nullable', 'string', 'max:255'],
            'fact_counter2_number' => ['nullable', 'string', 'max:255'],
            'fact_counter2_label' => ['nullable', 'string', 'max:255'],

            'testimonials_subtitle' => ['nullable', 'string', 'max:255'],
            'testimonials_title' => ['nullable', 'string', 'max:255'],
            'testimonials_review_count' => ['nullable', 'string', 'max:255'],
            'testimonials_review_label' => ['nullable', 'string', 'max:255'],
            'testimonials_item1_name' => ['nullable', 'string', 'max:255'],
            'testimonials_item1_designation' => ['nullable', 'string', 'max:255'],
            'testimonials_item1_quote' => ['nullable', 'string'],
            'testimonials_item2_name' => ['nullable', 'string', 'max:255'],
            'testimonials_item2_designation' => ['nullable', 'string', 'max:255'],
            'testimonials_item2_quote' => ['nullable', 'string'],
            'testimonials_item3_name' => ['nullable', 'string', 'max:255'],
            'testimonials_item3_designation' => ['nullable', 'string', 'max:255'],
            'testimonials_item3_quote' => ['nullable', 'string'],

            'faq_subtitle' => ['nullable', 'string', 'max:255'],
            'faq_title' => ['nullable', 'string', 'max:255'],
            'faq1_question' => ['nullable', 'string', 'max:255'],
            'faq1_answer' => ['nullable', 'string'],
            'faq2_question' => ['nullable', 'string', 'max:255'],
            'faq2_answer' => ['nullable', 'string'],
            'faq3_question' => ['nullable', 'string', 'max:255'],
            'faq3_answer' => ['nullable', 'string'],
            'faq4_question' => ['nullable', 'string', 'max:255'],
            'faq4_answer' => ['nullable', 'string'],
            'faq5_question' => ['nullable', 'string', 'max:255'],
            'faq5_answer' => ['nullable', 'string'],

            'page_header_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'about_image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'about_image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'about_helped_fund_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'approach_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'why_image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'why_image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'features_item1_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'features_item2_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'features_item3_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'fact_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'fact_body_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'testimonials_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'testimonials_item1_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'testimonials_item2_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'testimonials_item3_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $aboutPageData = $homeSetting->about_page_data ?? [];

        $imageFields = [
            'page_header_image',
            'about_image1',
            'about_image2',
            'about_helped_fund_image',
            'approach_image',
            'why_image1',
            'why_image2',
            'features_item1_image',
            'features_item2_image',
            'features_item3_image',
            'fact_image',
            'fact_body_image',
            'testimonials_image',
            'testimonials_item1_image',
            'testimonials_item2_image',
            'testimonials_item3_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if (!empty($aboutPageData[$field]) && Storage::disk('public')->exists($aboutPageData[$field])) {
                    Storage::disk('public')->delete($aboutPageData[$field]);
                }

                $aboutPageData[$field] = $request->file($field)->store('site-settings/about-page', 'public');
            }
        }

        $scalarFields = [
            'page_header_title',
            'page_header_highlight',
            'breadcrumb_home_text',
            'breadcrumb_home_link',
            'breadcrumb_current',
            'about_subtitle',
            'about_title',
            'about_description',
            'about_funded_amount',
            'about_funded_label',
            'about_support_title',
            'about_support_description',
            'about_donate_button_text',
            'about_donate_button_link',
            'about_helped_fund_count',
            'about_helped_fund_title',
            'about_helped_fund_description',
            'approach_subtitle',
            'approach_title',
            'approach_description',
            'approach_button_text',
            'approach_button_link',
            'approach_mission_title',
            'approach_mission_description',
            'approach_vision_title',
            'approach_vision_description',
            'approach_value_title',
            'approach_value_description',
            'why_subtitle',
            'why_title',
            'why_description',
            'why_counter1_number',
            'why_counter1_label',
            'why_counter2_number',
            'why_counter2_label',
            'why_counter3_number',
            'why_counter3_label',
            'how_subtitle',
            'how_title',
            'how_description',
            'how_button_text',
            'how_button_link',
            'how_item1_title',
            'how_item1_description',
            'how_item2_title',
            'how_item2_description',
            'how_item3_title',
            'how_item3_description',
            'how_item4_title',
            'how_item4_description',
            'features_subtitle',
            'features_title',
            'features_description',
            'features_item1_percent',
            'features_item1_title',
            'features_item1_description',
            'features_item2_percent',
            'features_item2_title',
            'features_item2_description',
            'features_item3_percent',
            'features_item3_title',
            'features_item3_description',
            'fact_subtitle',
            'fact_title',
            'fact_description',
            'fact_counter1_number',
            'fact_counter1_label',
            'fact_counter2_number',
            'fact_counter2_label',
            'testimonials_subtitle',
            'testimonials_title',
            'testimonials_review_count',
            'testimonials_review_label',
            'testimonials_item1_name',
            'testimonials_item1_designation',
            'testimonials_item1_quote',
            'testimonials_item2_name',
            'testimonials_item2_designation',
            'testimonials_item2_quote',
            'testimonials_item3_name',
            'testimonials_item3_designation',
            'testimonials_item3_quote',
            'faq_subtitle',
            'faq_title',
            'faq1_question',
            'faq1_answer',
            'faq2_question',
            'faq2_answer',
            'faq3_question',
            'faq3_answer',
            'faq4_question',
            'faq4_answer',
            'faq5_question',
            'faq5_answer',
        ];

        foreach ($scalarFields as $field) {
            if (array_key_exists($field, $validated)) {
                $aboutPageData[$field] = $validated[$field];
            }
        }

        if ($request->has('why_points')) {
            $aboutPageData['why_points'] = collect(preg_split('/\r\n|\r|\n/', (string) ($request->input('why_points') ?? '')))
                ->map(fn ($point) => trim((string) $point))
                ->filter(fn ($point) => filled($point))
                ->values()
                ->all();
        }

        if ($request->has('how_points')) {
            $aboutPageData['how_points'] = collect(preg_split('/\r\n|\r|\n/', (string) ($request->input('how_points') ?? '')))
                ->map(fn ($point) => trim((string) $point))
                ->filter(fn ($point) => filled($point))
                ->values()
                ->all();
        }

        $homeSetting->about_page_data = $aboutPageData;
        $homeSetting->save();

        return redirect()->route('admin.site.about')->with('success', 'About page content updated successfully.');
    }

    public function storeHeroSection(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'hero_button_text' => ['nullable', 'string', 'max:255'],
            'hero_button_link' => ['nullable', 'string', 'max:255'],
            'hero_button_text_secondary' => ['nullable', 'string', 'max:255'],
            'hero_button_link_secondary' => ['nullable', 'string', 'max:255'],
            'hero_video_url' => ['nullable', 'string', 'max:500'],
            'hero_items' => ['nullable', 'array'],
            'hero_items.*' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hero_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/x-matroska,video/webm', 'max:102400'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);

        if ($request->hasFile('hero_image')) {
            if ($homeSetting->hero_image && Storage::disk('public')->exists($homeSetting->hero_image)) {
                Storage::disk('public')->delete($homeSetting->hero_image);
            }

            $validated['hero_image'] = $request->file('hero_image')->store('site-settings/hero', 'public');
        }

        if ($request->hasFile('hero_video')) {
            if ($homeSetting->hero_video && Storage::disk('public')->exists($homeSetting->hero_video)) {
                Storage::disk('public')->delete($homeSetting->hero_video);
            }

            $validated['hero_video'] = $request->file('hero_video')->store('site-settings/hero', 'public');
        }

        $heroItems = collect($request->input('hero_items', []))
            ->map(fn ($item) => is_string($item) ? trim($item) : $item)
            ->filter(fn ($item) => filled($item))
            ->values()
            ->all();

        unset($validated['hero_items']);

        $homeSetting->fill($validated);
        $homeSetting->hero_items = $heroItems;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Hero section updated successfully.');
    }

    public function removeHomeItem(Request $request)
    {
        $validated = $request->validate([
            'section' => ['required', 'string'],
            'index' => ['required', 'integer', 'min:0'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $section = $validated['section'];
        $index = (int) $validated['index'];

        switch ($section) {
            case 'hero_items':
                $items = collect($homeSetting->hero_items ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                unset($items[$index]);
                $homeSetting->hero_items = array_values($items);
                break;

            case 'whatwedo_features':
                $data = $homeSetting->whatwedo_data ?? [];
                $items = collect($data['features'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                unset($items[$index]);
                $data['features'] = array_values($items);
                $homeSetting->whatwedo_data = $data;
                break;

            case 'causes_items':
                $data = $homeSetting->causes_data ?? [];
                $items = collect($data['items'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $removed = $items[$index] ?? null;
                if (!empty($removed['image']) && Storage::disk('public')->exists($removed['image'])) {
                    Storage::disk('public')->delete($removed['image']);
                }

                unset($items[$index]);
                $data['items'] = array_values($items);
                $homeSetting->causes_data = $data;
                break;

            case 'whychoose_points':
                $data = $homeSetting->whychoose_data ?? [];
                $items = collect($data['whychoose_points'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                unset($items[$index]);
                $data['whychoose_points'] = array_values($items);
                $homeSetting->whychoose_data = $data;
                break;

            case 'howitwork_steps':
                $data = $homeSetting->howitwork_data ?? [];
                $items = collect($data['steps'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $removed = $items[$index] ?? null;
                if (!empty($removed['image']) && Storage::disk('public')->exists($removed['image'])) {
                    Storage::disk('public')->delete($removed['image']);
                }

                unset($items[$index]);
                $data['steps'] = array_values($items);
                $homeSetting->howitwork_data = $data;
                break;

            case 'testimonials_items':
                $data = $homeSetting->testimonials_data ?? [];
                $items = collect($data['items'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $removed = $items[$index] ?? null;
                if (!empty($removed['image']) && Storage::disk('public')->exists($removed['image'])) {
                    Storage::disk('public')->delete($removed['image']);
                }

                unset($items[$index]);
                $data['items'] = array_values($items);
                $homeSetting->testimonials_data = $data;
                break;

            case 'gallery_items':
                $data = $homeSetting->gallery_data ?? [];
                $items = collect($data['items'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $removed = $items[$index] ?? null;
                if (!empty($removed['image']) && Storage::disk('public')->exists($removed['image'])) {
                    Storage::disk('public')->delete($removed['image']);
                }

                unset($items[$index]);
                $data['items'] = array_values($items);
                $homeSetting->gallery_data = $data;
                break;

            case 'lasthope_items':
                $data = $homeSetting->lasthope_data ?? [];
                $items = collect($data['items'] ?? [])->values()->all();
                if (!array_key_exists($index, $items)) {
                    return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
                }

                $removed = $items[$index] ?? null;
                if (!empty($removed['image']) && Storage::disk('public')->exists($removed['image'])) {
                    Storage::disk('public')->delete($removed['image']);
                }

                unset($items[$index]);
                $data['items'] = array_values($items);
                $homeSetting->lasthope_data = $data;
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Invalid section.'], 422);
        }

        $homeSetting->save();

        return response()->json(['success' => true, 'message' => 'Item removed successfully.']);
    }

    public function storeAboutSection(Request $request)
    {
        $validated = $request->validate([
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_subtitle' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string'],
            'about_support_title' => ['nullable', 'string', 'max:255'],
            'about_support_description' => ['nullable', 'string'],
            'about_support_icon_svg' => ['nullable', 'string'],
            'about_years' => ['nullable', 'integer', 'min:0'],
            'about_doctors' => ['nullable', 'integer', 'min:0'],
            'about_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'about_image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $aboutData = $homeSetting->about_data ?? [];

        if ($request->hasFile('about_image')) {
            if (!empty($aboutData['about_image']) && Storage::disk('public')->exists($aboutData['about_image'])) {
                Storage::disk('public')->delete($aboutData['about_image']);
            }

            $aboutData['about_image'] = $request->file('about_image')->store('site-settings/about', 'public');
        }

        if ($request->hasFile('about_image2')) {
            if (!empty($aboutData['about_image2']) && Storage::disk('public')->exists($aboutData['about_image2'])) {
                Storage::disk('public')->delete($aboutData['about_image2']);
            }

            $aboutData['about_image2'] = $request->file('about_image2')->store('site-settings/about', 'public');
        }

        $aboutData['about_title'] = $validated['about_title'] ?? null;
        $aboutData['about_subtitle'] = $validated['about_subtitle'] ?? null;
        $aboutData['about_description'] = $validated['about_description'] ?? null;
        $aboutData['about_support_title'] = $validated['about_support_title'] ?? null;
        $aboutData['about_support_description'] = $validated['about_support_description'] ?? null;
        $aboutData['about_support_icon_svg'] = $validated['about_support_icon_svg'] ?? null;
        $aboutData['about_support_text'] = $validated['about_support_description'] ?? null;
        $aboutData['about_years'] = $validated['about_years'] ?? null;
        $aboutData['about_doctors'] = $validated['about_doctors'] ?? null;

        $homeSetting->about_data = $aboutData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'About section updated successfully.');
    }

    public function storeContactSection(Request $request)
    {
        $validated = $request->validate([
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['nullable', 'string'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_twitter' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
            'social_whatsapp' => ['nullable', 'string', 'max:255'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $homeSetting->contact_data = $validated;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Contact & social section updated successfully.');
    }

    public function storeContactPage(Request $request)
    {
        $validated = $request->validate([
            'contact_page_title' => ['nullable', 'string', 'max:255'],
            'contact_page_heading' => ['nullable', 'string', 'max:255'],
            'contact_form_subtitle' => ['nullable', 'string', 'max:255'],
            'contact_form_title' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_phone_alt' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'string', 'max:255'],
            'contact_email_alt' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['nullable', 'string'],
            'contact_map_iframe' => ['nullable', 'string'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_twitter' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
            'social_whatsapp' => ['nullable', 'string', 'max:255'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $existing = is_array($homeSetting->contact_data) ? $homeSetting->contact_data : [];
        $homeSetting->contact_data = array_merge($existing, $validated);
        $homeSetting->save();

        return redirect()->route('admin.site.contact')->with('success', 'Contact page settings updated successfully.');
    }

    public function storeServicesSection(Request $request)
    {
        $validated = $request->validate([
            'services_subtitle' => ['nullable', 'string', 'max:255'],
            'services_title' => ['nullable', 'string', 'max:255'],
            'services_description' => ['nullable', 'string'],
            'services_footer_text' => ['nullable', 'string', 'max:255'],
            'services_footer_phone' => ['nullable', 'string', 'max:255'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $homeSetting->services_data = $validated;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Services section updated successfully.');
    }

    public function storeWhatWeDoSection(Request $request)
    {
        $validated = $request->validate([
            'whatwedo_title' => ['nullable', 'string', 'max:255'],
            'whatwedo_subtitle' => ['nullable', 'string', 'max:255'],
            'whatwedo_features' => ['nullable', 'array'],
            'whatwedo_features.*.icon' => ['nullable', 'string', 'max:255'],
            'whatwedo_features.*.title' => ['nullable', 'string', 'max:255'],
            'whatwedo_features.*.desc' => ['nullable', 'string'],
            'whatwedo_image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'whatwedo_image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $whatWeDoData = $homeSetting->whatwedo_data ?? [];

        if ($request->hasFile('whatwedo_image1')) {
            if (!empty($whatWeDoData['whatwedo_image1']) && Storage::disk('public')->exists($whatWeDoData['whatwedo_image1'])) {
                Storage::disk('public')->delete($whatWeDoData['whatwedo_image1']);
            }

            $whatWeDoData['whatwedo_image1'] = $request->file('whatwedo_image1')->store('site-settings/whatwedo', 'public');
        }

        if ($request->hasFile('whatwedo_image2')) {
            if (!empty($whatWeDoData['whatwedo_image2']) && Storage::disk('public')->exists($whatWeDoData['whatwedo_image2'])) {
                Storage::disk('public')->delete($whatWeDoData['whatwedo_image2']);
            }

            $whatWeDoData['whatwedo_image2'] = $request->file('whatwedo_image2')->store('site-settings/whatwedo', 'public');
        }

        $whatWeDoData['whatwedo_title'] = $validated['whatwedo_title'] ?? null;
        $whatWeDoData['whatwedo_subtitle'] = $validated['whatwedo_subtitle'] ?? null;
        $whatWeDoData['features'] = collect($request->input('whatwedo_features', []))
            ->map(function ($feature) {
                return [
                    'icon' => isset($feature['icon']) ? trim((string) $feature['icon']) : null,
                    'title' => isset($feature['title']) ? trim((string) $feature['title']) : null,
                    'desc' => isset($feature['desc']) ? trim((string) $feature['desc']) : null,
                ];
            })
            ->filter(function ($feature) {
                return filled($feature['icon']) || filled($feature['title']) || filled($feature['desc']);
            })
            ->values()
            ->all();

        $homeSetting->whatwedo_data = $whatWeDoData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'What we do section updated successfully.');
    }

    public function storeCausesSection(Request $request)
    {
        $validated = $request->validate([
            'causes_subtitle' => ['nullable', 'string', 'max:255'],
            'causes_title' => ['nullable', 'string', 'max:255'],
            'causes_description' => ['nullable', 'string'],
            'causes' => ['nullable', 'array'],
            'causes.*.title' => ['nullable', 'string', 'max:255'],
            'causes.*.category' => ['nullable', 'string', 'max:255'],
            'causes.*.desc' => ['nullable', 'string'],
            'causes.*.goal' => ['nullable', 'numeric', 'min:0'],
            'causes.*.raised' => ['nullable', 'numeric', 'min:0'],
            'causes.*.link' => ['nullable', 'string', 'max:255'],
            'causes.*.existing_image' => ['nullable', 'string', 'max:255'],
            'causes.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $causesData = $homeSetting->causes_data ?? [];

        $existingItems = $causesData['items'] ?? [];

        if (empty($existingItems)) {
            $existingItems = [
                [
                    'title' => $causesData['cause1_title'] ?? null,
                    'category' => $causesData['cause1_category'] ?? null,
                    'desc' => $causesData['cause1_desc'] ?? null,
                    'goal' => $causesData['cause1_goal'] ?? null,
                    'raised' => $causesData['cause1_raised'] ?? null,
                    'link' => $causesData['cause1_link'] ?? null,
                    'image' => $causesData['cause1_image'] ?? null,
                ],
                [
                    'title' => $causesData['cause2_title'] ?? null,
                    'category' => $causesData['cause2_category'] ?? null,
                    'desc' => $causesData['cause2_desc'] ?? null,
                    'goal' => $causesData['cause2_goal'] ?? null,
                    'raised' => $causesData['cause2_raised'] ?? null,
                    'link' => $causesData['cause2_link'] ?? null,
                    'image' => $causesData['cause2_image'] ?? null,
                ],
                [
                    'title' => $causesData['cause3_title'] ?? null,
                    'category' => $causesData['cause3_category'] ?? null,
                    'desc' => $causesData['cause3_desc'] ?? null,
                    'goal' => $causesData['cause3_goal'] ?? null,
                    'raised' => $causesData['cause3_raised'] ?? null,
                    'link' => $causesData['cause3_link'] ?? null,
                    'image' => $causesData['cause3_image'] ?? null,
                ],
            ];
        }

        $oldImages = collect($existingItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $newItems = [];
        $inputCauses = $request->input('causes', []);

        foreach ($inputCauses as $index => $causeItem) {
            $existingImage = isset($causeItem['existing_image']) ? trim((string) $causeItem['existing_image']) : null;
            $existingImage = $existingImage !== '' ? $existingImage : null;

            if ($request->hasFile("causes.$index.image")) {
                if (!empty($existingImage) && Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = $request->file("causes.$index.image")->store('site-settings/causes', 'public');
            }

            $item = [
                'title' => isset($causeItem['title']) ? trim((string) $causeItem['title']) : null,
                'category' => isset($causeItem['category']) ? trim((string) $causeItem['category']) : null,
                'desc' => isset($causeItem['desc']) ? trim((string) $causeItem['desc']) : null,
                'goal' => $causeItem['goal'] ?? null,
                'raised' => $causeItem['raised'] ?? null,
                'link' => isset($causeItem['link']) ? trim((string) $causeItem['link']) : null,
                'image' => $existingImage,
            ];

            if (filled($item['title']) || filled($item['category']) || filled($item['desc']) || filled($item['goal']) || filled($item['raised']) || filled($item['link']) || filled($item['image'])) {
                $newItems[] = $item;
            }
        }

        $newImages = collect($newItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $causesData['causes_subtitle'] = $validated['causes_subtitle'] ?? null;
        $causesData['causes_title'] = $validated['causes_title'] ?? null;
        $causesData['causes_description'] = $validated['causes_description'] ?? null;
        $causesData['items'] = array_values($newItems);

        $homeSetting->causes_data = $causesData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Our causes section updated successfully.');
    }

    public function storeWhyChooseSection(Request $request)
    {
        $validated = $request->validate([
            'whychoose_subtitle' => ['nullable', 'string', 'max:255'],
            'whychoose_title' => ['nullable', 'string', 'max:255'],
            'whychoose_description' => ['nullable', 'string'],
            'whychoose_points' => ['nullable', 'array'],
            'whychoose_points.*' => ['nullable', 'string', 'max:255'],
            'whychoose_counter1_number' => ['nullable', 'string', 'max:50'],
            'whychoose_counter1_label' => ['nullable', 'string', 'max:255'],
            'whychoose_counter2_number' => ['nullable', 'string', 'max:50'],
            'whychoose_counter2_label' => ['nullable', 'string', 'max:255'],
            'whychoose_counter3_number' => ['nullable', 'string', 'max:50'],
            'whychoose_counter3_label' => ['nullable', 'string', 'max:255'],
            'whychoose_image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'whychoose_image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $whyChooseData = $homeSetting->whychoose_data ?? [];

        if ($request->hasFile('whychoose_image1')) {
            if (!empty($whyChooseData['whychoose_image1']) && Storage::disk('public')->exists($whyChooseData['whychoose_image1'])) {
                Storage::disk('public')->delete($whyChooseData['whychoose_image1']);
            }

            $whyChooseData['whychoose_image1'] = $request->file('whychoose_image1')->store('site-settings/whychoose', 'public');
        }

        if ($request->hasFile('whychoose_image2')) {
            if (!empty($whyChooseData['whychoose_image2']) && Storage::disk('public')->exists($whyChooseData['whychoose_image2'])) {
                Storage::disk('public')->delete($whyChooseData['whychoose_image2']);
            }

            $whyChooseData['whychoose_image2'] = $request->file('whychoose_image2')->store('site-settings/whychoose', 'public');
        }

        $whyChooseData['whychoose_subtitle'] = $validated['whychoose_subtitle'] ?? null;
        $whyChooseData['whychoose_title'] = $validated['whychoose_title'] ?? null;
        $whyChooseData['whychoose_description'] = $validated['whychoose_description'] ?? null;
        $whyChooseData['whychoose_points'] = collect($request->input('whychoose_points', []))
            ->map(fn ($item) => is_string($item) ? trim($item) : $item)
            ->filter(fn ($item) => filled($item))
            ->values()
            ->all();

        $whyChooseData['whychoose_counter1_number'] = $validated['whychoose_counter1_number'] ?? null;
        $whyChooseData['whychoose_counter1_label'] = $validated['whychoose_counter1_label'] ?? null;
        $whyChooseData['whychoose_counter2_number'] = $validated['whychoose_counter2_number'] ?? null;
        $whyChooseData['whychoose_counter2_label'] = $validated['whychoose_counter2_label'] ?? null;
        $whyChooseData['whychoose_counter3_number'] = $validated['whychoose_counter3_number'] ?? null;
        $whyChooseData['whychoose_counter3_label'] = $validated['whychoose_counter3_label'] ?? null;

        $homeSetting->whychoose_data = $whyChooseData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Why choose us section updated successfully.');
    }

    public function storeHowItWorkSection(Request $request)
    {
        $validated = $request->validate([
            'howitwork_subtitle' => ['nullable', 'string', 'max:255'],
            'howitwork_title' => ['nullable', 'string', 'max:255'],
            'howitwork_description' => ['nullable', 'string'],
            'howitwork_button_text' => ['nullable', 'string', 'max:255'],
            'howitwork_button_link' => ['nullable', 'string', 'max:255'],
            'howitwork_steps' => ['nullable', 'array'],
            'howitwork_steps.*.title' => ['nullable', 'string', 'max:255'],
            'howitwork_steps.*.desc' => ['nullable', 'string'],
            'howitwork_steps.*.icon_svg' => ['nullable', 'string'],
            'howitwork_steps.*.existing_image' => ['nullable', 'string', 'max:255'],
            'howitwork_steps.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $howItWorkData = $homeSetting->howitwork_data ?? [];
        $existingSteps = collect($howItWorkData['steps'] ?? [])->values()->all();
        $oldImages = collect($existingSteps)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();
        $newSteps = [];

        foreach ((array) $request->input('howitwork_steps', []) as $index => $step) {
            $existingImage = isset($step['existing_image']) ? trim((string) $step['existing_image']) : null;
            $existingImage = $existingImage !== '' ? $existingImage : null;

            if ($request->hasFile("howitwork_steps.$index.image")) {
                if (!empty($existingImage) && Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = $request->file("howitwork_steps.$index.image")->store('site-settings/howitwork', 'public');
            }

            $item = [
                'title' => isset($step['title']) ? trim((string) $step['title']) : null,
                'desc' => isset($step['desc']) ? trim((string) $step['desc']) : null,
                'icon_svg' => isset($step['icon_svg']) ? trim((string) $step['icon_svg']) : null,
                'image' => $existingImage,
            ];

            if (filled($item['title']) || filled($item['desc']) || filled($item['icon_svg']) || filled($item['image'])) {
                $newSteps[] = $item;
            }
        }

        $newImages = collect($newSteps)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $howItWorkData['howitwork_subtitle'] = $validated['howitwork_subtitle'] ?? null;
        $howItWorkData['howitwork_title'] = $validated['howitwork_title'] ?? null;
        $howItWorkData['howitwork_description'] = $validated['howitwork_description'] ?? null;
        $howItWorkData['howitwork_button_text'] = $validated['howitwork_button_text'] ?? null;
        $howItWorkData['howitwork_button_link'] = $validated['howitwork_button_link'] ?? null;
        $howItWorkData['steps'] = array_values($newSteps);

        $homeSetting->howitwork_data = $howItWorkData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'How it work section updated successfully.');
    }

    public function storeTestimonialsSection(Request $request)
    {
        $validated = $request->validate([
            'testimonials_subtitle' => ['nullable', 'string', 'max:255'],
            'testimonials_title' => ['nullable', 'string', 'max:255'],
            'testimonials_description' => ['nullable', 'string'],
                        'testimonials_main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'testimonials' => ['nullable', 'array'],
            'testimonials.*.name' => ['nullable', 'string', 'max:255'],
            'testimonials.*.designation' => ['nullable', 'string', 'max:255'],
            'testimonials.*.quote' => ['nullable', 'string'],
            'testimonials.*.existing_image' => ['nullable', 'string', 'max:255'],
            'testimonials.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $testimonialsData = $homeSetting->testimonials_data ?? [];
        $existingItems = collect($testimonialsData['items'] ?? [])->values()->all();
        $oldImages = collect($existingItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();
        $newItems = [];

        foreach ((array) $request->input('testimonials', []) as $index => $testimonial) {
            $existingImage = isset($testimonial['existing_image']) ? trim((string) $testimonial['existing_image']) : null;
            $existingImage = $existingImage !== '' ? $existingImage : null;

            if ($request->hasFile("testimonials.$index.image")) {
                if (!empty($existingImage) && Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = $request->file("testimonials.$index.image")->store('site-settings/testimonials', 'public');
            }

            $item = [
                'name' => isset($testimonial['name']) ? trim((string) $testimonial['name']) : null,
                'designation' => isset($testimonial['designation']) ? trim((string) $testimonial['designation']) : null,
                'quote' => isset($testimonial['quote']) ? trim((string) $testimonial['quote']) : null,
                'image' => $existingImage,
            ];

            if (filled($item['name']) || filled($item['designation']) || filled($item['quote']) || filled($item['image'])) {
                $newItems[] = $item;
            }
        }

        $newImages = collect($newItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        
        if ($request->hasFile('testimonials_main_image')) {
            if (!empty($testimonialsData['testimonials_main_image']) && Storage::disk('public')->exists($testimonialsData['testimonials_main_image'])) {
                Storage::disk('public')->delete($testimonialsData['testimonials_main_image']);
            }

            $testimonialsData['testimonials_main_image'] = $request->file('testimonials_main_image')->store('site-settings/testimonials', 'public');
        }

        $testimonialsData['testimonials_subtitle'] = $validated['testimonials_subtitle'] ?? null;
        $testimonialsData['testimonials_title'] = $validated['testimonials_title'] ?? null;
        $testimonialsData['testimonials_description'] = $validated['testimonials_description'] ?? null;
        $testimonialsData['items'] = array_values($newItems);

        $homeSetting->testimonials_data = $testimonialsData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Testimonials section updated successfully.');
    }

    public function storeGallerySection(Request $request)
    {
        $validated = $request->validate([
            'gallery_subtitle' => ['nullable', 'string', 'max:255'],
            'gallery_title' => ['nullable', 'string', 'max:255'],
            'gallery_description' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
            'gallery.*.title' => ['nullable', 'string', 'max:255'],
            'gallery.*.category' => ['nullable', 'string', 'max:255'],
            'gallery.*.existing_image' => ['nullable', 'string', 'max:255'],
            'gallery.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $galleryData = $homeSetting->gallery_data ?? [];
        $existingItems = collect($galleryData['items'] ?? [])->values()->all();
        $oldImages = collect($existingItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();
        $newItems = [];

        foreach ((array) $request->input('gallery', []) as $index => $galleryItem) {
            $existingImage = isset($galleryItem['existing_image']) ? trim((string) $galleryItem['existing_image']) : null;
            $existingImage = $existingImage !== '' ? $existingImage : null;

            if ($request->hasFile("gallery.$index.image")) {
                if (!empty($existingImage) && Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = $request->file("gallery.$index.image")->store('site-settings/gallery', 'public');
            }

            $item = [
                'title' => isset($galleryItem['title']) ? trim((string) $galleryItem['title']) : null,
                'category' => isset($galleryItem['category']) ? trim((string) $galleryItem['category']) : null,
                'image' => $existingImage,
            ];

            if (filled($item['title']) || filled($item['category']) || filled($item['image'])) {
                $newItems[] = $item;
            }
        }

        $newImages = collect($newItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $galleryData['gallery_subtitle'] = $validated['gallery_subtitle'] ?? null;
        $galleryData['gallery_title'] = $validated['gallery_title'] ?? null;
        $galleryData['gallery_description'] = $validated['gallery_description'] ?? null;
        $galleryData['items'] = array_values($newItems);

        $homeSetting->gallery_data = $galleryData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Gallery section updated successfully.');
    }

    public function storeLastHopeSection(Request $request)
    {
        $validated = $request->validate([
            'lasthope_subtitle' => ['nullable', 'string', 'max:255'],
            'lasthope_title' => ['nullable', 'string', 'max:255'],
            'lasthope_description' => ['nullable', 'string'],
            'lasthope_button_text' => ['nullable', 'string', 'max:255'],
            'lasthope_button_link' => ['nullable', 'string', 'max:255'],
            'lasthope_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'lasthope_items' => ['nullable', 'array'],
            'lasthope_items.*.title' => ['nullable', 'string', 'max:255'],
            'lasthope_items.*.description' => ['nullable', 'string'],
            'lasthope_items.*.existing_image' => ['nullable', 'string', 'max:255'],
            'lasthope_items.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $lastHopeData = $homeSetting->lasthope_data ?? [];
        $existingItems = collect($lastHopeData['items'] ?? [])->values()->all();
        $oldImages = collect($existingItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();
        $newItems = [];

        if ($request->hasFile('lasthope_image')) {
            if (!empty($lastHopeData['lasthope_image']) && Storage::disk('public')->exists($lastHopeData['lasthope_image'])) {
                Storage::disk('public')->delete($lastHopeData['lasthope_image']);
            }

            $lastHopeData['lasthope_image'] = $request->file('lasthope_image')->store('site-settings/lasthope', 'public');
        }

        foreach ((array) $request->input('lasthope_items', []) as $index => $hopeItem) {
            $existingImage = isset($hopeItem['existing_image']) ? trim((string) $hopeItem['existing_image']) : null;
            $existingImage = $existingImage !== '' ? $existingImage : null;

            if ($request->hasFile("lasthope_items.$index.image")) {
                if (!empty($existingImage) && Storage::disk('public')->exists($existingImage)) {
                    Storage::disk('public')->delete($existingImage);
                }

                $existingImage = $request->file("lasthope_items.$index.image")->store('site-settings/lasthope/items', 'public');
            }

            $item = [
                'title' => isset($hopeItem['title']) ? trim((string) $hopeItem['title']) : null,
                'description' => isset($hopeItem['description']) ? trim((string) $hopeItem['description']) : null,
                'image' => $existingImage,
            ];

            if (filled($item['title']) || filled($item['description']) || filled($item['image'])) {
                $newItems[] = $item;
            }
        }

        $newImages = collect($newItems)
            ->pluck('image')
            ->filter()
            ->values()
            ->all();

        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $lastHopeData['lasthope_subtitle'] = $validated['lasthope_subtitle'] ?? null;
        $lastHopeData['lasthope_title'] = $validated['lasthope_title'] ?? null;
        $lastHopeData['lasthope_description'] = $validated['lasthope_description'] ?? null;
        $lastHopeData['lasthope_button_text'] = $validated['lasthope_button_text'] ?? null;
        $lastHopeData['lasthope_button_link'] = $validated['lasthope_button_link'] ?? null;
        $lastHopeData['items'] = array_values($newItems);

        $homeSetting->lasthope_data = $lastHopeData;
        $homeSetting->save();

        return redirect()->route('admin.site.home')->with('success', 'Last hope section updated successfully.');
    }
}
