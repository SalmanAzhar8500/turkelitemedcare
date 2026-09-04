<x-app-layout>
    @php
        $previewRoutes = ['home' => 'home', 'about' => 'about', 'contact' => 'contact', 'for-clinics' => 'for-clinics', 'how-it-works' => 'how-it-works', 'patient-services' => 'patient-services', 'treatments.index' => 'treatments.index', 'doctors.index' => 'doctors.index', 'clinics.index' => 'clinics.index', 'treatment-plan' => 'treatment-plan'];
        $previewUrl = isset($previewRoutes[$page->slug]) ? route($previewRoutes[$page->slug]) : route('home');
        $isHomePage = $page->slug === 'home';
        $sectionGuides = [
            'home' => [
                'hero' => ['Top banner', 'The main heading, introduction and two buttons visitors see first on the Home page.'],
                'welcome' => ['Welcome section', 'The welcome heading, four feature cards and quick treatment-plan form shown below the Home hero.'],
                'specialties' => ['Treatment library', 'Heading and introduction above the treatment specialties. Treatments themselves are managed in Admin > Treatments.'],
                'services' => ['Patient services introduction', 'Heading, introduction and links above the patient-services preview. Individual services are managed in Admin > Patient services.'],
                'testimonial' => ['Patient stories introduction', 'Heading and fallback quotation above published patient stories. Stories are managed in Admin > Patient stories.'],
                'specialist_teams' => ['Specialist teams (Doctors)', 'Heading and introduction above the doctor profiles. Doctors are managed separately in Admin > Doctors.'],
                'guides_videos' => ['Guides and articles', 'Heading and labels for the practical decision-article section. Guides are managed in Admin > Guides.'],
            ],
            'contact' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction at the top of the Contact page.'],
                'contact_info' => ['Contact details', 'The email, phone and language cards beside the enquiry form. Brand email and phone come from Admin > Website settings.'],
                'form' => ['Contact form introduction', 'Heading and privacy note shown with the main contact form.'],
                'callback' => ['Callback request', 'Heading, introduction and note shown in the callback section.'],
            ],
            'for-clinics' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction for clinic partners.'],
                'features' => ['Partner relationship checklist', 'Heading, checklist cards and contact button for prospective clinics.'],
            ],
            'how-it-works' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction at the top of the How It Works page.'],
                'summary' => ['Three clear roles', 'The heading and three short role statements shown in the top-right panel.'],
                'steps' => ['Patient journey steps', 'Edit the four steps visitors see. Each step has a number, title, description and responsible person.'],
                'decisions' => ['Before-you-book decisions', 'Edit the heading and the decision table shown near the bottom of the page.'],
            ],
            'patient-services' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction at the top of Patient Services.'],
                'services' => ['Services introduction', 'Heading and service list introduction. Individual service cards are managed in Admin > Patient services.'],
            ],
                                    'guides.index' => [
                'hero' => ['Top banner', 'The Guides label, heading, introduction and care-pathway card.'],
                'listing' => ['Guide library', 'Labels and empty-state text used around the guide cards. Individual guides are managed in Admin > Guides.'],
                'contact_routes' => ['Contact options', 'The three contact and treatment-enquiry cards below the guide library.'],
            ],'stories.index' => [
                'hero' => ['Top banner', 'The story-page label, heading, introduction and pathway card.'],
                'intro' => ['Story listing introduction', 'The heading, description and labels used above and on each story card.'],
                'empty' => ['Empty story state', 'Message shown when no patient stories are published.'],
            ],'treatments.index' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction on the Treatments page. Treatments and procedures are managed in their own admin sections.'],
            ],
            'treatments.show' => [
                'hero' => ['Treatment page fallback', 'Fallback heading and introduction used when a treatment page has no treatment-specific text.'],
            ],
            'about' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction at the top of the About page.'],
                'hero_card' => ['Top banner side card', 'The three lines shown in the visual card on the right of the top banner.'],
                'intro' => ['What we do', 'The two main explanation sections in the left column.'],
                'clinic_standards' => ['Clinic onboarding standards', 'The heading and four information cards below the introduction.'],
                'clinic_sidebar' => ['For clinics side panel', 'The right-hand panel and its button.'],
                'contact_routes' => ['Contact options', 'The three action cards: WhatsApp, callback and treatment request.'],
                'transparency' => ['Commercial transparency', 'The payment explanation and legal-link text at the bottom.'],
            ],
            'legal' => [
                'hero' => ['Top banner', 'The small label, main heading and introduction at the top of Legal & Privacy.'],
                'hero_card' => ['Top banner side card', 'The three lines shown in the visual card on the right of the top banner.'],
                'documents' => ['Legal documents', 'The heading, introduction and document links shown on the page.'],
                'notice' => ['Legal review notice', 'The warning shown above the legal document list.'],
                'role_clarity' => ['Role clarity', 'The paragraphs explaining the platform and independent providers.'],
                'sidebar' => ['Contact side panel', 'The help panel and contact button beside the legal content.'],
            ],
        ];
    @endphp
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">Content editor</p>
                <h1>{{ $page->title }}</h1>
                <p class="auth-help">Edit each page section with clear fields. The website structure is saved automatically.</p>
            </div>
            <div class="admin-toolbar">
                <a class="auth-button secondary" href="{{ route('admin.pages.index') }}">Back to pages</a>
                <a class="auth-button secondary" href="{{ $previewUrl }}" target="_blank" rel="noreferrer">Preview {{ $page->title }} page</a>
            </div>
        </div>
    </x-slot>

    <div class="admin-grid">
        <section class="admin-card span-12">
            @if(session('status'))
                <div class="auth-alert success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="auth-alert error">
                    <ul class="auth-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="auth-form">
                @csrf
                @method('PUT')

                <div class="auth-field">
                    <label for="title">Title (English)</label>
                    <input id="title" name="title" class="auth-input" value="{{ old('title', $page->title) }}" required>
                </div>

                <div class="auth-field">
                    <label for="description">Description (English)</label>
                    <textarea id="description" name="description" class="auth-textarea js-rich-text" rows="4">{{ old('description', $page->description) }}</textarea>
                </div>

                <input name="template" type="hidden" value="{{ old('template', $page->template) }}">

                <p class="admin-overline">Search engine optimisation</p>
                <p class="auth-help">Control the title and description used by search engines. Leave title or description empty to use the page title and description automatically.</p>
                <div class="admin-form-grid">
                    <label class="auth-field"><span>SEO title</span><input class="auth-input" name="seo_title" maxlength="60" value="{{ old('seo_title', $page->seo_title) }}"><small>Recommended: 50-60 characters.</small></label>
                    <label class="auth-field"><span>SEO keywords</span><input class="auth-input" name="seo_keywords" maxlength="500" value="{{ old('seo_keywords', $page->seo_keywords) }}" placeholder="medical treatment Turkey, patient support"><small>Separate keywords with commas.</small></label>
                    <label class="auth-field"><span>Search visibility</span><select class="auth-select" name="seo_robots"><option value="index,follow" @selected(old('seo_robots', $page->seo_robots ?? 'index,follow') === 'index,follow')>Index and follow links</option><option value="noindex,nofollow" @selected(old('seo_robots', $page->seo_robots) === 'noindex,nofollow')>Do not index</option></select></label>
                </div>
                <label class="auth-field"><span>Meta description</span><textarea class="auth-textarea compact" name="seo_description" maxlength="160">{{ old('seo_description', $page->seo_description) }}</textarea><small>Recommended: 120-160 characters.</small></label>

                @php($englishContent = old('content_fields', $page->content ?? []))
                <p class="admin-overline">Page content (English)</p>
                <p class="auth-help">{{ $isHomePage ? 'This is the single Home page editor. All homepage sections are grouped here so you can manage the full front page in one place.' : 'Open a section and edit its labels, headings and text. You do not need to edit code or JSON.' }}</p>
                @foreach($englishContent as $section => $sectionContent)
                    @php($guide = $sectionGuides[$page->slug][$section] ?? [str()->headline((string) $section), 'Edit the text shown in this page section.'])
                    @php($openSection = $page->slug !== 'how-it-works' || $section === 'hero')
                    @if($section === 'imported_static_content')
                        <details class="admin-content-section admin-legacy-section">
                            <summary>Imported legacy content</summary>
                            <p class="auth-help">This is archived content imported from the old HTML website. It is kept for reference and is not part of the current editable page layout.</p>
                            @if(filled(data_get($sectionContent, 'source_path')))
                                <p class="admin-meta"><strong>Source file:</strong> {{ data_get($sectionContent, 'source_path') }}</p>
                            @endif
                        </details>
                    @else
                        <details class="admin-content-section" @if($openSection) open @endif>
                            <summary>{{ $guide[0] }}</summary>
                            <p class="auth-help">{{ $guide[1] }}</p>
                            <x-admin.content-fields :name="'content_fields['.$section.']'" :label="$section" :value="$sectionContent" />
                        </details>
                    @endif
                @endforeach

                @php($german = $page->translation('de'))
                <p class="admin-overline">Deutsch</p>
                <p class="auth-help">Enter the German title, description and page text. The German frontend uses these values when available.</p>

                <div class="auth-field">
                    <label for="title_de">Title (German)</label>
                    <input id="title_de" name="title_de" class="auth-input" value="{{ old('title_de', $german['title'] ?? '') }}">
                </div>

                <div class="auth-field">
                    <label for="description_de">Description (German)</label>
                    <textarea id="description_de" name="description_de" class="auth-textarea js-rich-text" rows="4">{{ old('description_de', $german['description'] ?? '') }}</textarea>
                </div>

                <p class="admin-overline">SEO (German)</p>
                <p class="auth-help">Add German SEO values for Google results when visitors browse the German website.</p>
                <div class="admin-form-grid">
                    <label class="auth-field"><span>SEO title (German)</span><input class="auth-input" name="seo_title_de" maxlength="60" value="{{ old('seo_title_de', $german['seo_title'] ?? '') }}"><small>Recommended: 50-60 characters.</small></label>
                    <label class="auth-field"><span>SEO keywords (German)</span><input class="auth-input" name="seo_keywords_de" maxlength="500" value="{{ old('seo_keywords_de', $german['seo_keywords'] ?? '') }}"><small>Separate keywords with commas.</small></label>
                    <label class="auth-field"><span>Search visibility (German)</span><select class="auth-select" name="seo_robots_de"><option value="index,follow" @selected(old('seo_robots_de', $german['seo_robots'] ?? $page->seo_robots ?? 'index,follow') === 'index,follow')>Index and follow links</option><option value="noindex,nofollow" @selected(old('seo_robots_de', $german['seo_robots'] ?? '') === 'noindex,nofollow')>Do not index</option></select></label>
                </div>
                <label class="auth-field"><span>Meta description (German)</span><textarea class="auth-textarea compact" name="seo_description_de" maxlength="160">{{ old('seo_description_de', $german['seo_description'] ?? '') }}</textarea><small>Recommended: 120-160 characters.</small></label>                @php($germanContent = old('content_de_fields', array_replace_recursive($page->content ?? [], $german['content'] ?? [])))
                <p class="admin-overline">Page content (German)</p>
                <p class="auth-help">Translate the fields you want to show in German. The English version is used when a German value is empty.</p>
                @foreach($germanContent as $section => $sectionContent)
                    @php($guide = $sectionGuides[$page->slug][$section] ?? [str()->headline((string) $section), 'Translate the text shown in this page section.'])
                    @if($section === 'imported_static_content')
                        <details class="admin-content-section admin-legacy-section">
                            <summary>Imported German legacy content</summary>
                            <p class="auth-help">This archived HTML copy is kept for reference and is not editable here. Translate the named sections above instead.</p>
                            @if(filled(data_get($sectionContent, 'source_path')))
                                <p class="admin-meta"><strong>Source file:</strong> {{ data_get($sectionContent, 'source_path') }}</p>
                            @endif
                        </details>
                    @else
                        <details class="admin-content-section">
                            <summary>{{ $guide[0] }}</summary>
                            <p class="auth-help">{{ $guide[1] }}</p>
                            <x-admin.content-fields :name="'content_de_fields['.$section.']'" :label="$section" :value="$sectionContent" />
                        </details>
                    @endif
                @endforeach

                @include('admin.pages._translation-fields', ['locale' => 'tr', 'label' => 'Türkçe'])
                @include('admin.pages._translation-fields', ['locale' => 'ar', 'label' => 'العربية'])
                <div class="auth-row">
                    <button class="auth-button" type="submit">Save page</button>
                </div>
            </form>
        </section>
    </div>
    @include('admin.partials.rich-text-editor')
</x-app-layout>

