<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Models\Approval;
use App\Models\Room;
use App\Models\User;
use App\Notifications\MakeupClassStatusNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DepartmentChairDashboardController extends Controller
{
    /**
     * Display the department chair dashboard.
     */
    public function index(): View
    {
        // Show only requests that are still pending and belong to this chair's department
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'section', 'faculty.department'])
            ->where('status', 'pending')
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



        // Notify the faculty that Chair has approved (forwarded)
        if (method_exists($makeupRequest, 'notifyStatusChange')) {
            $makeupRequest->notifyStatusChange('CHAIR_APPROVED', $request->remarks);
        }
        // Notify the department chair (self) for record
        $chair->notify(new MakeupClassStatusNotification($makeupRequest, 'forwarded_to_head', $request->remarks));

        // Notify the Academic Head (now using model method)
        $makeupRequest->notifyAcademicHead('CHAIR_APPROVED', $request->remarks);

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

        // Optional: Notify the faculty
        if (method_exists($makeupRequest, 'notifyStatusChange')) {
            $makeupRequest->notifyStatusChange('CHAIR_REJECTED', $request->remarks);
        }

        return redirect()->route('department.dashboard')->with('success', 'Request rejected');
    }

    /**
     * Display the pending requests page.
     */
    public function requests(): View
    {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'section', 'faculty.department'])
            ->where('status', 'pending')
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
        $request = MakeUpClassRequest::with(['subject.department', 'section', 'faculty.department'])
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
        $requests = MakeUpClassRequest::with(['subject.department', 'section', 'faculty.department'])
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
        $requests = MakeUpClassRequest::with(['subject.department', 'section', 'faculty.department'])
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

    /** Print-friendly history view */
    public function printHistory() {
        $chair = Auth::user();
        $requests = MakeUpClassRequest::with(['subject.department', 'section', 'faculty.department'])
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
