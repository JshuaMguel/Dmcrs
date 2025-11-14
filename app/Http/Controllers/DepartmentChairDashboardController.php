<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Models\Approval;
use App\Models\Room;
use App\Models\User;
use App\Notifications\MakeupClassStatusNotification;
use App\Notifications\InstantMakeupNotification;
use App\Notifications\DatabaseOnlyMakeupNotification;
use App\Services\BrevoApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DepartmentChairDashboardController extends Controller
{
    /**
     * Display the department chair dashboard.
     */
    public function index(): View
    {
        // Show only requests that are officially submitted (submitted_to_chair_at is not null)
        // and are still pending and belong to this chair's department
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->where('status', 'pending')
            ->whereNotNull('submitted_to_chair_at') // Only show officially submitted requests
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->get();

        // Calculate counts for dashboard
        $pendingCount = $requests->count();

        // Count all processed requests (approved or rejected) from this department
        $historyCount = MakeUpClassRequest::whereIn('status', ['approved', 'rejected', 'CHAIR_APPROVED'])
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->count();

        // Count approvals made by this chair
        $approvalsCount = Approval::where('chair_id', $chair->id)->count();

        return view('department.dashboard', compact('requests', 'pendingCount', 'historyCount', 'approvalsCount'));
    }

    /**
     * Approve a make-up class request (forward to Academic Head).
     */
    public function approve(Request $request, $id)
    {
        /** @var User $chair */
        $chair = Auth::user();
        $makeupRequest = MakeUpClassRequest::where('id', $id)
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->firstOrFail();

        // Chair approves, but final decision is with Academic Head
        $makeupRequest->status = 'CHAIR_APPROVED';  // approved by chair, waiting for head
        $makeupRequest->chair_remarks = $request->remarks;
        $makeupRequest->save();

        // Log approval in approvals table
        \App\Models\Approval::create([
            'make_up_class_request_id' => $makeupRequest->id,
            'chair_id' => Auth::id(),
            'decision' => 'recommended',
            'remarks' => $request->remarks,
        ]);



        // Notify the faculty that Chair has approved (forwarded) - Environment-based notification
        try {
            $faculty = $makeupRequest->faculty;
            if ($faculty) {
                // Use DatabaseOnlyMakeupNotification for notification bell only (same as student confirmation - database only, no email)
                $faculty->notify(new DatabaseOnlyMakeupNotification($makeupRequest, 'CHAIR_APPROVED', $request->remarks));
                Log::info('Faculty notification sent successfully to: ' . $faculty->name . ' (Environment: ' . app()->environment() . ')');
            }
        } catch (\Exception $e) {
            Log::warning('Faculty notification failed', ['error' => $e->getMessage()]);
        }

        // Notify the department chair (self) for record
        try {
            // Use DatabaseOnlyMakeupNotification for notification bell only (same as student confirmation - database only, no email)
            $chair->notify(new DatabaseOnlyMakeupNotification($makeupRequest, 'forwarded_to_head', $request->remarks));
            Log::info('Chair self-notification sent successfully');
        } catch (\Exception $e) {
            Log::warning('Chair self-notification failed', ['error' => $e->getMessage()]);
        }

        // Notify the Academic Head (database notification)
        try {
            $academicHeads = \App\Models\User::where('role', 'academic_head')->get();
            foreach ($academicHeads as $academicHead) {
                // Use DatabaseOnlyMakeupNotification for notification bell only (same as student confirmation - database only, no email)
                $academicHead->notify(new DatabaseOnlyMakeupNotification($makeupRequest, 'CHAIR_APPROVED', $request->remarks));
            }
            Log::info('Academic Head notification bell sent successfully');
        } catch (\Exception $e) {
            Log::warning('Academic Head notification bell failed', ['error' => $e->getMessage()]);
        }

        // Send email notification to Academic Head
        try {
            $academicHeads = \App\Models\User::where('role', 'academic_head')->get();
            
            if ($academicHeads->isNotEmpty()) {
                $brevoService = new BrevoApiService();
                $subject = 'Makeup Class Request Recommended for Approval - ' . $makeupRequest->subject;
                
                // Build HTML message for Academic Head
                $subjectCode = $makeupRequest->subject;
                $subjectTitle = $makeupRequest->subject_title ?? '';
                $date = \Carbon\Carbon::parse($makeupRequest->preferred_date)->format('F d, Y');
                $time = \App\Helpers\TimeHelper::formatTime($makeupRequest->preferred_time) . ' - ' . \App\Helpers\TimeHelper::formatTime($makeupRequest->end_time);
                $facultyName = $makeupRequest->faculty ? $makeupRequest->faculty->name : 'Unknown Faculty';
                $chairName = $chair->name ?? 'Department Chair';
                $confirmedCount = $makeupRequest->confirmations()->where('status', 'confirmed')->count();
                
                $message = "A makeup class request has been <strong>recommended for approval</strong> by the Department Chair and is waiting for your final approval.<br><br>";
                $message .= "<strong>Faculty:</strong> {$facultyName}<br>";
                $message .= "<strong>Recommended by:</strong> {$chairName}<br>";
                $message .= "<strong>Subject:</strong> {$subjectCode} - {$subjectTitle}<br>";
                $message .= "<strong>Date:</strong> {$date}<br>";
                $message .= "<strong>Time:</strong> {$time}<br>";
                $message .= "<strong>Room:</strong> {$makeupRequest->room}<br>";
                $message .= "<strong>Tracking Number:</strong> {$makeupRequest->tracking_number}<br>";
                $message .= "<strong>Confirmed Students:</strong> {$confirmedCount}<br><br>";
                
                if ($request->remarks) {
                    $message .= "<strong>Chair Remarks:</strong> {$request->remarks}<br><br>";
                }
                
                $message .= "Please log in to the system to review and provide your final approval.";
                
                $actionUrl = url('/academic/dashboard');
                
                // Send email to all Academic Heads
                foreach ($academicHeads as $academicHead) {
                    $emailSent = $brevoService->sendNotificationEmail($academicHead, $subject, $message, $actionUrl);
                    if ($emailSent) {
                        Log::info('Academic Head email sent successfully via Brevo API', ['head_id' => $academicHead->id, 'head_email' => $academicHead->email]);
                    } else {
                        Log::warning('Failed to send Academic Head email via Brevo API', ['head_id' => $academicHead->id, 'head_email' => $academicHead->email]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Academic Head email notification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('department.dashboard')->with('success', 'Request approved and forwarded to Academic Head');
    }

    /**
     * Reject a make-up class request (final).
     */
    public function reject(Request $request, $id)
    {
        /** @var User $chair */
        $chair = Auth::user();
        $makeupRequest = MakeUpClassRequest::where('id', $id)
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->firstOrFail();

        // Chair rejection is final
        $makeupRequest->status = 'CHAIR_REJECTED';
        $makeupRequest->chair_remarks = $request->remarks;
        $makeupRequest->save();

        // Log rejection in approvals table
        \App\Models\Approval::create([
            'make_up_class_request_id' => $makeupRequest->id,
            'chair_id' => Auth::id(),
            'decision' => 'rejected',
            'remarks' => $request->remarks,
        ]);

        // Notify the faculty that Chair has rejected - Environment-based notification
        try {
            $faculty = $makeupRequest->faculty;
            if ($faculty) {
                // Use DatabaseOnlyMakeupNotification for notification bell only (same as student confirmation - database only, no email)
                $faculty->notify(new DatabaseOnlyMakeupNotification($makeupRequest, 'CHAIR_REJECTED', $request->remarks));
                Log::info('Faculty rejection notification sent successfully to: ' . $faculty->name . ' (Environment: ' . app()->environment() . ')');
            }
        } catch (\Exception $e) {
            Log::warning('Faculty rejection notification failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('department.dashboard')->with('success', 'Request rejected');
    }

    /**
     * Display the pending requests page.
     */
    public function requests(): View
    {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->where('status', 'pending')
            ->whereNotNull('submitted_to_chair_at') // Only show officially submitted requests
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->get();
        $status = 'pending';
        return view('department.requests.index', compact('requests', 'status'));
    }

    /**
     * Display a specific request.
     */
    public function show($id): View
    {
        $chair = Auth::user();
        $request = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->where('id', $id)
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->firstOrFail();
        return view('department.requests.show', compact('request'));
    }

    /**
     * Display the history of requests.
     */
    public function history(): View
    {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->whereNot('status', 'pending')
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->get();
        return view('department.history', compact('requests'));
    }

    /** Export history to PDF */
    public function exportHistoryPdf() {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->whereNot('status', 'pending')
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.department_history', [
            'requests' => $requests,
            'generatedAt' => now()->timezone(config('app.timezone')),
            'generatedBy' => $chair->name
        ])->setPaper('a4', 'landscape');
        return $pdf->download('request_history_'.now()->format('Ymd_His').'.pdf');
    }

    /** Export history to Excel */
    public function exportHistoryExcel() {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->whereNot('status', 'pending')
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->fromArray([
            ['Faculty', 'Subject', 'Date', 'Time', 'Room', 'Tracking #', 'Reason', 'Status', 'Remarks']
        ], null, 'A1');

        // Style header row
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setRGB('023047');
        $sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('FFFFFF');

        $row = 2;
        foreach ($requests as $req) {
            $fmt = function($t) {
                if(!$t) return '';
                $f = substr_count($t, ':') === 2 ? 'H:i:s' : 'H:i';
                try {
                    return \Carbon\Carbon::createFromFormat($f, $t)->format('g:i A');
                } catch(\Exception $e) {
                    return $t;
                }
            };
            
            $timeStr = $req->preferred_time ? $fmt($req->preferred_time) : '';
            if($req->end_time) {
                $timeStr .= ' - ' . $fmt($req->end_time);
            }

            $sheet->fromArray([
                $req->faculty->name ?? 'N/A',
                ($req->subject && is_object($req->subject)) ? $req->subject->subject_code : ($req->subject ?? $req->subject_title ?? 'N/A'),
                $req->preferred_date ? \Carbon\Carbon::parse($req->preferred_date)->format('M d, Y') : 'N/A',
                $timeStr,
                $req->room ?? 'N/A',
                $req->tracking_number ?? '—',
                $req->reason ?? 'N/A',
                ucfirst(strtolower(str_replace('_', ' ', $req->status))),
                $req->chair_remarks ?? $req->head_remarks ?? '—'
            ], null, 'A' . $row);
            $row++;
        }

        // Auto-size columns
        foreach(range('A','I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'request_history_' . date('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    /** Print-friendly history view */
    public function printHistory() {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'sectionRelation', 'faculty.department'])
            ->whereNot('status', 'pending')
            ->whereHas('faculty', function ($query) use ($chair) {
                $query->where('department_id', $chair->department_id);
            })
            ->get();
        return view('print.department_history', [
            'requests' => $requests,
            'generatedAt' => now()->timezone(config('app.timezone')),
            'generatedBy' => $chair->name
        ]);
    }

    /**
     * Display approvals log for department chair.
     */
    public function approvals(): View
    {
        $chairId = Auth::id();
        $approvals = \App\Models\Approval::where('chair_id', $chairId)->with('request.faculty')->orderByDesc('created_at')->get();
        return view('department.approvals', compact('approvals'));
    }

    /**
     * Export approval log to PDF
     */
    public function exportApprovalsPdf() {
        $chairId = Auth::id();
        $approvals = \App\Models\Approval::where('chair_id', $chairId)->with('request.faculty')->orderByDesc('created_at')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.department_approvals', [
            'approvals' => $approvals,
            'generatedAt' => now()->timezone(config('app.timezone')),
            'generatedBy' => Auth::user()->name
        ])->setPaper('a4', 'landscape');
        return $pdf->download('approvals_log_'.now()->format('Ymd_His').'.pdf');
    }

    /**
     * Export approval log to Excel
     */
    public function exportApprovalsExcel() {
        $chairId = Auth::id();
        $approvals = \App\Models\Approval::where('chair_id', $chairId)->with('request.faculty')->orderByDesc('created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->fromArray([
            ['Faculty', 'Subject', 'Date', 'Time', 'Room', 'Reason', 'Decision', 'Decision Date', 'Remarks']
        ], null, 'A1');

        // Style header row
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setRGB('023047');
        $sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('FFFFFF');

        $row = 2;
        foreach ($approvals as $approval) {
            $req = $approval->request;
            $fmt = function($t) {
                if(!$t) return '';
                $f = substr_count($t, ':') === 2 ? 'H:i:s' : 'H:i';
                try {
                    return \Carbon\Carbon::createFromFormat($f, $t)->format('g:i A');
                } catch(\Exception $e) {
                    return $t;
                }
            };
            
            $timeStr = $req->preferred_time ? $fmt($req->preferred_time) : '';
            if($req->end_time) {
                $timeStr .= ' - ' . $fmt($req->end_time);
            }

            $sheet->fromArray([
                $req->faculty->name ?? 'N/A',
                ($req->subject && is_object($req->subject)) ? $req->subject->subject_code : ($req->subject ?? $req->subject_title ?? 'N/A'),
                $req->preferred_date ? \Carbon\Carbon::parse($req->preferred_date)->format('M d, Y') : 'N/A',
                $timeStr,
                $req->room ?? 'N/A',
                $req->reason ?? 'N/A',
                ucfirst($approval->decision),
                $approval->created_at ? \Carbon\Carbon::parse($approval->created_at)->format('M d, Y g:i A') : '—',
                $approval->remarks ?: '—'
            ], null, 'A' . $row);
            $row++;
        }

        // Auto-size columns
        foreach(range('A','I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'approvals_log_' . date('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    /** Print-friendly approvals view */
    public function printApprovals() {
        $chairId = Auth::id();
        $approvals = \App\Models\Approval::where('chair_id', $chairId)->with('request.faculty')->orderByDesc('created_at')->get();
        return view('print.department_approvals', [
            'approvals' => $approvals,
            'generatedAt' => now()->timezone(config('app.timezone')),
            'generatedBy' => Auth::user()->name
        ]);
    }

    /**
     * Display the unified schedule board (view-only) for Department Chair.
     */
    public function schedule(Request $request): View
    {
        $canManage = false; // Chair cannot manage
        $selectedDay = $request->get('day', 'Monday');

        $schedules = \App\Models\Schedule::with(['instructor','department'])
            ->where('day_of_week', $selectedDay)
            ->orderBy('time_start')
            ->get();

        $rooms = \App\Models\Room::orderBy('name')->get();

        // Generate time slots (7:00 AM to 8:00 PM, 30-minute intervals)
        $timeSlots = [];
        $start = strtotime('07:00');
        $end = strtotime('20:00');
        for ($time = $start; $time <= $end; $time += 1800) {
            $timeSlots[] = date('g:i A', $time);
        }

        return view('admin.schedules.board', compact('schedules','rooms','timeSlots','selectedDay','canManage'));
    }
}
