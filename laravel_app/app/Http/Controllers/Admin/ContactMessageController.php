<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactSubmission::query()
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => ContactSubmission::count(),
            'today' => ContactSubmission::whereDate('created_at', today())->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }
}
