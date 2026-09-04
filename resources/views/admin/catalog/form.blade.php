<x-app-layout>
    @push('head')
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    @endpush
    <x-slot name="header">
        <div class="admin-toolbar">
            <div>
                <p class="auth-kicker">{{ $definition['plural'] }}</p>
                <h1>{{ $record ? 'Edit '.$definition['singular'] : 'New '.$definition['singular'] }}</h1>
                <p class="auth-help">Use clear public-facing names and keep drafts unpublished until they are ready.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.catalog.index', $type) }}">Back to {{ $definition['plural'] }}</a>
        </div>
    </x-slot>

    @if($errors->any())<div class="auth-alert error">{{ $errors->first() }}</div>@endif
    @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif

    <section class="admin-card">
        <form method="POST" action="{{ $record ? route('admin.catalog.update', [$type, $record]) : route('admin.catalog.store', $type) }}" class="auth-form" enctype="multipart/form-data">
            @csrf
            @if($record) @method('PUT') @endif
            @php($german = $record?->translation('de') ?? [])
            @php
                $defaultBulletPoints = match ($type) {
                    'patient-services' => [
                        'Planning around the confirmed treatment timetable',
                        'One coordination contact from arrival to return home',
                        'Support tailored to the patient journey',
                    ],
                    'patient-stories' => [
                        'Published patient journey',
                        'Research and planning example',
                        'Independent provider assessment required',
                    ],
                    default => [],
                };
            @endphp
            @php($storedBulletPoints = (array) ($record?->bullet_points ?? $defaultBulletPoints))
            @php($storedGermanBulletPoints = (array) data_get($german, 'bullet_points', []))
            @php($legacyGermanBulletPoints = $storedGermanBulletPoints === [] && count($storedBulletPoints) > count($defaultBulletPoints) ? array_slice($storedBulletPoints, count($defaultBulletPoints)) : [])
            @php($germanBulletPoints = old('bullet_points_de', $storedGermanBulletPoints ?: $legacyGermanBulletPoints))
            @php($bulletPoints = old('bullet_points', $legacyGermanBulletPoints !== [] ? array_slice($storedBulletPoints, 0, count($defaultBulletPoints)) : $storedBulletPoints))
            <p class="admin-overline">English content</p>
            <div class="admin-form-grid">
                <label class="auth-field"><span>Name</span><input class="auth-input" name="name" value="{{ old('name', $record?->name) }}" required></label>
                <label class="auth-field"><span>URL slug</span><input class="auth-input" name="slug" value="{{ old('slug', $record?->slug) }}" required></label>
                @if($type === 'procedures')
                    <label class="auth-field"><span>Treatment</span><select class="auth-select" name="specialty_id" required><option value="">Select treatment</option>@foreach($specialties as $specialty)<option value="{{ $specialty->id }}" @selected(old('specialty_id', $record?->specialty_id) == $specialty->id)>{{ $specialty->name }}</option>@endforeach</select></label>
                @endif
                @if($type === 'clinics')
                    <label class="auth-field"><span>Location</span><input class="auth-input" name="location" value="{{ old('location', $record?->location) }}"></label>
                    <label class="auth-field"><span>Website</span><input class="auth-input" name="website" value="{{ old('website', $record?->website) }}"></label>
                    <label class="auth-field"><span>Email</span><input class="auth-input" name="email" value="{{ old('email', $record?->email) }}"></label>
                    <label class="auth-field"><span>Phone</span><input class="auth-input" name="phone" value="{{ old('phone', $record?->phone) }}"></label>
                @endif
    
            @if($type === 'doctors')
                    <label class="auth-field"><span>Clinic</span><select class="auth-select" name="clinic_id"><option value="">No clinic assigned</option>@foreach($clinics as $clinic)<option value="{{ $clinic->id }}" @selected(old('clinic_id', $record?->clinic_id) == $clinic->id)>{{ $clinic->name }}</option>@endforeach</select></label>
                    <label class="auth-field"><span>Professional title</span><input class="auth-input" name="designation" value="{{ old('designation', $record?->designation) }}" placeholder="For example, Consultant Surgeon"></label>
                    <label class="auth-field"><span>Profile highlight</span><input class="auth-input" name="profile_highlight" value="{{ old('profile_highlight', $record?->profile_highlight) }}" placeholder="For example, Rhinoplasty, breast and body contouring"></label>
                    <label class="auth-field"><span>Years of clinical experience</span><input class="auth-input" name="experience_years" type="number" min="0" max="100" value="{{ old('experience_years', $record?->experience_years) }}" placeholder="For example, 16"></label>
                    <label class="auth-field"><span>Languages</span><input class="auth-input" name="languages" value="{{ old('languages', $record?->languages) }}" placeholder="For example, Turkish, English, German"></label>
                    <label class="auth-field"><span>Consultation or review method</span><input class="auth-input" name="consultation_method" value="{{ old('consultation_method', $record?->consultation_method) }}" placeholder="For example, Video review before travel"></label>
                    <label class="auth-field"><span>Typical response time</span><input class="auth-input" name="response_time" value="{{ old('response_time', $record?->response_time) }}" placeholder="For example, 1-2 working days"></label>
                    @php($selectedTreatmentIds = array_map('strval', old('treatment_ids', $record?->treatments->modelKeys() ?? [])))
                    @php($selectedProcedureIds = array_map('strval', old('procedure_ids', $record?->procedures->modelKeys() ?? [])))
                    <div class="auth-field doctor-selection-field"><div class="doctor-selection-label"><span>Treatments <em>Required</em></span><b data-selection-count="treatments">0 selected</b></div><div class="doctor-selection-panel" role="group" aria-label="Treatments">@foreach($specialties as $specialty)<label class="doctor-choice"><input class="doctor-choice-input doctor-treatment-option" name="treatment_ids[]" type="checkbox" value="{{ $specialty->id }}" @checked(in_array((string) $specialty->id, $selectedTreatmentIds, true))><span class="doctor-choice-mark" aria-hidden="true"></span><span class="doctor-choice-copy"><strong>{{ $specialty->name }}</strong><small>Treatment pathway</small></span></label>@endforeach</div><small>Select at least one treatment. You can select more than one.</small></div>
                    <div class="auth-field doctor-selection-field" data-doctor-procedure-field><div class="doctor-selection-label"><span>Procedures <em>Optional</em></span><b data-selection-count="procedures">0 selected</b></div><p class="doctor-procedure-empty" data-doctor-procedure-empty>Select one or more treatments above to show their available procedures.</p><div class="doctor-selection-panel doctor-procedure-panel" role="group" aria-label="Procedures">@foreach($procedures as $procedure)<label class="doctor-choice doctor-procedure-choice" data-treatment-id="{{ $procedure->specialty_id }}"><input class="doctor-choice-input doctor-procedure-option" name="procedure_ids[]" type="checkbox" value="{{ $procedure->id }}" @checked(in_array((string) $procedure->id, $selectedProcedureIds, true))><span class="doctor-choice-mark" aria-hidden="true"></span><span class="doctor-choice-copy"><strong>{{ $procedure->name }}</strong><small>{{ $procedure->specialty?->name }}</small></span></label>@endforeach</div><small>Only procedures belonging to the selected treatments can be chosen.</small></div>
                @endif
                <label class="auth-field"><span>Sort order</span><input class="auth-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $record?->sort_order ?? 0) }}"></label>
            </div>

            @if($type === 'doctors')
                <label class="auth-field"><span>What patients should verify</span><textarea class="auth-textarea compact js-rich-text" name="verification_notes" placeholder="For example, specialist registration, procedure-specific experience, and follow-up arrangements.">{{ old('verification_notes', $record?->verification_notes) }}</textarea><small>This appears in the public doctor profile below the selected procedures.</small></label>
            @endif
            <div class="auth-field">
                <span>Cover image</span>
                <p class="auth-help">Upload a JPG, PNG, or WebP image (4 MB maximum). This image is shown on the public website.</p>
                <input id="catalog-image" name="image" type="file" accept="image/png,image/jpeg,image/webp">
                @if($record?->image_path)
                
                    <div class="logo-preview catalog-image-preview">
                        <span>Current cover image</span>
                        <img src="{{ public_media_url($record->image_path) }}" alt="Current image for {{ $record->name }}">
                    </div>
                @endif
            </div>
            <label class="auth-field"><span>Summary</span><textarea class="auth-textarea compact js-rich-text" name="summary">{{ old('summary', $record?->summary) }}</textarea></label>
            @php($usesContent = in_array($type, ['procedures', 'patient-services', 'patient-stories', 'doctors']))
            @if($type !== 'treatments')
                <label class="auth-field"><span>{{ $usesContent ? ($type === 'doctors' ? 'Biography' : 'Full content') : 'Description' }}</span><textarea class="auth-textarea js-rich-text" name="{{ $usesContent ? 'content' : 'description' }}">{{ old($usesContent ? 'content' : 'description', $usesContent ? $record?->content : $record?->description) }}</textarea></label>
            @endif
            @if(in_array($type, ['patient-services', 'patient-stories'], true))
                <div class="auth-field bullet-points-editor" data-bullet-points-editor>
                    <span>English bullet points</span>
                    <small>Add one point per input. Remove points with the X button.</small>
                    <div data-bullet-points-list>
                        @foreach((array) $bulletPoints as $point)
                            <div class="bullet-point-row">
                                <input class="auth-input" name="bullet_points[]" value="{{ $point }}" maxlength="255">
                                <button class="auth-button danger" type="button" data-remove-bullet-point aria-label="Remove bullet point">X</button>
                            </div>
                        @endforeach
                    </div>
                    <button class="auth-button secondary" type="button" data-add-bullet-point>Add point</button>
                </div>
            @endif
            @if($type === 'patient-services')
                <label class="auth-field"><span>Primary action label (English)</span><input class="auth-input" name="action_label" maxlength="255" value="{{ old('action_label', data_get($record?->translations, 'en.action_label', '')) }}" placeholder="Build my treatment plan"></label>
            @endif
            <p class="admin-overline">German</p>
            <p class="auth-help">German content is displayed when visitors select German. Leave a field empty only when the English fallback is acceptable.</p>
            <div class="admin-form-grid">
                <label class="auth-field" style="grid-column: 1 / -1;">
                    <span>Name (German)</span>
                        <input class="auth-input" name="name_de" value="{{ old('name_de', $german['name'] ?? '') }}">
                </label>
            </div>
            <div class="admin-form-grid">
                <label class="auth-field" style="grid-column: 1 / -1;">
                    <span>Summary (German)</span>
                    <textarea class="auth-textarea compact js-rich-text" name="summary_de">{{ old('summary_de', $german['summary'] ?? '') }}</textarea>
                </label>
            </div>
            @if(in_array($type, ['patient-services', 'patient-stories'], true))
                <div class="auth-field bullet-points-editor" data-bullet-points-editor>
                    <span>German bullet points</span>
                    <small>Optional. Add one German point per input.</small>
                    <div data-bullet-points-list>
                        @foreach((array) $germanBulletPoints as $point)
                            <div class="bullet-point-row">
                                <input class="auth-input" name="bullet_points_de[]" value="{{ $point }}" maxlength="255">
                                <button class="auth-button danger" type="button" data-remove-bullet-point aria-label="Remove German bullet point">X</button>
                            </div>
                        @endforeach
                    </div>
                    <button class="auth-button secondary" type="button" data-add-bullet-point>Add point</button>
                </div>
            @endif
            @if($type !== 'treatments')
                <label class="auth-field"><span>{{ $usesContent ? ($type === 'doctors' ? 'Biography (German)' : 'Full content (German)') : 'Description (German)' }}</span><textarea class="auth-textarea js-rich-text" name="{{ $usesContent ? 'content_de' : 'description_de' }}">{{ old($usesContent ? 'content_de' : 'description_de', $german[$usesContent ? 'content' : 'description'] ?? '') }}</textarea></label>
            @endif
            @if($type === 'patient-services')
                <label class="auth-field"><span>Primary action label (German)</span><input class="auth-input" name="action_label_de" maxlength="255" value="{{ old('action_label_de', $german['action_label'] ?? '') }}"></label>
            @endif
            @include('admin.catalog._translation-fields')
            <p class="admin-overline">Search engine optimisation</p>
            <p class="auth-help">These fields control how this public page appears in Google and social sharing. Leave title or description empty to use the public name and summary automatically.</p>
            <div class="admin-form-grid">
                <label class="auth-field"><span>SEO title</span><input class="auth-input" name="seo_title" maxlength="60" value="{{ old('seo_title', $record?->seo_title) }}"><small>Recommended: 50-60 characters.</small></label>
                <label class="auth-field"><span>SEO keywords</span><input class="auth-input" name="seo_keywords" maxlength="500" value="{{ old('seo_keywords', $record?->seo_keywords) }}" placeholder="dental implants, treatment in Turkey"><small>Separate keywords with commas.</small></label>
                <label class="auth-field"><span>Search visibility</span><select class="auth-select" name="seo_robots"><option value="index,follow" @selected(old('seo_robots', $record?->seo_robots ?? 'index,follow') === 'index,follow')>Index and follow links</option><option value="noindex,nofollow" @selected(old('seo_robots', $record?->seo_robots) === 'noindex,nofollow')>Do not index</option></select></label>
            </div>
            <label class="auth-field"><span>Meta description</span><textarea class="auth-textarea compact" name="seo_description" maxlength="160">{{ old('seo_description', $record?->seo_description) }}</textarea><small>Recommended: 120-160 characters.</small></label>
            <p class="admin-overline">German SEO</p>
            <p class="auth-help">Add German search text for visitors using the German website.</p>
            <div class="admin-form-grid">
                <label class="auth-field"><span>SEO title (German)</span><input class="auth-input" name="seo_title_de" maxlength="60" value="{{ old('seo_title_de', $german['seo_title'] ?? '') }}"><small>Recommended: 50-60 characters.</small></label>
                <label class="auth-field"><span>SEO keywords (German)</span><input class="auth-input" name="seo_keywords_de" maxlength="500" value="{{ old('seo_keywords_de', $german['seo_keywords'] ?? '') }}" placeholder="Behandlung Türkei, Klinik"><small>Separate keywords with commas.</small></label>
                <label class="auth-field"><span>Search visibility (German)</span><select class="auth-select" name="seo_robots_de"><option value="index,follow" @selected(old('seo_robots_de', $german['seo_robots'] ?? $record?->seo_robots ?? 'index,follow') === 'index,follow')>Index and follow links</option><option value="noindex,nofollow" @selected(old('seo_robots_de', $german['seo_robots'] ?? '') === 'noindex,nofollow')>Do not index</option></select></label>
            </div>
            <label class="auth-field"><span>Meta description (German)</span><textarea class="auth-textarea compact" name="seo_description_de" maxlength="160">{{ old('seo_description_de', $german['seo_description'] ?? '') }}</textarea><small>Recommended: 120-160 characters.</small></label>            <label class="auth-check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $record?->is_active ?? true))> Publish on the public site</label>
            <div class="admin-toolbar"><button class="auth-button" type="submit">Save {{ $definition['singular'] }}</button>@if($record)<button class="auth-button danger" type="submit" form="delete-record">Delete</button>@endif</div>
        </form>
        @if($record)<form id="delete-record" method="POST" action="{{ route('admin.catalog.destroy', [$type, $record]) }}">@csrf @method('DELETE')</form>@endif
    </section>
    @include('admin.partials.rich-text-editor')
        @if(in_array($type, ['patient-services', 'patient-stories'], true))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('[data-bullet-points-editor]').forEach((editor) => {
                        const list = editor.querySelector('[data-bullet-points-list]');
                        const addButton = editor.querySelector('[data-add-bullet-point]');
                        const inputName = list.querySelector('input')?.name || 'bullet_points[]';
                        addButton.addEventListener('click', () => {
                            const row = document.createElement('div');
                            row.className = 'bullet-point-row';
                            row.innerHTML = `<input class="auth-input" name="${inputName}" maxlength="255"><button class="auth-button danger" type="button" data-remove-bullet-point aria-label="Remove bullet point">X</button>`;
                            list.appendChild(row);
                            row.querySelector('input').focus();
                        });
                        list.addEventListener('click', (event) => {
                            if (event.target.matches('[data-remove-bullet-point]')) event.target.closest('.bullet-point-row').remove();
                        });
                    });
                });
            </script>
        @endif
    @push('scripts')
        @if($type === 'doctors')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const treatments = Array.from(document.querySelectorAll('.doctor-treatment-option'));
                    const procedures = Array.from(document.querySelectorAll('.doctor-procedure-option'));
                    const treatmentCount = document.querySelector('[data-selection-count="treatments"]');
                    const procedureCount = document.querySelector('[data-selection-count="procedures"]');
                    const procedureField = document.querySelector('[data-doctor-procedure-field]');
                    const procedureEmpty = document.querySelector('[data-doctor-procedure-empty]');

                    if (!treatments.length) return;

                    const filterProcedures = () => {
                        const selected = new Set(treatments.filter((input) => input.checked).map((input) => input.value));

                        let visibleProcedures = 0;
                        procedures.forEach((input) => {
                            const choice = input.closest('.doctor-procedure-choice');
                            const available = selected.has(choice.dataset.treatmentId);
                            choice.hidden = !available;
                            input.disabled = !available;
                            if (!available) input.checked = false;
                            if (available) visibleProcedures += 1;
                        });

                        treatmentCount.textContent = `${selected.size} selected`;
                        procedureCount.textContent = `${procedures.filter((input) => input.checked).length} selected`;
                        procedureField.classList.toggle('is-ready', selected.size > 0);
                        procedureEmpty.hidden = selected.size > 0;
                        if (selected.size > 0 && visibleProcedures === 0) {
                            procedureEmpty.hidden = false;
                            procedureEmpty.textContent = 'No published procedures are available for the selected treatment yet.';
                        } else {
                            procedureEmpty.textContent = 'Select one or more treatments above to show their available procedures.';
                        }
                    };

                    treatments.forEach((input) => input.addEventListener('change', filterProcedures));
                    procedures.forEach((input) => input.addEventListener('change', filterProcedures));
                    filterProcedures();
                });
            </script>
        @endif
    @endpush
</x-app-layout>


