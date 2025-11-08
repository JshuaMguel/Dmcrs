<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\MakeupClassStatusNotification;
use App\Models\MakeUpClassRequest;

// TEMPORARY: Test notification system
Route::middleware(['auth'])->get('/test-notification-system', function () {
    /** @var User $user */
    $user = Auth::user();
    
    // Create a simple test notification directly
    try {
        // Get the latest makeup request or create a dummy one for testing
        $request = MakeUpClassRequest::first();
        if (!$request) {
            return response()->json([
                'error' => 'No makeup request found for testing'
            ]);
        }
        
        // Test direct notification with proper type casting
        $user->notify(new MakeupClassStatusNotification($request, 'test_message', 'This is a test notification'));
        
        // Refresh user and get unread count
        $freshUser = User::find($user->id);
        $unreadCount = $freshUser->unreadNotifications->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Test notification created successfully!',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'unread_count' => $unreadCount,
            'redirect_to' => '/notifications'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to create notification: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});