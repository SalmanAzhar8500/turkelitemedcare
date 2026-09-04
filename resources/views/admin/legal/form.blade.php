<x-app-layout>
    <x-slot name="header"><div class="admin-toolbar"><div><p class="auth-kicker">Legal &amp; Privacy</p><h1>{{ $document ? 'Edit document' : 'Create document' }}</h1><p class="auth-help">Write the document in plain language, then keep it in draft until legal review is complete.</p></div><a class="auth-button secondary" href="{{ route('admin.legal.index') }}">Back to documents</a></div></x-slot>
    @if($errors->any())<div class="auth-alert error">{{ $errors->first() }}</div>@endif
    @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif
    @php($german = $document?->translation('de') ?? [])
    <section class="admin-card"><form method="POST" action="{{ $document ? route('admin.legal.update', $document) : route('admin.legal.store') }}" class="auth-form">
        @csrf @if($document) @method('PUT') @endif
        <p class="admin-overline">English document</p><div class="admin-form-grid">
            <label class="auth-field"><span>Document title</span><input class="auth-input" name="title" value="{{ old('title', $document?->title) }}" required></label>
            <label class="auth-field"><span>URL slug</span><input class="auth-input" name="slug" value="{{ old('slug', $document?->slug) }}" placeholder="privacy-policy" required></label>
            <label class="auth-field"><span>Short summary</span><input class="auth-input" name="summary" value="{{ old('summary', $document?->summary) }}"></label>
            <label class="auth-field"><span>Display order</span><input class="auth-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $document?->sort_order ?? 0) }}"></label>
        </div>
        <label class="auth-field"><span>Document content</span><textarea class="auth-textarea js-rich-text" name="content" rows="18">{{ old('content', $document?->content) }}</textarea><small>Use headings, paragraphs and lists. Have qualified counsel review the final document before publishing.</small></label>
        <label class="auth-check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $document?->is_active ?? true))> Publish this document</label>
        <p class="admin-overline">German translation</p><div class="admin-form-grid">
            <label class="auth-field"><span>German title</span><input class="auth-input" name="title_de" value="{{ old('title_de', $german['title'] ?? '') }}"></label>
            <label class="auth-field"><span>German summary</span><input class="auth-input" name="summary_de" value="{{ old('summary_de', $german['summary'] ?? '') }}"></label>
        </div>
        <label class="auth-field"><span>German document content</span><textarea class="auth-textarea js-rich-text" name="content_de" rows="14">{{ old('content_de', $german['content'] ?? '') }}</textarea></label>
        <p class="admin-overline">German SEO settings</p><p class="auth-help">SEO text shown when this legal document is opened in German.</p><div class="admin-form-grid">
            <label class="auth-field"><span>German SEO title</span><input class="auth-input" name="seo_title_de" maxlength="60" value="{{ old('seo_title_de', $german['seo_title'] ?? '') }}"></label>
            <label class="auth-field"><span>German SEO description</span><input class="auth-input" name="seo_description_de" maxlength="160" value="{{ old('seo_description_de', $german['seo_description'] ?? '') }}"></label>
            <label class="auth-field"><span>German SEO keywords</span><input class="auth-input" name="seo_keywords_de" value="{{ old('seo_keywords_de', $german['seo_keywords'] ?? '') }}"></label>
            <label class="auth-field"><span>German search visibility</span><select class="auth-select" name="seo_robots_de"><option value="index,follow" @selected(old('seo_robots_de', $german['seo_robots'] ?? $document?->seo_robots ?? 'index,follow') === 'index,follow')>Index and follow</option><option value="noindex,nofollow" @selected(old('seo_robots_de', $german['seo_robots'] ?? '') === 'noindex,nofollow')>Do not index</option></select></label>
        </div>
        @include('admin.legal._translation-fields')
        <p class="admin-overline">Search engine settings</p><div class="admin-form-grid">
            <label class="auth-field"><span>SEO title</span><input class="auth-input" name="seo_title" maxlength="60" value="{{ old('seo_title', $document?->seo_title) }}"></label>
            <label class="auth-field"><span>SEO description</span><input class="auth-input" name="seo_description" maxlength="160" value="{{ old('seo_description', $document?->seo_description) }}"></label>
            <label class="auth-field"><span>SEO keywords</span><input class="auth-input" name="seo_keywords" value="{{ old('seo_keywords', $document?->seo_keywords) }}"></label>
            <label class="auth-field"><span>Search visibility</span><select class="auth-select" name="seo_robots"><option value="index,follow" @selected(old('seo_robots', $document?->seo_robots ?? 'index,follow') === 'index,follow')>Index and follow</option><option value="noindex,nofollow" @selected(old('seo_robots', $document?->seo_robots) === 'noindex,nofollow')>Do not index</option></select></label>
        </div><button class="auth-button" type="submit">Save document</button>
    </form></section>
    @include('admin.partials.rich-text-editor')
</x-app-layout>

