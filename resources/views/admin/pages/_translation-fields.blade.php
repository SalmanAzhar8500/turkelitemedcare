@php($translation = $page->translation($locale))
@php($localizedContent = old('content_'.$locale.'_fields', array_replace_recursive($page->content ?? [], $translation['content'] ?? [])))
<section class="admin-translation-panel" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
    <p class="admin-overline">{{ $label }}</p>
    <p class="auth-help">Enter the {{ $label }} title, description, page content and SEO values. Fields are intentionally empty so the admin can provide an approved translation.</p>
    <div class="auth-field"><label for="title_{{ $locale }}">Title ({{ $label }})</label><input id="title_{{ $locale }}" name="title_{{ $locale }}" class="auth-input" value="{{ old('title_'.$locale, $translation['title'] ?? '') }}"></div>
    <div class="auth-field"><label for="description_{{ $locale }}">Description ({{ $label }})</label><textarea id="description_{{ $locale }}" name="description_{{ $locale }}" class="auth-textarea js-rich-text" rows="4">{{ old('description_'.$locale, $translation['description'] ?? '') }}</textarea></div>
    <p class="admin-overline">SEO ({{ $label }})</p>
    <div class="admin-form-grid">
        <label class="auth-field"><span>SEO title ({{ $label }})</span><input class="auth-input" name="seo_title_{{ $locale }}" maxlength="60" value="{{ old('seo_title_'.$locale, $translation['seo_title'] ?? '') }}"></label>
        <label class="auth-field"><span>SEO keywords ({{ $label }})</span><input class="auth-input" name="seo_keywords_{{ $locale }}" maxlength="500" value="{{ old('seo_keywords_'.$locale, $translation['seo_keywords'] ?? '') }}"></label>
        <label class="auth-field"><span>Search visibility ({{ $label }})</span><select class="auth-select" name="seo_robots_{{ $locale }}"><option value="index,follow" @selected(old('seo_robots_'.$locale, $translation['seo_robots'] ?? 'index,follow') === 'index,follow')>Index and follow links</option><option value="noindex,nofollow" @selected(old('seo_robots_'.$locale, $translation['seo_robots'] ?? '') === 'noindex,nofollow')>Do not index</option></select></label>
    </div>
    <label class="auth-field"><span>Meta description ({{ $label }})</span><textarea class="auth-textarea compact" name="seo_description_{{ $locale }}" maxlength="160">{{ old('seo_description_'.$locale, $translation['seo_description'] ?? '') }}</textarea></label>
    <p class="admin-overline">Page content ({{ $label }})</p>
    @foreach($localizedContent as $section => $sectionContent)
        @if($section !== 'imported_static_content')
            @php($guide = $sectionGuides[$page->slug][$section] ?? [str()->headline((string) $section), 'Enter the translated text for this page section.'])
            <details class="admin-content-section"><summary>{{ $guide[0] }}</summary><p class="auth-help">{{ $guide[1] }}</p><x-admin.content-fields :name="'content_'.$locale.'_fields['.$section.']'" :label="$section" :value="$sectionContent" /></details>
        @endif
    @endforeach
</section>
