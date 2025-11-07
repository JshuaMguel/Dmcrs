<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\MakeUpClassRequest;

class DatabaseOnlyMakeupNotification extends Notification
{
    use Queueable;

    protected $request;
    protected string $status;
    protected ?string $remarks;

    public function __construct(MakeUpClassRequest $request, string $status, ?string $remarks = null)
    {
        $this->request = $request;
        $this->status = $status;
        $this->remarks = $remarks;
    }

    /**
     * Get the notification's delivery channels.
     * Only database channel to avoid email sending issues
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        $title = match($this->status) {
            'submitted' => 'Makeup Class Request Submitted',
            'CHAIR_APPROVED' => 'Request Approved by Chair',
            'CHAIR_REJECTED' => 'Request Rejected by Chair',  
            'APPROVED' => 'Request Approved by Head',
            'HEAD_REJECTED' => 'Request Rejected by Head',
            'forwarded_to_head' => 'Request Forwarded to Head',
            'approved_by_head' => 'Request Approved by Head',
            'confirmed' => 'Student Confirmed Attendance',
            'declined' => 'Student Declined Attendance',
            default => 'Makeup Class Request Updated'
        };

        $message = "Tracking: {$this->request->tracking_number}";
        if ($this->remarks) {
            $message .= " - Remarks: {$this->remarks}";
        }

        return [
            'title' => $title,
            'message' => $message,
            'request_id' => $this->request->id,
            'tracking_number' => $this->request->tracking_number,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'created_at' => now(),
        ];
    }
}