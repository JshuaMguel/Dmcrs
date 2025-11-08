<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Test email sending directly
Route::get('/test-admin-email', function() {
    try {
        Log::info('=== STARTING DIRECT EMAIL TEST ===');
        
        // Create fake user data
        $user = (object) [
            'id' => 999,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'faculty'
        ];
        $plainPassword = 'testpass123';
        
        Log::info('Test user created', ['user' => $user]);
        
        // Initialize Brevo service
        Log::info('Initializing BrevoApiService...');
        $brevoService = app(\App\Services\BrevoApiService::class);
        Log::info('BrevoApiService initialized successfully');
        
        // Render template
        Log::info('Rendering email template...');
        if (!view()->exists('emails.new-user-account')) {
            Log::error('Template emails.new-user-account does not exist');
            return response()->json(['error' => 'Template not found']);
        }
        
        $htmlContent = view('emails.new-user-account', [
            'user' => $user,
            'password' => $plainPassword
        ])->render();
        
        Log::info('Template rendered successfully', ['content_length' => strlen($htmlContent)]);
        
        // Send email
        Log::info('Sending email via Brevo API...');
        $result = $brevoService->sendEmail(
            'test@example.com',
            'Test DMCRS Account Details',
            $htmlContent,
            null,
            'USTP Balubal Campus - DMCRS',
            'ustpbalubal.dmcrs@gmail.com'
        );
        
        Log::info('Email send result', ['success' => $result]);
        
        return response()->json([
            'success' => $result,
            'message' => $result ? 'Email sent successfully!' : 'Email failed to send',
            'template_length' => strlen($htmlContent)
        ]);
        
    } catch (\Exception $e) {
        Log::error('TEST EMAIL EXCEPTION', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});