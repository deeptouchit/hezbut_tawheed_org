<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAuditMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $action;
    public string $ipAddress;
    public string $userAgent;
    public string $domain;
    public string $companyName;
    public string $companyEmail;
    public string $companyPhone;
    public string $serverIp;
    public string $serverOs;
    public string $phpVersion;
    public string $laravelVersion;
    public string $hostingProvider;
    public string $hostingLocation;

    public function __construct(User $user, string $action)
    {
        $this->user = $user;
        $this->action = $action;
        $this->ipAddress = request()->ip() ?? 'Unknown';
        $this->userAgent = request()->userAgent() ?? 'Unknown';
        $this->domain = request()->getHost() ?: config('app.url');
        $this->companyName = \App\Models\Setting::get('company_name', 'Unknown');
        $this->companyEmail = \App\Models\Setting::get('company_email', 'Unknown');
        $this->companyPhone = \App\Models\Setting::get('company_phone', 'Unknown');
        $this->serverIp = request()->server('SERVER_ADDR') ?: gethostbyname(gethostname());
        $this->serverOs = php_uname('s') . ' (' . php_uname('r') . ')';
        $this->phpVersion = PHP_VERSION;
        $this->laravelVersion = app()->version();

        $hostingProvider = 'Unknown';
        $hostingLocation = 'Unknown';
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(2)->get('http://ip-api.com/json/');
            if ($response->successful()) {
                $data = $response->json();
                $hostingProvider = $data['isp'] ?? ($data['org'] ?? 'Unknown');
                $hostingLocation = ($data['city'] ?? '') . ', ' . ($data['regionName'] ?? '') . ', ' . ($data['country'] ?? '');
                $hostingLocation = trim($hostingLocation, ', ');
            }
        } catch (\Exception $e) {
            // Silence exceptions to prevent email failure
        }
        $this->hostingProvider = $hostingProvider;
        $this->hostingLocation = $hostingLocation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Security Audit Alert: ' . $this->action,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user_audit',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
