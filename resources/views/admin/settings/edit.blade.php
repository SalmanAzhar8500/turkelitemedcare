<x-app-layout>
    @push('head')
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    @endpush
    <x-slot name="header">
        <div class="admin-toolbar"><div><p class="auth-kicker">Configuration</p><h1>Website settings</h1><p class="auth-help">Manage the public identity, contact details and non-secret outgoing mail settings.</p></div><a class="auth-button secondary" href="{{ route('admin.dashboard') }}">Back to dashboard</a></div>
    </x-slot>

    @if(session('status'))<div class="auth-alert success">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="auth-alert error">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="admin-settings-form" enctype="multipart/form-data">@csrf @method('PUT')
        <section class="admin-card"><p class="admin-overline">Public identity</p><h3>Brand and contact</h3><div class="admin-form-grid">
            <label class="auth-field"><span>Website name</span><input class="auth-input" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? config('site.brand.name')) }}" required></label>
            <label class="auth-field"><span>Legal business name</span><input class="auth-input" name="legal_name" value="{{ old('legal_name', $settings['legal_name'] ?? config('site.brand.legal_name')) }}"></label>
            <label class="auth-field"><span>Contact email</span><input class="auth-input" type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? config('site.brand.email')) }}" required></label>
            <label class="auth-field"><span>Contact phone</span><input class="auth-input" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? config('site.brand.phone')) }}"></label>
            <label class="auth-field"><span>WhatsApp number</span><input class="auth-input" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? config('site.brand.whatsapp_number', config('site.brand.phone'))) }}" placeholder="+49 30 23125 400"><small class="auth-help">Include the country code. Spaces and symbols are removed automatically.</small></label>
            <label class="auth-field"><span>Support hours</span><input class="auth-input" name="support_hours" value="{{ old('support_hours', $settings['support_hours'] ?? config('site.support_hours.weekday')) }}"></label>
        </div></section>        <section class="admin-card"><p class="admin-overline">Brand assets</p><h3>Logo and favicon</h3><p class="auth-help">Upload a PNG, JPG, WebP or SVG logo (2 MB maximum), plus a favicon (1 MB maximum). Both files are saved to Laravel public storage and update the public site automatically.</p><div class="logo-upload-grid"><div><label class="auth-field"><span>Header and footer logo</span><input id="logo" name="logo" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml"></label></div><div class="logo-preview"><span>Current logo</span><img src="{{ website_logo_url() }}" alt="Current {{ website_setting('brand.name') }} logo"></div><div><label class="auth-field"><span>Browser favicon</span><input id="favicon" name="favicon" type="file" accept="image/x-icon,image/png,image/jpeg,image/webp,image/svg+xml"></label></div><div class="logo-preview"><span>Current favicon</span><img src="{{ website_favicon_url() }}" alt="Current website favicon"></div></div></section>
        <section class="admin-card"><p class="admin-overline">Footer content</p><h3>English and German footer text</h3><p class="auth-help">Manage the text shown in the public footer. German values are used automatically on the German website.</p><div class="admin-form-grid">
            <label class="auth-field"><span>Footer description (English)</span><textarea class="auth-textarea" name="footer_description_en" rows="4">{{ old('footer_description_en', $settings['footer_description_en'] ?? '') }}</textarea></label>
            <label class="auth-field"><span>Footer description (German)</span><textarea class="auth-textarea" name="footer_description_de" rows="4">{{ old('footer_description_de', $settings['footer_description_de'] ?? '') }}</textarea></label>
            <label class="auth-field"><span>Trust note (English)</span><input class="auth-input" name="footer_trust_note_en" value="{{ old('footer_trust_note_en', $settings['footer_trust_note_en'] ?? '') }}"></label>
            <label class="auth-field"><span>Trust note (German)</span><input class="auth-input" name="footer_trust_note_de" value="{{ old('footer_trust_note_de', $settings['footer_trust_note_de'] ?? '') }}"></label>
            <label class="auth-field"><span>Treatments heading (English)</span><input class="auth-input" name="footer_treatments_heading_en" value="{{ old('footer_treatments_heading_en', $settings['footer_treatments_heading_en'] ?? '') }}"></label>
            <label class="auth-field"><span>Treatments heading (German)</span><input class="auth-input" name="footer_treatments_heading_de" value="{{ old('footer_treatments_heading_de', $settings['footer_treatments_heading_de'] ?? '') }}"></label>
            <label class="auth-field"><span>Support heading (English)</span><input class="auth-input" name="footer_support_heading_en" value="{{ old('footer_support_heading_en', $settings['footer_support_heading_en'] ?? '') }}"></label>
            <label class="auth-field"><span>Support heading (German)</span><input class="auth-input" name="footer_support_heading_de" value="{{ old('footer_support_heading_de', $settings['footer_support_heading_de'] ?? '') }}"></label>
            <label class="auth-field"><span>Company heading (English)</span><input class="auth-input" name="footer_company_heading_en" value="{{ old('footer_company_heading_en', $settings['footer_company_heading_en'] ?? '') }}"></label>
            <label class="auth-field"><span>Company heading (German)</span><input class="auth-input" name="footer_company_heading_de" value="{{ old('footer_company_heading_de', $settings['footer_company_heading_de'] ?? '') }}"></label>
            @foreach(['about' => 'About link', 'guides' => 'Guides link', 'contact' => 'Contact link', 'about_us' => 'About Us link', 'for_clinics' => 'For Clinics link', 'legal_privacy' => 'Legal & Privacy link', 'copyright' => 'Copyright text', 'medical_notice' => 'Medical notice', 'disclosure' => 'Provider disclosure'] as $key => $label)
                <label class="auth-field"><span>{{ $label }} (English)</span><input class="auth-input" name="footer_{{ $key }}_en" value="{{ old('footer_'.$key.'_en', $settings['footer_'.$key.'_en'] ?? '') }}"></label>
                <label class="auth-field"><span>{{ $label }} (German)</span><input class="auth-input" name="footer_{{ $key }}_de" value="{{ old('footer_'.$key.'_de', $settings['footer_'.$key.'_de'] ?? '') }}"></label>
            @endforeach
        </div></section>        <section class="admin-card"><p class="admin-overline">Form security</p><h3>reCAPTCHA protection</h3><p class="auth-help">Protect contact and treatment forms from spam with Google reCAPTCHA v3.</p><div class="auth-help"><strong>How to set up:</strong> Create a reCAPTCHA v3 site in Google, add your website domain, then paste the public key in <strong>Site key</strong> and the private key in <strong>Secret key</strong>. Use <code>localhost</code> for local testing and your real domain in production. The secret key is never displayed after saving.</div><div class="admin-form-grid"><label class="auth-field"><span>Site key</span><input class="auth-input" name="recaptcha_site_key" value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? env('RECAPTCHA_SITE_KEY', '')) }}" autocomplete="off"><small>This public key may be loaded by the website.</small></label><label class="auth-field"><span>Secret key</span><input class="auth-input" type="password" name="recaptcha_secret_key" value="" autocomplete="new-password"><small>Private key. Leave blank when updating other settings.</small></label></div></section>
        <section class="admin-card"><p class="admin-overline">Email delivery</p><h3>SMTP configuration</h3><p class="auth-help">Use <strong>Log</strong> during development. For SMTP, add <code>MAIL_PASSWORD</code> to <code>.env</code>; it is never displayed or stored in the admin database.</p><div class="admin-form-grid">
            <label class="auth-field"><span>Mailer</span><select class="auth-select" name="mail_mailer"><option value="log" @selected(old('mail_mailer', $settings['mail_mailer'] ?? env('MAIL_MAILER', 'log')) === 'log')>Log (development)</option><option value="smtp" @selected(old('mail_mailer', $settings['mail_mailer'] ?? env('MAIL_MAILER')) === 'smtp')>SMTP</option></select></label>
            <label class="auth-field"><span>SMTP host</span><input class="auth-input" name="mail_host" value="{{ old('mail_host', $settings['mail_host'] ?? env('MAIL_HOST')) }}"></label>
            <label class="auth-field"><span>SMTP port</span><input class="auth-input" type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port'] ?? env('MAIL_PORT', 587)) }}"></label>
            <label class="auth-field"><span>SMTP username</span><input class="auth-input" name="mail_username" value="{{ old('mail_username', $settings['mail_username'] ?? env('MAIL_USERNAME')) }}"></label><label class="auth-field"><span>SMTP password</span><input class="auth-input" type="password" name="mail_password" value="" autocomplete="new-password"><small>Enter this in <code>.env</code>. It is never displayed or saved in the admin database.</small></label>
            <label class="auth-field"><span>Encryption</span><select class="auth-select" name="mail_encryption"><option value="">None</option><option value="tls" @selected(old('mail_encryption', $settings['mail_encryption'] ?? env('MAIL_ENCRYPTION')) === 'tls')>TLS</option><option value="ssl" @selected(old('mail_encryption', $settings['mail_encryption'] ?? env('MAIL_ENCRYPTION')) === 'ssl')>SSL</option></select></label>
            <label class="auth-field"><span>From email</span><input class="auth-input" type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS', config('site.brand.email'))) }}" required></label>
            <label class="auth-field"><span>From name</span><input class="auth-input" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? env('MAIL_FROM_NAME', config('site.brand.name'))) }}" required></label>
        </div></section>
        <button class="auth-button" type="submit">Save website settings</button>
    </form>

    @push('scripts')
        <script src="https://unpkg.com/filepond-plugin-file-validate-type@^1/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size@^2/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview@^4/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
        <script>
            FilePond.registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize, FilePondPluginImagePreview);
            FilePond.create(document.querySelector('#logo'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'],
                maxFileSize: '2MB',
                storeAsFile: true,
                labelIdle: 'Drag & Drop your logo or <span class="filepond--label-action">Browse</span>',
            });
            FilePond.create(document.querySelector('#favicon'), {
                acceptedFileTypes: ['image/x-icon', 'image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'],
                maxFileSize: '1MB',
                storeAsFile: true,
                labelIdle: 'Drag & Drop your favicon or <span class="filepond--label-action">Browse</span>',
            });
        </script>
    @endpush
</x-app-layout>
