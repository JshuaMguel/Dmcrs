<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Notifications\InstantMakeupNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HeadRequestController extends Controller
{
    /**
     * Display all pending requests for Academic Head.
     */
    public function index(): View
    {
    $requests = MakeUpClassRequest::whereIn('status', ['pending', 'CHAIR_APPROVED'])->with(['faculty.department', 'subject.department', 'sectionRelation'])->orderByDesc('created_at')->get();
        return view('head.requests.index', compact('requests'));
    }

    /**
     * Show details for a specific request.
     */
    public function show($id): View
    {
        $request = MakeUpClassRequest::with(['faculty.department', 'subject.department', 'sectionRelation'])->findOrFail($id);
        return view('head.requests.show', compact('request'));
    }

    /**
     * Approve a make-up class request (final approval by Academic Head).
     */
    public function approve(Request $request, $id)
    {
        Log::info('HeadRequestController@approve called', [
            'request_id' => $id,
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role ?? 'unknown',
            'post_data' => $request->all()
        ]);

        try {
            $makeupRequest = MakeUpClassRequest::findOrFail($id);
            $head = Auth::user();
            $remarks = $request->input('remarks');

            // Check if request is already processed
            if ($makeupRequest->status === 'APPROVED' || $makeupRequest->status === 'HEAD_REJECTED') {
                return redirect()->route('head.requests.index')->with('info', 'This request has already been processed. Current status: ' . $makeupRequest->status);
            }

            Log::info('Academic Head approval started', [
                'request_id' => $id,
                'head_id' => $head->id,
                'current_status' => $makeupRequest->status
            ]);

            // Validate and map room name to room_id
            $roomName = $makeupRequest->room;
            $room = \App\Models\Room::firstOrCreate(['name' => $roomName]);
            $roomId = $room->id;

            // Insert into approvals table
            DB::table('approvals')->insert([
                'make_up_class_request_id' => $makeupRequest->id,
                'chair_id' => $head->id,
                'decision' => 'approved',
                'remarks' => $remarks,
                'created_at' => now(),
                'updated_at' => now(),
                'position' => 'Head',
                'status' => 'approved',
                'is_final' => true,
            ]);

        // Update request status
        $makeupRequest->status = 'APPROVED';
        $makeupRequest->head_remarks = $remarks;
        $makeupRequest->save();

        // Notify academic head of department chair recommendation
        $academicHeads = \App\Models\User::where('role', 'academic_head')->get();
        foreach ($academicHeads as $academicHead) {
            Log::info('Notifying academic head: ' . $academicHead->id . ' - ' . $academicHead->name . ' (' . $academicHead->email . ')');
            // Use instant notification for live environments to avoid queue issues
            if (app()->environment('production') || app()->environment('staging')) {
                $notification = new InstantMakeupNotification($makeupRequest, 'CHAIR_APPROVED', $remarks);
            } else {
                $notification = new \App\Notifications\MakeupClassStatusNotification($makeupRequest, 'CHAIR_APPROVED', $remarks);
            }
            Log::info('Notification data: ' . json_encode($notification->toArray($academicHead)));
            $academicHead->notify($notification);
        }

        // Create entry in schedules table (type = makeup)
        $dayOfWeek = \Carbon\Carbon::parse($makeupRequest->preferred_date)->format('l'); // Get day name (Monday, Tuesday, etc.)

        // Get faculty information for complete schedule data
        $faculty = $makeupRequest->faculty;

        // Format section properly
        $sectionText = 'Make-up Class';
        if ($makeupRequest->section && is_object($makeupRequest->section)) {
            $sectionText = $makeupRequest->section->year_level . '-' . $makeupRequest->section->section_name;
        }

        DB::table('schedules')->insert([
            'instructor_id' => $makeupRequest->faculty_id,
            'instructor_name' => $faculty ? $faculty->name : 'Unknown Instructor',
            'room' => $makeupRequest->room,
            'day_of_week' => $dayOfWeek,
            'time_start' => $makeupRequest->preferred_time,
            'time_end' => $makeupRequest->end_time,
            'subject_code' => $makeupRequest->subject,
            'subject_title' => $makeupRequest->subject_title ?: ('Make-up Class: ' . ($makeupRequest->subject ?? 'Unknown Subject')),
            'section' => $sectionText,
            'department_id' => $makeupRequest->department_id ?? ($faculty ? $faculty->department_id : 1),
            'semester' => $makeupRequest->semester ?? 'Current',
            'status' => 'APPROVED',
            'type' => 'MAKEUP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);        // Notify faculty
        $makeupRequest->notifyStatusChange('APPROVED', $remarks);
        // Notify students (if emails available)
        $studentEmails = [];
        $studentData = []; // Store complete student information
        
        if ($makeupRequest->student_list) {
            $path = storage_path('app/public/' . $makeupRequest->student_list);
            Log::info('Student list path: ' . $path);
            if (file_exists($path)) {
                Log::info('Student list file exists.');
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($extension === 'csv') {
                    Log::info('Parsing CSV file for student data.');
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
                }
                Log::info('Total students found: ' . count($studentEmails));
                Log::info('Student emails: ' . implode(', ', $studentEmails));
                Log::info('Student data parsed for ' . count($studentData) . ' students');
            } else {
                Log::info('Student list file does NOT exist.');
            }
        }

        // Send notifications to all students
        if (!empty($studentEmails)) {
            Log::info('Sending notifications to ' . count($studentEmails) . ' students');
            if (method_exists($makeupRequest, 'notifyStudents')) {
                $makeupRequest->notifyStudents($studentEmails, $studentData);
            }
        } else {
            Log::warning('No student emails found for notification');
        }

            Log::info('Academic Head approval completed successfully', ['request_id' => $id]);
            $studentCount = count($studentEmails);
            $message = 'Request approved and schedule created.';
            if ($studentCount > 0) {
                $message .= ' Email notifications sent to ' . $studentCount . ' students.';
            }
            return redirect()->route('head.requests.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Academic Head approval failed', [
                'request_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    /**
     * Reject a make-up class request (final rejection by Academic Head).
     */
    public function reject(Request $request, $id)
    {
        try {
            $makeupRequest = MakeUpClassRequest::findOrFail($id);
            $head = Auth::user();
            $remarks = $request->input('remarks');

            // Check if request is already processed
            if ($makeupRequest->status === 'APPROVED' || $makeupRequest->status === 'HEAD_REJECTED') {
                return redirect()->route('head.requests.index')->with('info', 'This request has already been processed. Current status: ' . $makeupRequest->status);
            }

            Log::info('Academic Head rejection started', [
                'request_id' => $id,
                'head_id' => $head->id,
                'current_status' => $makeupRequest->status
            ]);

            // Insert into approvals table
            DB::table('approvals')->insert([
                'make_up_class_request_id' => $makeupRequest->id,
                'chair_id' => $head->id,
                'decision' => 'rejected',
                'remarks' => $remarks,
                'created_at' => now(),
                'updated_at' => now(),
                'position' => 'Head',
                'status' => 'rejected',
                'is_final' => true,
            ]);

            // Update request status
            $makeupRequest->status = 'HEAD_REJECTED';
            $makeupRequest->head_remarks = $remarks;
            $makeupRequest->save();

            // Notify faculty
            $makeupRequest->notifyStatusChange('HEAD_REJECTED', $remarks);
            // Notify students (if emails available)
            if (method_exists($makeupRequest, 'notifyStudents')) {
                $makeupRequest->notifyStudents([]); // Pass actual emails if available
            }

            Log::info('Academic Head rejection completed successfully', ['request_id' => $id]);
            return redirect()->route('head.requests.index')->with('success', 'Request rejected.');
        } catch (\Exception $e) {
            Log::error('Academic Head rejection failed', [
                'request_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }
}
