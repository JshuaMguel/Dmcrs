<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\MakeupClassStatusNotification;
use App\Notifications\InstantMakeupNotification;
use App\Models\MakeUpClassRequest;

// LIVE ENVIRONMENT DEBUG ROUTE
Route::middleware(['auth'])->get('/debug-live-notifications', function () {
    /** @var User $user */
    $user = Auth::user();
    
    try {
        // 1. Check database connection
        $dbConnection = DB::connection()->getPdo() ? 'Connected' : 'Failed';
        
        // 2. Check notifications table exists
        $notificationsTable = DB::getSchemaBuilder()->hasTable('notifications') ? 'Exists' : 'Missing';
        
        // 3. Check current notifications in database
        $totalNotifs = DB::table('notifications')->count();
        $userNotifs = DB::table('notifications')->where('notifiable_id', $user->id)->count();
        $unreadNotifs = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
            
        // 4. Check recent notifications for this user
        $recentNotifs = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // 5. Test creating a live notification
        $testNotificationCreated = false;
        $testError = null;
        
        try {
            $request = MakeUpClassRequest::first();
            if ($request) {
                // Test both notification types
                $user->notify(new InstantMakeupNotification($request, 'instant_test', 'Instant notification test (no queue)'));
                $user->notify(new MakeupClassStatusNotification($request, 'queued_test', 'Queued notification test (requires queue worker)'));
                $testNotificationCreated = true;
                
                // Check if it was actually created
                sleep(1); // Give it a moment
                $afterCreate = DB::table('notifications')
                    ->where('notifiable_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
            }
        } catch (\Exception $e) {
            $testError = $e->getMessage();
        }
        
        // 6. Environment checks
        $environment = app()->environment();
        $queueDriver = config('queue.default');
        $cacheDriver = config('cache.default');
        
        return response()->json([
            'environment' => $environment,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ],
            'database' => [
                'connection' => $dbConnection,
                'notifications_table' => $notificationsTable,
                'total_notifications' => $totalNotifs,
                'user_notifications' => $userNotifs,
                'unread_notifications' => $unreadNotifs
            ],
            'config' => [
                'queue_driver' => $queueDriver,
                'cache_driver' => $cacheDriver,
                'app_env' => $environment
            ],
            'test_notification' => [
                'created' => $testNotificationCreated,
                'error' => $testError,
                'unread_after_test' => $afterCreate ?? null
            ],
            'recent_notifications' => $recentNotifs->map(function($notif) {
                $data = json_decode($notif->data, true);
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'title' => $data['title'] ?? 'No title',
                    'created_at' => $notif->created_at,
                    'read_at' => $notif->read_at
                ];
            }),
            'debug_info' => [
                'timestamp' => now()->toDateTimeString(),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version()
            ]
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Debug failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'environment' => app()->environment()
        ]);
    }
});