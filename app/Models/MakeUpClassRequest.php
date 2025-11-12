<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeUpClassRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'subject_id',
        'section_id',
        'subject',
        'subject_title',
        'section',
        'room',
        'reason',
        'preferred_date',
        'preferred_time',
        'end_time',
        'status',
        'attachment',
        'student_list',
        'proof_of_conduct',
        'tracking_number',
        'chair_remarks',
        'head_remarks',
        'department_id',
        'semester',
        'submitted_to_chair_at',
    ];

    protected $casts = [
        'proof_of_conduct' => 'array', // Automatically convert JSON to array
    ];

    /**
     * Notify academic head about status change
     */
    public function notifyAcademicHead(string $status, ?string $remarks = null)
    {
        $head = \App\Models\User::where('role', 'academic_head')->first();
        if ($head) {
            // Use instant notification for live environments to avoid queue issues
            if (app()->environment('production') || app()->environment('staging')) {
                $head->notify(new \App\Notifications\InstantMakeupNotification($this, $status, $remarks));
            } else {
                $head->notify(new \App\Notifications\MakeupClassStatusNotification($this, $status, $remarks));
            }
        }
    }

    // Relationship with Faculty (User)
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    // Relationship with Subject
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    // Relationship with Section
    public function sectionRelation()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }

    // Relationship with Confirmations
    public function confirmations()
    {
        return $this->hasMany(MakeUpClassConfirmation::class, 'make_up_class_request_id');
    }

    /**
     * Notify the faculty about status change
     */
    public function notifyStatusChange(string $status, ?string $remarks = null)
    {
        // Use instant notification for live environments to avoid queue issues
        if (app()->environment('production') || app()->environment('staging')) {
            $this->faculty->notify(new \App\Notifications\InstantMakeupNotification($this, $status, $remarks));
        } else {
            $this->faculty->notify(new \App\Notifications\MakeupClassStatusNotification($this, $status, $remarks));
        }
    }

        /**
     * Notify students with makeup class details
     */
    public function notifyStudents(array $studentEmails, array $studentData = [])
    {
        $successCount = 0;
        $failedCount = 0;
        
        // Use Brevo API instead of SMTP
        $brevoService = new \App\Services\BrevoApiService();

        foreach ($studentEmails as $email) {
            try {
                Log::info('Sending makeup class notification via Brevo API to: ' . $email);
                
                // Pass complete student data to API service
                $studentInfo = $studentData[$email] ?? ['email' => $email, 'student_id' => null, 'name' => null];
                
                $success = $brevoService->sendStudentMakeupEmail($email, $this, $studentInfo);
                
                if ($success) {
                    $successCount++;
                    Log::info('Successfully sent email via API to: ' . $email);
                } else {
                    $failedCount++;
                    Log::error('Failed to send email via API to: ' . $email);
                }
            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Exception sending email to: ' . $email . ' - Error: ' . $e->getMessage());
            }
        }

        Log::info('Email API summary: ' . $successCount . ' successful, ' . $failedCount . ' failed out of ' . count($studentEmails) . ' total students');
    }

    /**
     * Notify department chair about new request
     */
    public function notifyDepartmentChair()
    {
        $this->loadMissing('faculty');

        $departmentId = optional($this->faculty)->department_id;

        $chairsQuery = \App\Models\User::where('role', 'department_chair');

        if ($departmentId) {
            $chairsQuery->where('department_id', $departmentId);
        }

        $departmentChairs = $chairsQuery->get();

        foreach ($departmentChairs as $departmentChair) {
            // Use instant notification for live environments to avoid queue issues
            if (app()->environment('production') || app()->environment('staging')) {
                $departmentChair->notify(new \App\Notifications\InstantMakeupNotification($this, 'new_request'));
            } else {
                $departmentChair->notify(new \App\Notifications\MakeupClassStatusNotification($this, 'new_request'));
            }
        }

        // Also notify academic head about new request in the system
        $academicHead = \App\Models\User::where('role', 'academic_head')->first();
        if ($academicHead) {
            // Use instant notification for live environments to avoid queue issues
            if (app()->environment('production') || app()->environment('staging')) {
                $academicHead->notify(new \App\Notifications\InstantMakeupNotification($this, 'new_request_submitted'));
            } else {
                $academicHead->notify(new \App\Notifications\MakeupClassStatusNotification($this, 'new_request_submitted'));
            }
        }
    }

    /**
     * Parse student list CSV file and extract student emails and data
     * Returns array with 'emails' and 'data' keys
     */
    public function parseStudentListFromCSV()
    {
        $studentEmails = [];
        $studentData = [];

        if (!$this->student_list) {
            return ['emails' => $studentEmails, 'data' => $studentData];
        }

        $path = storage_path('app/public/' . $this->student_list);
        if (!file_exists($path)) {
            Log::warning('Student list file does not exist: ' . $path);
            return ['emails' => $studentEmails, 'data' => $studentData];
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension !== 'csv') {
            Log::warning('Student list is not a CSV file: ' . $extension);
            return ['emails' => $studentEmails, 'data' => $studentData];
        }

        try {
            $file = fopen($path, 'r');
            $header = fgetcsv($file); // Get header row

            // Find column indices
            $emailColumnIndex = -1;
            $studentIdColumnIndex = -1;
            $nameColumnIndex = -1;

            if ($header) {
                foreach ($header as $index => $columnName) {
                    $columnName = strtolower(trim($columnName));
                    if ($columnName === 'email') {
                        $emailColumnIndex = $index;
                    } elseif (in_array($columnName, ['student id', 'student_id', 'id'])) {
                        $studentIdColumnIndex = $index;
                    } elseif (in_array($columnName, ['name', 'student name', 'student_name', 'full name'])) {
                        $nameColumnIndex = $index;
                    }
                }
            }

            // Parse data rows
            while (($row = fgetcsv($file)) !== false) {
                if ($emailColumnIndex >= 0 && isset($row[$emailColumnIndex])) {
                    $email = trim($row[$emailColumnIndex]);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $studentEmails[] = $email;

                        // Store complete student data
                        $studentData[$email] = [
                            'email' => $email,
                            'student_id' => $studentIdColumnIndex >= 0 && isset($row[$studentIdColumnIndex]) ? trim($row[$studentIdColumnIndex]) : null,
                            'name' => $nameColumnIndex >= 0 && isset($row[$nameColumnIndex]) ? trim($row[$nameColumnIndex]) : null
                        ];
                    }
                } else {
                    // Fallback: scan all columns for valid emails
                    foreach ($row as $cellIndex => $cell) {
                        $email = trim($cell);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $studentEmails[] = $email;

                            // For fallback, try to get other data from same row
                            $studentData[$email] = [
                                'email' => $email,
                                'student_id' => $studentIdColumnIndex >= 0 && isset($row[$studentIdColumnIndex]) ? trim($row[$studentIdColumnIndex]) : null,
                                'name' => $nameColumnIndex >= 0 && isset($row[$nameColumnIndex]) ? trim($row[$nameColumnIndex]) : null
                            ];
                        }
                    }
                }
            }
            fclose($file);

            Log::info('Parsed student list CSV', [
                'request_id' => $this->id,
                'total_students' => count($studentEmails)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to parse student list CSV', [
                'request_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }

        return ['emails' => $studentEmails, 'data' => $studentData];
    }

    /**
     * Check if request has minimum confirmed students
     */
    public function hasMinimumConfirmedStudents($minimum = 1)
    {
        $confirmedCount = $this->confirmations()
            ->where('status', 'confirmed')
            ->count();

        return $confirmedCount >= $minimum;
    }

    /**
     * Check if request is ready to be submitted to Department Chair
     * (has minimum confirmed students and status is still pending)
     */
    public function isReadyForSubmission()
    {
        return $this->status === 'pending' && $this->hasMinimumConfirmedStudents(1);
    }
}
