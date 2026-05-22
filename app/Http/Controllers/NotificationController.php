<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        $unreadCount = auth()->user()->unreadNotifications->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }
    
    public function latest()
    {
        $notifications = auth()->user()->notifications()->latest()->limit(5)->get();
        
        $html = view('components.notification-items', compact('notifications'))->render();
        
        return response()->json([
            'unread_count' => auth()->user()->unreadNotifications->count(),
            'html' => $html
        ]);
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
}