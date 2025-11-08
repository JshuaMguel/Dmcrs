<?php

namespace App\Channels;

use App\Services\BrevoApiService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BrevoApiChannel
{
    protected $brevoService;

    public function __construct(BrevoApiService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        // Check if notification has toBrevoApi method
        if (method_exists($notification, 'toBrevoApi')) {
            try {
                return call_user_func([$notification, 'toBrevoApi'], $notifiable);
            } catch (\Exception $e) {
                Log::error('Brevo API Channel failed: ' . $e->getMessage());
                return false;
            }
        }

        Log::warning('Notification does not have toBrevoApi method: ' . get_class($notification));
        return false;
    }
}