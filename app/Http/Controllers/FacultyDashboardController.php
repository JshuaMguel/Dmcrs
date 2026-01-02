<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FacultyDashboardController extends Controller
{
    public function index()
    {
        $facultyId = Auth::id();
        
        // Get statistics for faculty
        $totalRequests = \App\Models\MakeUpClassRequest::where('faculty_id', $facultyId)->count();
        $pendingRequests = \App\Models\MakeUpClassRequest::where('faculty_id', $facultyId)
            ->where('status', 'pending')
            ->count();
        $approvedRequests = \App\Models\MakeUpClassRequest::where('faculty_id', $facultyId)
            ->where('status', 'APPROVED')
            ->count();
        $myClassesCount = \App\Models\FacultyLoadingDetail::where('instructor_id', $facultyId)
            ->whereHas('header', function($q) {
                $q->where('status', 'active');
            })
            ->count();
        
        return view('faculty.dashboard', [
            'title' => 'Faculty Dashboard',
            'totalRequests' => $totalRequests,
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'myClassesCount' => $myClassesCount,
        ]);
    }

    public function studentConfirmations()
    {
        $facultyId = Auth::id();
        
        // Get all makeup class requests from this faculty with student confirmations
        // Show both 'pending' (waiting for student confirmations) and 'APPROVED' requests
        $makeupRequests = \App\Models\MakeUpClassRequest::with([
            'confirmations.student',
            'subject',
            'sectionRelation'
        ])
        ->where('faculty_id', $facultyId)
        ->whereIn('status', ['pending', 'APPROVED']) // Show pending and approved requests
        ->orderBy('preferred_date', 'desc')
        ->get();

        // Add summary calculations for each request
        foreach ($makeupRequests as $request) {
            // Get total students from section if section_id exists
            if ($request->section_id) {
                $section = \App\Models\Section::find($request->section_id);
                
                // Method 1: Get by section_id
                $request->total_students = \App\Models\Student::where('section_id', $request->section_id)
                    ->where('status', 'active')
                    ->count();
                
                // Method 2: If no students by section_id, try department + year_level (fallback)
                if ($request->total_students == 0 && $section) {
                    $request->total_students = \App\Models\Student::where('department_id', $section->department_id)
                        ->where('year_level', $section->year_level)
                        ->where('status', 'active')
                        ->count();
                }
            } else {
                // Fallback: count from confirmations for backward compatibility
                $request->total_students = $request->confirmations()->count();
            }

            // Count confirmations
            $request->confirmed_count = $request->confirmations()->where('status', 'confirmed')->count();
            $request->declined_count = $request->confirmations()->where('status', 'declined')->count();
            $request->pending_count = $request->confirmations()->where('status', 'pending')->orWhereNull('status')->count();
            
            // Calculate students who haven't responded yet
            $request->no_response_count = $request->total_students - ($request->confirmed_count + $request->declined_count);
            if ($request->no_response_count < 0) {
                $request->no_response_count = 0; // Prevent negative values
            }
        }

        return view('faculty.student-confirmations', [
            'title' => 'Student Confirmations',
            'makeupRequests' => $makeupRequests
        ]);
    }

    public function scheduleBoard(Request $request)
    {
        // Use the unified board layout in admin/schedules/board but view-only for faculty
        // Show ONLY this faculty's classes based on their instructor_id
        $canManage = false;
        $selectedDay = $request->get('day', 'Monday');
        $facultyId = Auth::id();
        
        // Get schedules where this faculty is the instructor
        $schedules = \App\Models\Schedule::with(['instructor','department'])
            ->where('day_of_week', $selectedDay)
            ->where('instructor_id', $facultyId) // Filter by faculty's ID
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

    /**
     * Show faculty's own class loading/assignments.
     */
    public function myLoading(Request $request)
    {
        $facultyId = Auth::id();
        
        // Get all faculty loading details where this faculty is the instructor
        $query = \App\Models\FacultyLoadingDetail::with(['header.department', 'instructor'])
            ->where('instructor_id', $facultyId);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->whereHas('header', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        } else {
            // Default: only show active loading if no status filter
            $query->whereHas('header', function($q) {
                $q->where('status', 'active');
            });
        }

        // Filter by semester if provided
        if ($request->filled('semester')) {
            $query->whereHas('header', function($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        // Filter by school year if provided
        if ($request->filled('school_year')) {
            $query->whereHas('header', function($q) use ($request) {
                $q->where('school_year', $request->school_year);
            });
        }

        $myClasses = $query->orderBy('day_of_week')
            ->orderBy('time_start')
            ->get();

        // Load subjects and calculate student counts
        $subjectCodes = $myClasses->pluck('subject_code')->unique();
        $subjects = \App\Models\Subject::whereIn('subject_code', $subjectCodes)->get()->keyBy('subject_code');
        
        // Load all sections for in-memory filtering (since full_name and abbreviated_name are accessors)
        $allSections = \App\Models\Section::with('department')->get();
        
        // Calculate student counts for each class
        foreach ($myClasses as $class) {
            // Get subject title
            $class->subject_title = $subjects->get($class->subject_code)->subject_title ?? 'N/A';
            
            // Find section by matching section_name, full_name, or abbreviated_name (in-memory filtering)
            $section = $allSections->first(function($s) use ($class) {
                return $s->section_name === $class->section 
                    || $s->full_name === $class->section 
                    || $s->abbreviated_name === $class->section;
            });
            
            if ($section) {
                // Count by section_id
                $class->total_students = \App\Models\Student::where('section_id', $section->id)
                    ->where('status', 'active')
                    ->count();
                
                // Fallback: if no students by section_id, try department + year_level
                if ($class->total_students == 0) {
                    $class->total_students = \App\Models\Student::where('department_id', $section->department_id)
                        ->where('year_level', $section->year_level)
                        ->where('status', 'active')
                        ->count();
                }
            } else {
                $class->total_students = 0;
            }
        }

        // Group by day for better display
        $classesByDay = $myClasses->groupBy('day_of_week');

        return view('faculty.my-loading', [
            'title' => 'My Class Loading',
            'myClasses' => $myClasses,
            'classesByDay' => $classesByDay
        ]);
    }

}
