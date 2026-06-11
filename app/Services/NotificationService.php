<?php

namespace App\Services;

class NotificationService
{
    public function getNotifications($user)
    {
        return $user->notifications()->paginate(20);
    }

    public function getUnreadCount($user)
    {
        return $user->unreadNotifications()->count();
    }

    public function markAsRead($user, $id)
    {
        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead($user)
    {
        $notifications = $user->unreadNotifications()->get();
        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }
    }

    public function destroy($user, $id)
    {
        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
        }
    }

    public function destroyAll($user)
    {
        $user->notifications()->delete();
    }
}