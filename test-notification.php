<?php
require 'vendor/autoload.php';

// Laravel application bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MakeUpClassRequest;
use App\Notifications\MakeupClassStatusNotification;
use App\Notifications\SimpleMakeupNotification;

echo "🔔 DMCRS Notification System Debug Test\n";
echo "=====================================\n\n";

try {
    // 1. Check database connection
    echo "1. Database Connection: ";
    $pdo = DB::connection()->getPdo();
    echo "✅ Connected\n\n";
    
    // 2. Check notifications table
    echo "2. Notifications Table Check:\n";
    try {
        DB::table('notifications')->limit(1)->get();
        echo "   ✅ Notifications table exists and accessible\n";
    } catch (Exception $e) {
        echo "   ❌ Notifications table issue: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 3. Count current notifications
    echo "3. Current Notifications Count:\n";
    $totalNotifs = DB::table('notifications')->count();
    echo "   Total notifications: {$totalNotifs}\n";
    
    $unreadNotifs = DB::table('notifications')->whereNull('read_at')->count();
    echo "   Unread notifications: {$unreadNotifs}\n\n";
    
    // 4. Show recent notifications
    echo "4. Recent Notifications (last 5):\n";
    $recent = DB::table('notifications')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($recent as $notif) {
        $data = json_decode($notif->data, true);
        echo "   📧 {$notif->type}\n";
        echo "      To User ID: {$notif->notifiable_id}\n";
        echo "      Title: " . ($data['title'] ?? 'No title') . "\n";
        echo "      Created: {$notif->created_at}\n";
        echo "      Read: " . ($notif->read_at ? $notif->read_at : 'Unread') . "\n\n";
    }
    
    // 5. Test creating a notification
    echo "5. Testing Notification Creation:\n";
    
    // Find a user to test with
    $testUser = User::where('role', 'faculty')->first();
    if (!$testUser) {
        $testUser = User::first();
    }
    
    if ($testUser) {
        echo "   Testing with user: {$testUser->name} (ID: {$testUser->id})\n";
        
        // Create test notification
        try {
            $testUser->notify(new SimpleMakeupNotification(
                'Test Notification',
                'This is a test notification to check if database storage is working.',
                999
            ));
            echo "   ✅ Test notification created successfully\n";
            
            // Check if it was stored
            $latestNotif = DB::table('notifications')
                ->where('notifiable_id', $testUser->id)
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($latestNotif) {
                $data = json_decode($latestNotif->data, true);
                echo "   ✅ Notification stored in database:\n";
                echo "      Title: " . ($data['title'] ?? 'No title') . "\n";
                echo "      Message: " . ($data['message'] ?? 'No message') . "\n";
            } else {
                echo "   ❌ Notification NOT found in database\n";
            }
            
        } catch (Exception $e) {
            echo "   ❌ Error creating notification: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ❌ No users found for testing\n";
    }
    
    echo "\n6. User Notification Counts by Role:\n";
    $users = User::select('id', 'name', 'role')->get();
    foreach ($users as $user) {
        $count = $user->notifications()->count();
        $unread = $user->unreadNotifications()->count();
        echo "   {$user->name} ({$user->role}): {$count} total, {$unread} unread\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔔 Test completed!\n";