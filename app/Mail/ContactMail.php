<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public ContactMessage $contact;

    public function __construct(ContactMessage $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'যোগাযোগ ফর্ম থেকে নতুন বার্তা - বগুড়া বাজার',
            replyTo: [$this->contact->email, $this->contact->name],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'name' => $this->contact->name,
                'email' => $this->contact->email,
                'phone' => $this->contact->phone,
                'subject' => $this->contact->subject,
                'messageBody' => $this->contact->message,
                'date' => $this->contact->created_at->format('d M, Y h:i A'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
