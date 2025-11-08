<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\MakeUpClassRequest;

class MakeupClassStatusNotification extends Notification implements ShouldQueue
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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = match($this->status) {
            'submitted' => 'Submitted',
            'updated' => 'Updated',
            'CHAIR_APPROVED' => 'Approved by Chair',
            'CHAIR_REJECTED' => 'Rejected by Chair',
            'APPROVED' => 'Approved',
            'HEAD_REJECTED' => 'Rejected by Head',
            'new_request' => 'New Request Submitted',
            'forwarded_to_head' => 'Forwarded to Academic Head',
            'approved_by_head' => 'Approved by Academic Head',
            'new_request_submitted' => 'New Request in System',
            'confirmed' => 'Student Confirmed Attendance',
            'declined' => 'Student Declined Attendance',
            default => ucfirst($this->status)
        };

        $message = match($this->status) {
            'submitted' => "Your makeup class request has been submitted successfully.",
            'updated' => "Your makeup class request has been updated.",
            'CHAIR_APPROVED' => "Your makeup class request has been approved by the Department Chair and forwarded to the Academic Head.",
            'CHAIR_REJECTED' => "Your makeup class request has been rejected by the Department Chair.",
            'APPROVED' => "Your makeup class request has been approved by the Academic Head.",
            'HEAD_REJECTED' => "Your makeup class request has been rejected by the Academic Head.",
            'new_request' => "A new makeup class request has been submitted by {$this->request->faculty->name}.",
            'forwarded_to_head' => "You have successfully forwarded the request to the Academic Head for final approval.",
            'approved_by_head' => "The makeup class request you recommended has been approved by the Academic Head.",
            'new_request_submitted' => "A new makeup class request has been submitted and is being reviewed by the Department Chair.",
            'confirmed' => "Student {$this->student->name} has confirmed their attendance for the makeup class.",
            'declined' => "Student {$this->student->name} has declined attendance for the makeup class." . ($this->remarks ? " Reason: {$this->remarks}" : ""),
            default => "Your makeup class request status has been updated to: {$this->status}."
        };

        $actionUrl = match($notifiable->role) {
            'faculty' => url('/faculty/makeup-requests'),
            'department_chair' => url('/department/requests'),
            'academic_head' => url('/academic/dashboard'),
            default => url('/dashboard')
        };

        $actionText = match($notifiable->role) {
            'faculty' => 'View My Requests',
            'department_chair' => 'Review Requests',
            'academic_head' => 'View Dashboard',
            default => 'View Dashboard'
        };

        $subjectCode = $this->request->subject && $this->request->subject instanceof \App\Models\Subject
            ? $this->request->subject->subject_code
            : $this->request->subject;
        $subjectTitle = $this->request->subject && $this->request->subject instanceof \App\Models\Subject
            ? $this->request->subject->subject_title
            : ($this->request->subject_title ?? '');
        return (new MailMessage)
            ->subject("Makeup Class Request - {$statusText}")
            ->greeting("Hello {$notifiable->name},")
            ->line($message)
            ->line("Subject: {$subjectCode} - {$subjectTitle}")
            ->line("Room: {$this->request->room}")
            ->line("Date: " . ($this->request->preferred_date instanceof \Carbon\Carbon
                ? $this->request->preferred_date->format('M d, Y')
                : $this->request->preferred_date))
            ->line("Time: {$this->request->preferred_time}")
            ->when($this->remarks, fn($mail) => $mail->line("Remarks: {$this->remarks}"))
            ->action($actionText, $actionUrl)
            ->salutation('Best regards, DMCRS System');
    }

    public function toArray(object $notifiable): array
    {
        Log::info('Creating database notification', [
            'status' => $this->status,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'environment' => app()->environment()
        ]);
        
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
            default => "Makeup Class Request " . ucfirst($this->status)
        };

        $subjectCode = $this->request->subject && $this->request->subject instanceof \App\Models\Subject
            ? $this->request->subject->subject_code
            : $this->request->subject;
        $subjectTitle = $this->request->subject && $this->request->subject instanceof \App\Models\Subject
            ? $this->request->subject->subject_title
            : ($this->request->subject_title ?? '');
        // Custom message per role for CHAIR_APPROVED
        if ($this->status === 'CHAIR_APPROVED') {
            if (isset($notifiable->role) && $notifiable->role === 'academic_head') {
                $message = "A new makeup class request for {$subjectCode} - {$subjectTitle} has been recommended by the Department Chair and requires your review.";
            } elseif (isset($notifiable->role) && $notifiable->role === 'faculty') {
                $message = "Your makeup class request for {$subjectCode} - {$subjectTitle} has been approved by the Department Chair and forwarded to the Academic Head.";
            } else {
                $message = "A makeup class request for {$subjectCode} - {$subjectTitle} has been approved by the Department Chair.";
            }
        } else {
            $message = match($this->status) {
                'submitted' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been submitted successfully.",
                'updated' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been updated.",
                'CHAIR_REJECTED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been rejected by the Department Chair.",
                'APPROVED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been approved.",
                'HEAD_REJECTED' => "Your makeup class request for {$subjectCode} - {$subjectTitle} has been rejected by the Academic Head.",
                'new_request' => "A new makeup class request has been submitted by {$this->request->faculty->name} for {$subjectCode} - {$subjectTitle}.",
                'forwarded_to_head' => "You have successfully forwarded the {$subjectCode} - {$subjectTitle} request to the Academic Head for final approval.",
                'approved_by_head' => "The {$subjectCode} - {$subjectTitle} request you recommended has been approved by the Academic Head.",
                'new_request_submitted' => "A new makeup class request for {$subjectCode} - {$subjectTitle} has been submitted by {$this->request->faculty->name}.",
                default => "Your makeup class request for {$subjectCode} - {$subjectTitle} status has been updated to: {$this->status}."
            };
        }

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
