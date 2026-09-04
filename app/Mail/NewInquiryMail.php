<?php

namespace App\Mail;

use App\Models\SiteInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SiteInquiry $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New '.ucwords(str_replace('-', ' ', $this->inquiry->type)).' inquiry',
            replyTo: $this->inquiry->email ? [$this->inquiry->email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.inquiries.new');
    }
}