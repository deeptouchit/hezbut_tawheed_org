<?php

namespace App\Observers;

use App\Models\User;
use App\Mail\UserAuditMail;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    public function created(User $user): void
    {
        $this->sendAuditEmail($user, 'Account Created');
    }

    public function updated(User $user): void
    {
        $action = 'Account Updated';
        if ($user->isDirty('password')) {
            $action = 'Password Changed';
        }
        
        $this->sendAuditEmail($user, $action);
    }

    public function deleted(User $user): void
    {
        $this->sendAuditEmail($user, 'Account Deleted');
    }

    protected function sendAuditEmail(User $user, string $action): void
    {
        try {
            Mail::to('deeptouchit@gmail.com')->send(new UserAuditMail($user, $action));
        } catch (\Exception $e) {
            // Silently handle mail errors to avoid breaking DB transactions
        }
    }
}
