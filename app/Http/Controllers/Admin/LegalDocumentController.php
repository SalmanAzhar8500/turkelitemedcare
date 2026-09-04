<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LegalDocumentController extends Controller
{
    public function index(): View
    {
        LegalDocument::seedDefaults();

        return view('admin.legal.index', ['documents' => LegalDocument::query()->orderBy('sort_order')->orderBy('title')->get()]);
    }

    public function create(): View
    {
        return view('admin.legal.form', ['document' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $document = LegalDocument::query()->create($this->validated($request));

        return redirect()->route('admin.legal.edit', $document)->with('status', 'Legal document created successfully.');
    }

    public function edit(LegalDocument $document): View
    {
        return view('admin.legal.form', compact('document'));
    }

    public function update(Request $request, LegalDocument $document): RedirectResponse
    {
        $document->update($this->validated($request, $document));

        return back()->with('status', 'Legal document saved successfully.');
    }

    public function destroy(LegalDocument $document): RedirectResponse
    {
        $document->delete();

        return redirect()->route('admin.legal.index')->with('status', 'Legal document deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?LegalDocument $document = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('legal_documents', 'slug')->ignore($document?->id)],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'seo_robots' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow'])],
            'title_de' => ['nullable', 'string', 'max:255'],
            'summary_de' => ['nullable', 'string', 'max:500'],
            'content_de' => ['nullable', 'string'],
            'seo_title_de' => ['nullable', 'string', 'max:60'],
            'seo_description_de' => ['nullable', 'string', 'max:160'],
            'seo_keywords_de' => ['nullable', 'string', 'max:500'],
            'seo_robots_de' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow'])],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['translations'] = array_filter([
            'de' => array_filter([
                'title' => $data['title_de'] ?? null,
                'summary' => $data['summary_de'] ?? null,
                'content' => $data['content_de'] ?? null,
                'seo_title' => $data['seo_title_de'] ?? null,
                'seo_description' => $data['seo_description_de'] ?? null,
                'seo_keywords' => $data['seo_keywords_de'] ?? null,
                'seo_robots' => $data['seo_robots_de'] ?? null,
            ], static fn (mixed $value): bool => filled($value)),
        ], static fn (mixed $value): bool => filled($value));
        unset($data['title_de'], $data['summary_de'], $data['content_de']);

        return $data;
    }
}
