<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryMail;
use App\Models\SiteInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'contact');
        $isCallback = $type === 'callback';
        $isTreatmentPlan = $type === 'treatment-plan';

        $validated = $request->validate([
            'type' => ['required', Rule::in(['contact', 'callback', 'treatment-plan'])],
            'page_slug' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [$isCallback ? 'nullable' : 'required', 'email', 'max:255'],
            'phone' => [Rule::requiredIf($isCallback), 'nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => [$isCallback ? 'nullable' : 'required', 'string', 'max:5000'],
            'preferred_language' => ['nullable', 'string', 'max:50'],
            'preferred_time' => ['nullable', 'string', 'max:100'],
            'specialty' => [Rule::requiredIf($isTreatmentPlan), 'nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'procedure' => ['nullable', 'string', 'max:255'],
            'country' => [Rule::requiredIf($isTreatmentPlan), 'nullable', 'string', 'max:100'],
            'consent_processing' => ['accepted'],
            'company_website' => ['nullable', 'string', 'max:0'],
        ]);

        $inquiry = SiteInquiry::create([
            'type' => $validated['type'],
            'page_slug' => $validated['page_slug'] ?? 'contact',
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'] ?? 'Callback request',
            'preferred_language' => $validated['preferred_language'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'status' => 'new',
            'metadata' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'specialty' => $validated['specialty'] ?? null,
                'condition' => $validated['condition'] ?? null,
                'procedure' => $validated['procedure'] ?? null,
                'country' => $validated['country'] ?? null,
            ],
        ]);

        // Send after the response so visitors do not wait for the SMTP server.
        app()->terminating(function () use ($inquiry): void {
            try {
                Mail::to(config('mail.admin_address', config('mail.from.address')))
                    ->send(new NewInquiryMail($inquiry));
            } catch (\Throwable $exception) {
                report($exception);
            }
        });

        $locale = app()->getLocale();
        $message = match ($locale) {
            'de' => match (true) {
                $isTreatmentPlan => 'Vielen Dank. Ihre Anfrage zum Behandlungsplan wurde an unser Team gesendet.',
                $isCallback => 'Vielen Dank. Ihr Rückrufwunsch wurde an unser Team gesendet.',
                default => 'Vielen Dank. Ihre Anfrage wurde an unser Team gesendet.',
            },
            'ar' => match (true) {
                $isTreatmentPlan => 'شكراً لك. تم إرسال طلب خطة العلاج إلى فريقنا.',
                $isCallback => 'شكراً لك. تم إرسال طلب معاودة الاتصال إلى فريقنا.',
                default => 'شكراً لك. تم إرسال استفسارك إلى فريقنا.',
            },
            default => match (true) {
                $isTreatmentPlan => 'Thanks. Your treatment plan request has been sent to the team.',
                $isCallback => 'Thanks. Your callback request has been sent to the team.',
                default => 'Thanks. Your enquiry has been sent to the team.',
            },
        };

        if ($validated['type'] === 'treatment-plan') {
            return redirect()->route('treatment-plan', $locale === 'en' ? [] : ['locale' => $locale])->with('status', $message);
        }

        return back()->with('status', $message);
    }
}
