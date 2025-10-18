<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    public function index()
    {
        $notifications = Auth::user()->notifications;
        return view('notifications.index', compact('notifications'));
    }
}