<?php

// Simple database admin interface
// Access via: your-app.onrender.com/adminer.php

// Only allow in development or with proper auth
if (env('APP_ENV') === 'production') {
    // Simple auth check
    session_start();
    if (!isset($_SESSION['admin_authenticated'])) {
        if (isset($_POST['admin_password']) && $_POST['admin_password'] === env('ADMIN_DB_PASSWORD', 'your-secret-password')) {
            $_SESSION['admin_authenticated'] = true;
        } else {
            ?>
            <!DOCTYPE html>
            <html>
            <head><title>Database Admin - Authentication Required</title></head>
            <body style="font-family: Arial; padding: 50px; background: #f5f5f5;">
                <div style="max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h2 style="color: #333; text-align: center;">🔒 Database Admin Access</h2>
                    <form method="post" style="margin-top: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Admin Password:</label>
                        <input type="password" name="admin_password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 15px;" required>
                        <button type="submit" style="width: 100%; padding: 12px; background: #007cba; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">Access Database</button>
                    </form>
                    <p style="margin-top: 15px; text-align: center; color: #666; font-size: 12px;">
                        Secure database management for DMCRS
                    </p>
                </div>
            </body>
            </html>
            <?php
            exit;
        }
    }
}

// Download and include Adminer
$adminerUrl = 'https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php';
$adminerPath = __DIR__ . '/adminer-core.php';

if (!file_exists($adminerPath)) {
    $adminerContent = file_get_contents($adminerUrl);
    if ($adminerContent) {
        file_put_contents($adminerPath, $adminerContent);
    } else {
        die('Could not download Adminer. Please check your internet connection.');
    }
}

// Include Adminer
include $adminerPath;