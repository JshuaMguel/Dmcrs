<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MakeUpClassRequest;

/**
 * Simple database-only notification for production reliability
 */
class SimpleMakeupNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $requestId;

    public function __construct($title, $message, $requestId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->requestId = $requestId;
    }

    public function via(object $notifiable): array
    {
        // Database only - no email dependencies
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'request_id' => $this->requestId,
            'type' => 'simple',
            'created_at' => now()->toDateTimeString()
        ];
    }
}