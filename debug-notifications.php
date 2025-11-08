<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;

// Debug route to check notification bell
Route::middleware(['auth'])->get('/debug-notifications', function () {
    $user = Auth::user();
    $unreadCount = $user->unreadNotifications->count();
    $totalCount = $user->notifications->count();
    
    $recent = $user->notifications->take(5);
    
    $debug = [
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ],
        'notification_counts' => [
            'total' => $totalCount,
            'unread' => $unreadCount
        ],
        'recent_notifications' => $recent->map(function($notif) {
            return [
                'id' => $notif->id,
                'type' => $notif->type,
                'title' => $notif->data['title'] ?? 'No title',
                'message' => $notif->data['message'] ?? 'No message',
                'read_at' => $notif->read_at,
                'created_at' => $notif->created_at
            ];
        })
    ];
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->name('debug.notifications');