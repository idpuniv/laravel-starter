<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use App\Support\Flash;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    private function user()
    {
        return auth()->user();
    }

    public function index()
    {
        try {
            $notifications = $this->notificationService->getNotifications($this->user());
            $unreadCount = $this->notificationService->getUnreadCount($this->user());

            return view('admin.notifications.index', compact(
                'notifications',
                'unreadCount'
            ));

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function markAsRead(Notification $notification)
    {
        try {
            $this->notificationService->markAsRead($this->user(), $notification->id);

            return back()->with(
                Flash::SUCCESS,
                __('messages.notification.read')
            );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function markAllAsRead()
    {
        try {
            $this->notificationService->markAllAsRead($this->user());

            return back()->with(
                Flash::SUCCESS,
                __('messages.notification.all_read')
            );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function destroy(Notification $notification)
    {
        try {
            $this->notificationService->destroy($this->user(), $notification->id);

            return back()->with(
                Flash::SUCCESS,
                __('messages.notification.deleted')
            );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }

    public function destroyAll()
    {
        try {
            $this->notificationService->destroyAll($this->user());

            return back()->with(
                Flash::SUCCESS,
                __('messages.notification.all_deleted')
            );

        } catch (\Throwable $e) {
            Log::error($e);

            return back()->with(
                Flash::ERROR,
                __('messages.operation_failed')
            );
        }
    }
}