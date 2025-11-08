<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "🔔 DMCRS Authentication & Notification Debug\n";
echo "==========================================\n\n";

// Simulate being logged in as different users
$users = User::all();
foreach ($users as $user) {
    echo "👤 User: {$user->name} ({$user->role})\n";
    echo "   User ID: {$user->id}\n";
    
    // Check notifications for this user
    $totalNotifs = DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->count();
    
    $unreadNotifs = DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->whereNull('read_at')
        ->count();
    
    echo "   Total notifications: {$totalNotifs}\n";
    echo "   Unread notifications: {$unreadNotifs}\n";
    
    if ($unreadNotifs > 0) {
        echo "   ✅ SHOULD show red badge with number: {$unreadNotifs}\n";
    } else {
        echo "   ❌ No unread notifications - no badge should show\n";
    }
    
    echo "\n";
}

// Let's also check what happens with Auth::user()
echo "🔐 Authentication Test:\n";
try {
    if (Auth::check()) {
        $currentUser = Auth::user();
        echo "   Currently logged in as: {$currentUser->name}\n";
        $unread = $currentUser->unreadNotifications->count();
        echo "   Their unread count: {$unread}\n";
    } else {
        echo "   ❌ No user currently authenticated\n";
        echo "   This might be why the bell shows no notifications\n";
    }
} catch (Exception $e) {
    echo "   ❌ Auth error: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";