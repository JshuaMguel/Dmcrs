<?php
// This controller is now removed. Use NotificationController for all roles.
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HeadNotificationController extends Controller
{
    /**
     * Display notifications for Academic Head.
     */
    public function index(Request $request): View
    {
        $headId = Auth::id();
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $headId)
            ->orderByDesc('created_at')
            ->get();

        return view('head.notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as seen.
     */
    public function markAsSeen($id)
    {
        DB::table('notifications')->where('id', $id)->update(['status' => 'seen']);
        return redirect()->back();
    }
}
