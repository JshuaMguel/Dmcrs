<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentMakeupClassNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $confirmUrl = url('/student/makeup-class/confirm?request_id=' . $notifiable->id);
        $declineUrl = url('/student/makeup-class/decline?request_id=' . $notifiable->id);
        return (new MailMessage)
            ->subject('Make-Up Class Confirmation Required')
            ->line('You have a scheduled make-up class. Please confirm your attendance.')
            ->action('Confirm Attendance', $confirmUrl)
            ->line('If you cannot attend, please click below to decline and provide a reason:')
            ->action('Decline Attendance', $declineUrl)
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
