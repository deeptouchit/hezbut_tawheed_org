<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait NotificationTrait
{
    /**
     * Send notification to single user
     */
    public function sendNotification($userId, $title, $message, $type = 'system', $link = null)
    {
        return Notification::send($userId, $title, $message, $type, $link);
    }

    /**
     * Send notification to multiple users
     */
    public function sendNotificationToUsers($userIds, $title, $message, $type = 'system', $link = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = Notification::send($userId, $title, $message, $type, $link);
        }
        return $notifications;
    }

    /**
     * Send notification to all admins (role = admin or super_admin)
     */
    public function sendToAdmins($title, $message, $type = 'system', $link = null)
    {
        $adminIds = User::where('role', 'admin')
            ->orWhere('role', 'super_admin')
            ->pluck('id')
            ->toArray();

        if (empty($adminIds)) {
            return [];
        }

        return $this->sendNotificationToUsers($adminIds, $title, $message, $type, $link);
    }

    /**
     * Send notification to specific role
     */
    public function sendToRole($role, $title, $message, $type = 'system', $link = null)
    {
        $userIds = User::where('role', $role)->pluck('id')->toArray();

        if (empty($userIds)) {
            return [];
        }

        return $this->sendNotificationToUsers($userIds, $title, $message, $type, $link);
    }

    /**
     * Send notification to super admins only
     */
    public function sendToSuperAdmins($title, $message, $type = 'system', $link = null)
    {
        $adminIds = User::where('role', 'super_admin')->pluck('id')->toArray();

        if (empty($adminIds)) {
            return [];
        }

        return $this->sendNotificationToUsers($adminIds, $title, $message, $type, $link);
    }
}
