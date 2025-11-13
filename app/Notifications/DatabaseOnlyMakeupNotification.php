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
            'updated' => 'Makeup Class Request Updated',
            'CHAIR_APPROVED' => 'Request Approved by Chair',
            'CHAIR_REJECTED' => 'Request Rejected by Chair',
            'APPROVED' => 'Request Approved',
            'HEAD_REJECTED' => 'Request Rejected by Head',
            'new_request' => 'New Makeup Class Request',
            'forwarded_to_head' => 'Request Forwarded to Academic Head',
            'approved_by_head' => 'Request Approved by Academic Head',
            'new_request_submitted' => 'New Request in System',
            'confirmed' => 'Student Confirmed Attendance',
            'declined' => 'Student Declined Attendance',
            default => 'Makeup Class Request Updated'
        };

        $subjectCode = $this->request->subject && $this->request->subject instanceof \App\Models\Subject
            ? $this->request->subject->subject_code
            : $this->request->subject;
        $subjectTitle = $this->request->subject && $this->request->subject instanceof \App\Models\Subject
            ? $this->request->subject->subject_title
            : ($this->request->subject_title ?? '');

        $message = match($this->status) {
            'submitted' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been submitted successfully.",
            'updated' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been updated.",
            'CHAIR_APPROVED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been approved by the Department Chair and forwarded to the Academic Head.",
            'CHAIR_REJECTED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been rejected by the Department Chair.",
            'APPROVED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been approved by the Academic Head.",
            'HEAD_REJECTED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been rejected by the Academic Head.",
            'new_request' => "A new makeup class request for {$subjectCode} - {$subjectTitle} has been submitted by {$this->request->faculty->name}.",
            'forwarded_to_head' => "You have successfully forwarded the {$subjectCode} - {$subjectTitle} request to the Academic Head for final approval.",
            'approved_by_head' => "The {$subjectCode} - {$subjectTitle} request you recommended has been approved by the Academic Head.",
            'new_request_submitted' => "A new makeup class request for {$subjectCode} - {$subjectTitle} has been submitted by {$this->request->faculty->name}.",
            default => "Your makeup class request for {$subjectCode} - {$subjectTitle} status has been updated."
        };

        if ($this->remarks) {
            $message .= " Remarks: {$this->remarks}";
        }

        return [
            'title' => $title,
            'message' => $message,
            'subject' => $subjectCode . ($subjectTitle ? ' - ' . $subjectTitle : ''), // Combined subject for view compatibility
            'subject_code' => $subjectCode,
            'subject_title' => $subjectTitle,
            'date' => $this->request->preferred_date instanceof \Carbon\Carbon
                ? $this->request->preferred_date->format('M d, Y')
                : $this->request->preferred_date,
            'time' => $this->request->preferred_time . ($this->request->end_time ? " - {$this->request->end_time}" : ""),
            'remarks' => $this->remarks,
            'request_id' => $this->request->id,
            'tracking_number' => $this->request->tracking_number,
        ];
    }
}