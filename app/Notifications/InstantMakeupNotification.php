<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\MakeUpClassRequest;

/**
 * Non-queued version of MakeupClassStatusNotification
 * For immediate processing in live environments
 */
class InstantMakeupNotification extends Notification
{
    use Queueable;

    protected $request;
    protected string $status;
    protected ?string $remarks;
    protected $student;

    public function __construct(MakeUpClassRequest $request, string $status, ?string $remarks = null, $student = null)
    {
        $this->request = $request;
        $this->status = $status;
        $this->remarks = $remarks;
        $this->student = $student;
    }

    public function via(object $notifiable): array
    {
        // Database only, no queue processing
        return ['database'];
    }

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
            'live_test' => 'Live Environment Test',
            default => "Makeup Class Request " . ucfirst($this->status)
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
            'confirmed' => "Student {$this->student->name} has confirmed their attendance for the {$subjectCode} makeup class.",
            'declined' => "Student {$this->student->name} has declined attendance for the {$subjectCode} makeup class." . ($this->remarks ? " Reason: {$this->remarks}" : ""),
            'live_test' => "Live environment test notification for {$subjectCode} - working properly!",
            default => "Your makeup class request for {$subjectCode} - {$subjectTitle} status has been updated to: {$this->status}."
        };

        return [
            'title' => $title,
            'message' => $message,
            'subject_code' => $subjectCode,
            'subject_title' => $subjectTitle,
            'date' => $this->request->preferred_date instanceof \Carbon\Carbon
                ? $this->request->preferred_date->format('M d, Y')
                : $this->request->preferred_date,
            'time' => $this->request->preferred_time,
            'remarks' => $this->remarks,
            'request_id' => $this->request->id,
            'tracking_number' => $this->request->tracking_number,
        ];
    }
}