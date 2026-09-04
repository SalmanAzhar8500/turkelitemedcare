<x-app-layout>
    <x-slot name="header">
        <div class="admin-toolbar labels-hero-toolbar">
            <div>
                <p class="auth-kicker">Language control</p>
                <h1>Website labels</h1>
                <p class="auth-help">Manage the short public labels used across navigation, buttons and page breadcrumbs in English and German.</p>
            </div>
            <a class="auth-button secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </div>
    </x-slot>

    @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="auth-alert error">{{ $errors->first() }}</div>@endif

    @php
        $labels = [
            'home' => 'Home',
            'treatments' => 'Treatments',
            'clinics' => 'Clinics',
            'how_it_works' => 'How It Works',
            'patient_services' => 'Patient Services',
            'patient_stories' => 'Patient Stories',
            'about' => 'About',
            'for_clinics' => 'For Clinics',
            'contact' => 'Contact',
            'plan_journey' => 'Plan My Journey',            'browse_specialties' => 'Browse specialties',
            'view_all_specialties' => 'View all specialties',
            'view_all_doctors' => 'View all doctors',
            'explore_clinics' => 'Explore clinics',
            'see_how_it_works' => 'See how it works',
            'read_guide' => 'Read guide',
            'send_message' => 'Send us a message',
            'request_callback' => 'Request a callback',
            'start_enquiry' => 'Start a treatment enquiry',
            'show_all' => 'Show all',
            'aesthetic' => 'Aesthetic',
            'surgical' => 'Surgical',
            'vision' => 'Vision',
            'specialist' => 'Specialist',
        ];
        $defaults = [
            'home' => ['en' => 'Home', 'de' => 'Startseite'],
            'treatments' => ['en' => 'Treatments', 'de' => 'Behandlungen'],
            'clinics' => ['en' => 'Clinics', 'de' => 'Kliniken'],
            'how_it_works' => ['en' => 'How It Works', 'de' => 'So funktioniert es'],
            'patient_services' => ['en' => 'Patient Services', 'de' => 'Patientenservice'],
            'patient_stories' => ['en' => 'Patient Stories', 'de' => 'Patientengeschichten'],
            'about' => ['en' => 'About', 'de' => 'Über uns'],
            'for_clinics' => ['en' => 'For Clinics', 'de' => 'Für Kliniken'],
            'contact' => ['en' => 'Contact', 'de' => 'Kontakt'],
            'plan_journey' => ['en' => 'Plan My Journey', 'de' => 'Meine Reise planen'],            'browse_specialties' => ['en' => 'Browse specialties', 'de' => 'Fachgebiete ansehen'],
            'view_all_specialties' => ['en' => 'View all specialties', 'de' => 'Alle Fachgebiete ansehen'],
            'view_all_doctors' => ['en' => 'View all doctors', 'de' => 'Alle Ärzte ansehen'],
            'explore_clinics' => ['en' => 'Explore clinics', 'de' => 'Kliniken ansehen'],
            'see_how_it_works' => ['en' => 'See how it works', 'de' => 'So funktioniert es'],
            'read_guide' => ['en' => 'Read guide', 'de' => 'Ratgeber lesen'],
            'send_message' => ['en' => 'Send us a message', 'de' => 'Nachricht senden'],
            'request_callback' => ['en' => 'Request a callback', 'de' => 'Rückruf anfordern'],
            'start_enquiry' => ['en' => 'Start a treatment enquiry', 'de' => 'Behandlungsanfrage starten'],
            'show_all' => ['en' => 'Show all', 'de' => 'Alle anzeigen'],
            'aesthetic' => ['en' => 'Aesthetic', 'de' => 'Ästhetik'],
            'surgical' => ['en' => 'Surgical', 'de' => 'Chirurgie'],
            'vision' => ['en' => 'Vision', 'de' => 'Sehen'],
            'specialist' => ['en' => 'Specialist', 'de' => 'Fachbereich'],
        ];
    @endphp

    <form method="POST" action="{{ route('admin.labels.update') }}" class="admin-labels-form">
        @csrf
        @method('PUT')
        <section class="admin-card labels-card">
            <div class="labels-card-heading">
                <div>
                    <p class="admin-overline">Public copy</p>
                    <h3>Navigation and actions</h3>
                    <p class="auth-help">These values update automatically when visitors switch between English and German.</p>
                </div>
                <span class="labels-language-mark" aria-hidden="true">EN / DE</span>
            </div>
            <div class="labels-grid">
                @foreach($labels as $key => $label)
                    <div class="label-editor-row">
                        <div class="label-editor-name"><strong>{{ $label }}</strong><small>{{ $key }}</small></div>
                        <label class="auth-field"><span>English</span><input class="auth-input" name="{{ $key }}_label_en" value="{{ old($key.'_label_en', $settings[$key.'_label_en'] ?? $defaults[$key]['en']) }}" required></label>
                        <label class="auth-field"><span>German</span><input class="auth-input" name="{{ $key }}_label_de" value="{{ old($key.'_label_de', $settings[$key.'_label_de'] ?? $defaults[$key]['de']) }}" required></label>
                    </div>
                @endforeach
            </div>
        </section>
        <div class="labels-form-actions"><p class="auth-help">Use concise wording so labels remain clear on desktop and mobile.</p><button class="auth-button" type="submit">Save website labels</button></div>
    </form>
</x-app-layout>