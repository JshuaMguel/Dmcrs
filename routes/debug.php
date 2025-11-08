<?php

use Illuminate\Support\Facades\Route;

// Debug route to check configuration - REMOVE IN PRODUCTION
Route::get('/debug/config', function() {
    if (app()->environment('production')) {
        return response()->json([
            'mail_mailer' => config('mail.default'),
            'brevo_api_key_exists' => !empty(config('mail.brevo_api_key')),
            'brevo_api_key_length' => strlen(config('mail.brevo_api_key')),
            'environment' => app()->environment(),
        ]);
    }
    return 'Debug disabled in production';
});

// Test email route - REMOVE IN PRODUCTION  
Route::get('/debug/test-email', function() {
    if (app()->environment('production')) {
        try {
            $brevoService = new \App\Services\BrevoApiService();
            $result = $brevoService->sendEmail(
                'test@example.com',
                'Test Email from DMCRS',
                '<h1>Test Email</h1><p>If you receive this, the Brevo API is working!</p>'
            );
            
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Email sent successfully!' : 'Email failed to send'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    return 'Debug disabled in production';
});