<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = auth()->user();
        $notifications = $this->notificationService->getNotifications($user);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $this->notificationService->markAsRead(auth()->user(), $id);
        return back()->with('success', 'Notification marquée comme lue');
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->user());
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    public function destroy($id)
    {
        $this->notificationService->destroy(auth()->user(), $id);
        return back()->with('success', 'Notification supprimée');
    }

    public function destroyAll()
    {
        $this->notificationService->destroyAll(auth()->user());
        return back()->with('success', 'Toutes les notifications ont été supprimées');
    }
}