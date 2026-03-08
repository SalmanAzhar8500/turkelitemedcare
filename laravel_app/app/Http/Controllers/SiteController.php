<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ContactSubmission;
use App\Models\HomeSetting;
use App\Models\PatientGuide;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SiteController extends Controller
{
    public function home()
    {
        $homeSetting = HomeSetting::first();
        $featuredServices = Service::whereNull('parentid')->latest()->take(3)->get();

        return view('frontend.welcome', compact('homeSetting', 'featuredServices'));
    }

    public function about()
    {
        $homeSetting = HomeSetting::first();

        return view('frontend.pages.about', compact('homeSetting'));
    }
    public function services()
    {
        $services = Service::latest()->take(6)->get();
        $totalServices = Service::count();

        return view('frontend.pages.services', compact('services', 'totalServices'));
    }

    public function loadMoreServices(Request $request)
    {
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 6);
        $limit = $limit > 0 ? min($limit, 24) : 6;

        $services = Service::latest()->skip($offset)->take($limit)->get();
        $totalServices = Service::count();

        $nextOffset = $offset + $services->count();

        return response()->json([
            'html' => view('frontend.pages.partials.service-cards', compact('services'))->render(),
            'nextOffset' => $nextOffset,
            'hasMore' => $nextOffset < $totalServices,
        ]);
    }

    public function serviceDetails($slug)
    {
        $service = Service::with(['children' => function ($query) {
            $query->orderBy('name');
        }])->where('slug', $slug)->firstOrFail();

        $detailContent = is_array($service->detail_content) ? $service->detail_content : [];

        $relatedServices = Service::query()
            ->where('id', '!=', $service->id)
            ->when(
                $service->parentid,
                fn ($query) => $query->where('parentid', $service->parentid),
                fn ($query) => $query->whereNull('parentid')
            )
            ->orderBy('name')
            ->take(5)
            ->get();

        $descriptionText = trim(strip_tags((string) $service->description));
        $descriptionSentences = collect(preg_split('/(?<=[.!?])\s+/', $descriptionText))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values();

        $serviceHighlights = collect($detailContent['highlights'] ?? [])->filter()->values();

        if ($serviceHighlights->isEmpty()) {
            $serviceHighlights = $service->children
                ->pluck('name')
                ->filter()
                ->values();
        }

        if ($serviceHighlights->isEmpty()) {
            $serviceHighlights = $descriptionSentences
                ->map(fn ($sentence) => Str::limit($sentence, 80, '...'))
                ->take(6)
                ->values();
        }

        $serviceSteps = collect($detailContent['steps'] ?? [])->filter(function ($step) {
            return filled($step['title'] ?? null) || filled($step['description'] ?? null) || filled($step['icon'] ?? null);
        })->values();

        if ($serviceSteps->isEmpty()) {
            $serviceSteps = $service->children
            ->take(3)
            ->map(function ($item) {
                return [
                    'title' => $item->name,
                    'description' => $item->description ?: 'Our team will guide you through this step with focused support.',
                    'image' => $item->image,
                    'icon' => null,
                ];
            })
            ->values();
        }

        if ($serviceSteps->isEmpty()) {
            $serviceSteps = collect([
                [
                    'title' => 'Initial consultation',
                    'description' => 'We assess your needs and understand your goals for this service.',
                    'image' => null,
                    'icon' => null,
                ],
                [
                    'title' => 'Personalized support',
                    'description' => 'Our team connects you with the right resources and action plan.',
                    'image' => null,
                    'icon' => null,
                ],
                [
                    'title' => 'Follow-up and care',
                    'description' => 'We continue support and monitor progress to improve outcomes.',
                    'image' => null,
                    'icon' => null,
                ],
            ]);
        }

        $featureItems = collect($detailContent['features'] ?? [])
            ->filter(fn ($item) => filled($item['title'] ?? null) || filled($item['description'] ?? null) || filled($item['image'] ?? null) || filled($item['icon'] ?? null))
            ->values();

        if ($featureItems->isEmpty()) {
            $featureItems = $service->children
                ->map(fn ($item) => [
                    'title' => $item->name,
                    'description' => $item->description,
                    'image' => $item->image,
                    'icon' => null,
                ])
                ->values();
        }

        if ($featureItems->isEmpty()) {
            $featureItems = $relatedServices
                ->map(fn ($item) => [
                    'title' => $item->name,
                    'description' => $item->description,
                    'image' => $item->image,
                    'icon' => null,
                ])
                ->values();
        }

        $faqItems = collect($detailContent['faqs'] ?? [])->filter(function ($faq) {
            return filled($faq['question'] ?? null) || filled($faq['answer'] ?? null);
        })->values();

        if ($faqItems->isEmpty()) {
            $faqItems = collect([
                [
                    'question' => 'What does this service cover?',
                    'answer' => $service->description ?: 'This service is designed to provide focused support based on your needs.',
                ],
                [
                    'question' => 'Who can apply for this service?',
                    'answer' => 'Anyone looking for ' . strtolower($service->name) . ' support can reach out and our team will guide the next steps.',
                ],
                [
                    'question' => 'How do I get started?',
                    'answer' => 'Contact us through the contact page and mention ' . $service->name . ' so we can assist you quickly.',
                ],
                [
                    'question' => 'Is ongoing support available?',
                    'answer' => 'Yes, we provide follow-up support to make sure you continue receiving the right care and guidance.',
                ],
                [
                    'question' => 'Can I learn about related services?',
                    'answer' => 'Yes, you can also explore related services listed in the sidebar section of this page.',
                ],
            ]);
        }

        return view('frontend.pages.service-details', compact('service', 'relatedServices', 'serviceHighlights', 'serviceSteps', 'detailContent', 'featureItems', 'faqItems'));
    }

    public function contact()
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $contactData = is_array($homeSetting->contact_data) ? $homeSetting->contact_data : [];

        return view('frontend.pages.contact', compact('contactData'));
    }

    public function patientGuide()
    {
        $guides = PatientGuide::query()
            ->where('type', 'main')
            ->orderBy('name')
            ->with(['children' => function ($query) {
                $query->orderBy('name')->with(['children' => function ($childQuery) {
                    $childQuery->orderBy('name');
                }]);
            }])
            ->get();

        return view('frontend.pages.patient-guide', compact('guides'));
    }

    public function patientGuideDetails($slug)
    {
        $guide = PatientGuide::with(['children' => function ($query) {
            $query->orderBy('name')->with(['children' => function ($childQuery) {
                $childQuery->orderBy('name');
            }]);
        }, 'parent'])->where('slug', $slug)->firstOrFail();

        $detailContent = is_array($guide->detail_content) ? $guide->detail_content : [];
        $mainServices = Service::whereNull('parentid')->orderBy('name')->get(['id', 'name']);

        $relatedGuides = PatientGuide::query()
            ->where('id', '!=', $guide->id)
            ->when(
                $guide->parentid,
                fn ($query) => $query->where('parentid', $guide->parentid),
                fn ($query) => $query->whereNull('parentid')
            )
            ->orderBy('name')
            ->take(8)
            ->get();

        $breadcrumb = [];
        $current = $guide;

        while ($current) {
            array_unshift($breadcrumb, $current->name);
            $current = $current->parent;
        }

        return view('frontend.pages.patient-guide-details', compact('guide', 'relatedGuides', 'breadcrumb', 'detailContent', 'mainServices'));
    }

    public function submitHairAnalysis(Request $request, $slug)
    {
        $guide = PatientGuide::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['nullable', 'string'],
            'head_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'recaptcha_token' => ['nullable', 'string'],
        ]);

        $this->validateRecaptcha($validated['recaptcha_token'] ?? null);

        $headImagePath = $request->hasFile('head_image')
            ? $request->file('head_image')->store('appointments/head-analysis', 'public')
            : null;

        $fullName = trim(($validated['fname'] ?? '') . ' ' . ($validated['lname'] ?? ''));

        $appointment = Appointment::create([
            'patient_guide_id' => $guide->id,
            'type' => 'analysis',
            'name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'] ?? null,
            'head_image' => $headImagePath,
        ]);

        $this->sendAppointmentMails($appointment, 'Online Hair Analysis Request');

        return back()->with('success', 'Your hair analysis request has been submitted successfully.');
    }

    public function submitBooking(Request $request, $slug)
    {
        $guide = PatientGuide::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'booking_name' => ['required', 'string', 'max:255'],
            'booking_email' => ['required', 'email', 'max:255'],
            'booking_phone' => ['required', 'string', 'max:50'],
            'booking_service_id' => ['required', 'integer', 'exists:services,id'],
            'booking_date' => ['required', 'date'],
            'booking_notes' => ['nullable', 'string'],
            'recaptcha_token' => ['nullable', 'string'],
        ]);

        $this->validateRecaptcha($validated['recaptcha_token'] ?? null);

        $appointment = Appointment::create([
            'patient_guide_id' => $guide->id,
            'service_id' => (int) $validated['booking_service_id'],
            'type' => 'booking',
            'name' => $validated['booking_name'],
            'email' => $validated['booking_email'],
            'phone' => $validated['booking_phone'],
            'appointment_date' => $validated['booking_date'],
            'message' => $validated['booking_notes'] ?? null,
        ]);

        $this->sendAppointmentMails($appointment, 'Consultation Booking Request');

        return back()
            ->with('success', 'Your booking request has been submitted successfully.')
            ->with('booking_success', 'Done! Your booking request has been submitted successfully.');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'message' => ['nullable', 'string'],
            'recaptcha_token' => ['nullable', 'string'],
        ]);

        $this->validateRecaptcha($validated['recaptcha_token'] ?? null);

        $fullName = trim(($validated['fname'] ?? '') . ' ' . ($validated['lname'] ?? ''));

        $submission = ContactSubmission::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $this->sendContactAdminMail($submission);

        return back()->with('contact_success', 'Done! Your contact request has been submitted successfully.');
    }

    private function applyDynamicSmtp(): array
    {
        $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
        $mailData = is_array($homeSetting->mail_data) ? $homeSetting->mail_data : [];

        if (!empty($mailData['host']) && !empty($mailData['port']) && !empty($mailData['username']) && !empty($mailData['from_address'])) {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $mailData['host'],
                'mail.mailers.smtp.port' => (int) $mailData['port'],
                'mail.mailers.smtp.encryption' => $mailData['encryption'] ?? null,
                'mail.mailers.smtp.username' => $mailData['username'],
                'mail.mailers.smtp.password' => $mailData['password'] ?? null,
                'mail.from.address' => $mailData['from_address'],
                'mail.from.name' => $mailData['from_name'] ?? config('app.name'),
            ]);

            app(MailManager::class)->forgetMailers();
        }

        return $mailData;
    }

    private function validateRecaptcha(?string $token): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (blank($secretKey)) {
            return;
        }

        if (blank($token)) {
            throw ValidationException::withMessages([
                'recaptcha_token' => ['Please verify that you are not a robot and try again.'],
            ]);
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $token,
                    'remoteip' => request()->ip(),
                ]);

            $result = $response->json();
            $success = (bool) ($result['success'] ?? false);
            $score = $result['score'] ?? null;

            if (!$success || (is_numeric($score) && (float) $score < 0.3)) {
                throw ValidationException::withMessages([
                    'recaptcha_token' => ['reCAPTCHA verification failed. Please try again.'],
                ]);
            }
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::warning('reCAPTCHA verification request failed', ['error' => $exception->getMessage()]);

            throw ValidationException::withMessages([
                'recaptcha_token' => ['Unable to verify reCAPTCHA right now. Please try again.'],
            ]);
        }
    }

    private function sendAppointmentMails(Appointment $appointment, string $subjectPrefix): void
    {
        try {
            $mailData = $this->applyDynamicSmtp();

            $adminEmail = $mailData['admin_email'] ?? config('mail.from.address');
            $serviceName = optional($appointment->service)->name;

            if (!empty($appointment->email)) {
                Mail::to($appointment->email)->send(new class($appointment, $subjectPrefix, $serviceName) extends \Illuminate\Mail\Mailable {
                    public function __construct(private Appointment $appointment, private string $subjectPrefix, private ?string $serviceName) {}
                    public function build()
                    {
                        return $this->subject($this->subjectPrefix . ' - Received')
                            ->view('template.appointment-user', [
                                'appointment' => $this->appointment,
                                'subjectPrefix' => $this->subjectPrefix,
                                'serviceName' => $this->serviceName,
                            ]);
                    }
                });
            }

            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new class($appointment, $subjectPrefix, $serviceName) extends \Illuminate\Mail\Mailable {
                    public function __construct(private Appointment $appointment, private string $subjectPrefix, private ?string $serviceName) {}
                    public function build()
                    {
                        return $this->subject($this->subjectPrefix . ' - Admin Notification')
                            ->view('template.appointment-admin', [
                                'appointment' => $this->appointment,
                                'subjectPrefix' => $this->subjectPrefix,
                                'serviceName' => $this->serviceName,
                            ]);
                    }
                });
            }
        } catch (\Throwable $exception) {
            Log::error('Appointment mail send failed', ['error' => $exception->getMessage(), 'appointment_id' => $appointment->id]);
        }
    }

    private function sendContactAdminMail(ContactSubmission $submission): void
    {
        try {
            $mailData = $this->applyDynamicSmtp();
            $adminEmail = $mailData['admin_email'] ?? config('mail.from.address');

            if (empty($adminEmail)) {
                return;
            }

            Mail::to($adminEmail)->send(new class($submission) extends \Illuminate\Mail\Mailable {
                public function __construct(private ContactSubmission $submission) {}

                public function build()
                {
                    return $this->subject('New Contact Message - Admin Notification')
                        ->view('template.contact-admin', [
                            'submission' => $this->submission,
                        ]);
                }
            });
        } catch (\Throwable $exception) {
            Log::error('Contact admin mail send failed', [
                'error' => $exception->getMessage(),
                'submission_id' => $submission->id,
            ]);
        }
    }
}
