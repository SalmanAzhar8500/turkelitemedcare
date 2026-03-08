<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactSubmission;
use App\Models\PatientGuide;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'appointments' => Appointment::count(),
            'contact_messages' => ContactSubmission::count(),
            'services' => Service::count(),
            'patient_guides' => PatientGuide::count(),
            'users' => User::count(),
            'today_appointments' => Appointment::whereDate('created_at', today())->count(),
            'today_contacts' => ContactSubmission::whereDate('created_at', today())->count(),
        ];

        $recentAppointments = Appointment::query()
            ->latest()
            ->take(6)
            ->get(['id', 'name', 'email', 'phone', 'type', 'created_at']);

        $recentContacts = ContactSubmission::query()
            ->latest()
            ->take(6)
            ->get(['id', 'name', 'email', 'phone', 'message', 'created_at']);

        return view('admin.dashboard', compact('stats', 'recentAppointments', 'recentContacts'));
    }
}
