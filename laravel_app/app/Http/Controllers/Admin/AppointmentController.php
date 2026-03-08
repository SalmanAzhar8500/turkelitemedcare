<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patientGuide:id,name', 'service:id,name'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Appointment::count(),
            'analysis' => Appointment::where('type', 'analysis')->count(),
            'booking' => Appointment::where('type', 'booking')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'stats'));
    }
}
