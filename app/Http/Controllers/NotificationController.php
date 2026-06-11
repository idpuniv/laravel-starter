<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        protected readonly NotificationService $notificationService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();

        $notifications = $this->notificationService->getNotifications($user);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        return view('notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $this->notificationService->markAsRead(
            auth()->user(),
            $id
        );

        return back()->with(
            'success',
            __('notifications.marked_as_read')
        );
    }

    public function markAllAsRead(): RedirectResponse
    {
        $this->notificationService->markAllAsRead(
            auth()->user()
        );

        return back()->with(
            'success',
            __('notifications.all_marked_as_read')
        );
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->notificationService->destroy(
            auth()->user(),
            $id
        );

        return back()->with(
            'success',
            __('notifications.deleted')
        );
    }

    public function destroyAll(): RedirectResponse
    {
        $this->notificationService->destroyAll(
            auth()->user()
        );

        return back()->with(
            'success',
            __('notifications.all_deleted')
        );
    }
}