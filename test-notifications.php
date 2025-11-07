<?php

// Simple test script to check notification system
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Testing DMCRS Notification System\n";
echo "===================================\n";

try {
    // Check if notifications table exists
    $tableExists = Schema::hasTable('notifications');
    echo "📋 Notifications table exists: " . ($tableExists ? '✅ YES' : '❌ NO') . "\n";

    // Check if we have users
    $userCount = \App\Models\User::count();
    echo "👤 Total users: {$userCount}\n";

    // Check faculty users
    $facultyCount = \App\Models\User::where('role', 'faculty')->count();
    echo "🎓 Faculty users: {$facultyCount}\n";

    // Check makeup requests
    $requestCount = \App\Models\MakeUpClassRequest::count();
    echo "📝 Total makeup requests: {$requestCount}\n";

    // Check notifications
    $notificationCount = DB::table('notifications')->count();
    echo "🔔 Total notifications in DB: {$notificationCount}\n";

    // Get a sample faculty user and check their notifications
    $faculty = \App\Models\User::where('role', 'faculty')->first();
    if ($faculty) {
        echo "\n👨‍🏫 Sample Faculty: {$faculty->name} ({$faculty->email})\n";
        echo "   🔔 Total notifications: " . $faculty->notifications->count() . "\n";
        echo "   🆕 Unread notifications: " . $faculty->unreadNotifications->count() . "\n";

        // Show recent notifications
        $recent = $faculty->notifications->take(3);
        echo "   📋 Recent notifications:\n";
        foreach ($recent as $notif) {
            $title = $notif->data['title'] ?? 'No title';
            $created = $notif->created_at->diffForHumans();
            $read = $notif->read_at ? '(Read)' : '(Unread)';
            echo "      • {$title} - {$created} {$read}\n";
        }
    } else {
        echo "❌ No faculty user found\n";
    }

    echo "\n💾 Database Connection: " . config('database.default') . "\n";
    echo "🌐 Environment: " . config('app.env') . "\n";
    echo "📧 Mail Driver: " . config('mail.default') . "\n";

    echo "\n✅ Diagnostic complete!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}