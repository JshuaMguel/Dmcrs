<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoApiService
{
    private $apiKey;
    private $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->apiKey = config('mail.brevo_api_key');
    }

    /**
     * Send email using Brevo API
     */
    public function sendEmail($to, $subject, $htmlContent, $textContent = null, $fromName = null, $fromEmail = null)
    {
        try {
            $fromName = $fromName ?: config('mail.from.name');
            $fromEmail = $fromEmail ?: config('mail.from.address');

            $payload = [
                'sender' => [
                    'name' => $fromName,
                    'email' => $fromEmail
                ],
                'to' => [
                    [
                        'email' => $to,
                        'name' => $to
                    ]
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'textContent' => $textContent ?: strip_tags($htmlContent)
            ];

            Log::info('Sending email via Brevo API', [
                'to' => $to,
                'subject' => $subject
            ]);

            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/smtp/email', $payload);

            if ($response->successful()) {
                Log::info('Email sent successfully via Brevo API', [
                    'messageId' => $response->json('messageId')
                ]);
                return true;
            } else {
                Log::error('Brevo API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Brevo API exception', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }

    /**
     * Send notification email
     */
    public function sendNotificationEmail($user, $subject, $message, $actionUrl = null)
    {
        $htmlContent = view('emails.notification-template', [
            'subject' => $subject,
            'message' => $message,
            'actionUrl' => $actionUrl,
            'user' => $user
        ])->render();

        return $this->sendEmail(
            $user->email,
            $subject,
            $htmlContent,
            null,
            'USTP Balubal Campus - DMCRS',
            'ustpbalubal.dmcrs@gmail.com'
        );
    }

    /**
     * Send student makeup class email
     */
    public function sendStudentMakeupEmail($email, $makeupRequest, $studentInfo = [])
    {
        $subject = 'Makeup Class Scheduled - ' . $makeupRequest->subject;
        
        $htmlContent = view('emails.makeup-class-student-notification', [
            'makeupRequest' => $makeupRequest,
            'email' => $email,
            'studentInfo' => $studentInfo
        ])->render();

        return $this->sendEmail(
            $email,
            $subject,
            $htmlContent,
            null,
            'USTP Balubal Campus - DMCRS',
            'ustpbalubal.dmcrs@gmail.com'
        );
    }
}
