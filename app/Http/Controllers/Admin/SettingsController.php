<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => SiteSetting::values()]);
    }

    public function labelsEdit(): View
    {
        return view('admin.labels.edit', ['settings' => SiteSetting::values()]);
    }

    public function labelsUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'home_label_en' => ['required', 'string', 'max:100'],
            'home_label_de' => ['required', 'string', 'max:100'],
            'treatments_label_en' => ['required', 'string', 'max:100'],
            'treatments_label_de' => ['required', 'string', 'max:100'],
            'clinics_label_en' => ['required', 'string', 'max:100'],
            'clinics_label_de' => ['required', 'string', 'max:100'],
            'how_it_works_label_en' => ['required', 'string', 'max:100'],
            'how_it_works_label_de' => ['required', 'string', 'max:100'],
            'patient_services_label_en' => ['required', 'string', 'max:100'],
            'patient_services_label_de' => ['required', 'string', 'max:100'],
            'patient_stories_label_en' => ['required', 'string', 'max:100'],
            'patient_stories_label_de' => ['required', 'string', 'max:100'],
            'about_label_en' => ['required', 'string', 'max:100'],
            'about_label_de' => ['required', 'string', 'max:100'],
            'for_clinics_label_en' => ['required', 'string', 'max:100'],
            'for_clinics_label_de' => ['required', 'string', 'max:100'],
            'contact_label_en' => ['required', 'string', 'max:100'],
            'contact_label_de' => ['required', 'string', 'max:100'],
            'plan_journey_label_en' => ['required', 'string', 'max:100'],
            'plan_journey_label_de' => ['required', 'string', 'max:100'],            'browse_specialties_label_en' => ['required', 'string', 'max:100'],
            'browse_specialties_label_de' => ['required', 'string', 'max:100'],
            'view_all_specialties_label_en' => ['required', 'string', 'max:100'],
            'view_all_specialties_label_de' => ['required', 'string', 'max:100'],
            'view_all_doctors_label_en' => ['required', 'string', 'max:100'],
            'view_all_doctors_label_de' => ['required', 'string', 'max:100'],
            'explore_clinics_label_en' => ['required', 'string', 'max:100'],
            'explore_clinics_label_de' => ['required', 'string', 'max:100'],
            'see_how_it_works_label_en' => ['required', 'string', 'max:100'],
            'see_how_it_works_label_de' => ['required', 'string', 'max:100'],
            'read_guide_label_en' => ['required', 'string', 'max:100'],
            'read_guide_label_de' => ['required', 'string', 'max:100'],
            'send_message_label_en' => ['required', 'string', 'max:100'],
            'send_message_label_de' => ['required', 'string', 'max:100'],
            'request_callback_label_en' => ['required', 'string', 'max:100'],
            'request_callback_label_de' => ['required', 'string', 'max:100'],
            'start_enquiry_label_en' => ['required', 'string', 'max:100'],
            'start_enquiry_label_de' => ['required', 'string', 'max:100'],
            'show_all_label_en' => ['required', 'string', 'max:100'],
            'show_all_label_de' => ['required', 'string', 'max:100'],
            'aesthetic_label_en' => ['required', 'string', 'max:100'],
            'aesthetic_label_de' => ['required', 'string', 'max:100'],
            'surgical_label_en' => ['required', 'string', 'max:100'],
            'surgical_label_de' => ['required', 'string', 'max:100'],
            'vision_label_en' => ['required', 'string', 'max:100'],
            'vision_label_de' => ['required', 'string', 'max:100'],
            'specialist_label_en' => ['required', 'string', 'max:100'],
            'specialist_label_de' => ['required', 'string', 'max:100'],            'browse_specialties_label_en' => ['required', 'string', 'max:100'],
            'browse_specialties_label_de' => ['required', 'string', 'max:100'],
            'view_all_specialties_label_en' => ['required', 'string', 'max:100'],
            'view_all_specialties_label_de' => ['required', 'string', 'max:100'],
            'view_all_doctors_label_en' => ['required', 'string', 'max:100'],
            'view_all_doctors_label_de' => ['required', 'string', 'max:100'],
            'explore_clinics_label_en' => ['required', 'string', 'max:100'],
            'explore_clinics_label_de' => ['required', 'string', 'max:100'],
            'see_how_it_works_label_en' => ['required', 'string', 'max:100'],
            'see_how_it_works_label_de' => ['required', 'string', 'max:100'],
            'read_guide_label_en' => ['required', 'string', 'max:100'],
            'read_guide_label_de' => ['required', 'string', 'max:100'],
            'send_message_label_en' => ['required', 'string', 'max:100'],
            'send_message_label_de' => ['required', 'string', 'max:100'],
            'request_callback_label_en' => ['required', 'string', 'max:100'],
            'request_callback_label_de' => ['required', 'string', 'max:100'],
            'start_enquiry_label_en' => ['required', 'string', 'max:100'],
            'start_enquiry_label_de' => ['required', 'string', 'max:100'],
            'show_all_label_en' => ['required', 'string', 'max:100'],
            'show_all_label_de' => ['required', 'string', 'max:100'],
            'aesthetic_label_en' => ['required', 'string', 'max:100'],
            'aesthetic_label_de' => ['required', 'string', 'max:100'],
            'surgical_label_en' => ['required', 'string', 'max:100'],
            'surgical_label_de' => ['required', 'string', 'max:100'],
            'vision_label_en' => ['required', 'string', 'max:100'],
            'vision_label_de' => ['required', 'string', 'max:100'],
            'specialist_label_en' => ['required', 'string', 'max:100'],
            'specialist_label_de' => ['required', 'string', 'max:100'],
        ]);

        SiteSetting::putMany($validated);

        return back()->with('status', 'Website labels saved successfully.');
    }
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:100'],
            'whatsapp_number' => ['nullable', 'string', 'max:100'],
            'support_hours' => ['nullable', 'string', 'max:255'],
            'footer_description_en' => ['nullable', 'string', 'max:1000'],
            'footer_description_de' => ['nullable', 'string', 'max:1000'],
            'footer_trust_note_en' => ['nullable', 'string', 'max:255'],
            'footer_trust_note_de' => ['nullable', 'string', 'max:255'],
            'footer_treatments_heading_en' => ['nullable', 'string', 'max:100'],
            'footer_treatments_heading_de' => ['nullable', 'string', 'max:100'],
            'footer_support_heading_en' => ['nullable', 'string', 'max:100'],
            'footer_support_heading_de' => ['nullable', 'string', 'max:100'],
            'footer_company_heading_en' => ['nullable', 'string', 'max:100'],
            'footer_company_heading_de' => ['nullable', 'string', 'max:100'],
            'footer_about_en' => ['nullable', 'string', 'max:100'],
            'footer_about_de' => ['nullable', 'string', 'max:100'],
            'footer_guides_en' => ['nullable', 'string', 'max:100'],
            'footer_guides_de' => ['nullable', 'string', 'max:100'],
            'footer_contact_en' => ['nullable', 'string', 'max:100'],
            'footer_contact_de' => ['nullable', 'string', 'max:100'],
            'footer_about_us_en' => ['nullable', 'string', 'max:100'],
            'footer_about_us_de' => ['nullable', 'string', 'max:100'],
            'footer_for_clinics_en' => ['nullable', 'string', 'max:100'],
            'footer_for_clinics_de' => ['nullable', 'string', 'max:100'],
            'footer_legal_privacy_en' => ['nullable', 'string', 'max:100'],
            'footer_legal_privacy_de' => ['nullable', 'string', 'max:100'],
            'footer_copyright_en' => ['nullable', 'string', 'max:255'],
            'footer_copyright_de' => ['nullable', 'string', 'max:255'],
            'footer_medical_notice_en' => ['nullable', 'string', 'max:255'],
            'footer_medical_notice_de' => ['nullable', 'string', 'max:255'],
            'footer_disclosure_en' => ['nullable', 'string', 'max:500'],
            'footer_disclosure_de' => ['nullable', 'string', 'max:500'],            'recaptcha_site_key' => ['nullable', 'string', 'max:255'],
            'recaptcha_secret_key' => ['nullable', 'string', 'max:255'],
            'mail_mailer' => ['required', 'in:log,smtp'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'in:tls,ssl'],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,jpg,jpeg,png,webp,svg', 'max:1024'],
        ]);

        if ($request->hasFile('logo')) {
            $path = Storage::disk('uploads')->putFile('settings', $request->file('logo'));
            $validated['logo_path'] = '/uploads/'.ltrim($path, '/');
        }

        if ($request->hasFile('favicon')) {
            $path = Storage::disk('uploads')->putFile('settings', $request->file('favicon'));
            $validated['favicon_path'] = '/uploads/'.ltrim($path, '/');
        }

        unset($validated['logo'], $validated['favicon']);
        if (blank($validated['recaptcha_secret_key'] ?? null)) {
            unset($validated['recaptcha_secret_key']);
        }
        SiteSetting::putMany($validated);

        return back()->with('status', 'Website settings saved. SMTP passwords remain protected in the environment file.');
    }
}
