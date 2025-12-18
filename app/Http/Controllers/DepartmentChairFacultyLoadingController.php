<?php

namespace App\Http\Controllers;

use App\Models\FacultyLoadingHeader;
use App\Models\FacultyLoadingDetail;
use App\Models\Department;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DepartmentChairFacultyLoadingController extends Controller
{
    /**
     * Check if user is department chair and get their department.
     */
    private function checkAccess()
    {
        if (!Auth::check() || Auth::user()->role !== 'department_chair') {
            abort(403, 'Unauthorized access.');
        }

        if (!Auth::user()->department_id) {
            abort(403, 'Department not assigned.');
        }

        return Auth::user()->department_id;
    }

    /**
     * Display a listing of faculty loading headers.
     */
    public function index(Request $request)
    {
        $departmentId = $this->checkAccess();

        $query = FacultyLoadingHeader::with(['department', 'uploadedBy'])
            ->where('department_id', $departmentId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by school year
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        $headers = $query->orderBy('school_year', 'desc')
            ->orderBy('semester')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('department.faculty-loading.index', compact('headers'));
    }

    /**
     * Show the form for creating a new faculty loading header.
     */
    public function create()
    {
        $this->checkAccess();

        $instructors = User::where('role', 'faculty')
            ->where('department_id', Auth::user()->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('department_id', Auth::user()->department_id)
            ->orderBy('subject_code')
            ->get();

        return view('department.faculty-loading.create', compact('instructors', 'subjects'));
    }

    /**
     * Store a newly created faculty loading header and details.
     */
    public function store(Request $request)
    {
        $departmentId = $this->checkAccess();

        $request->validate([
            'semester' => 'required|in:1st,2nd,summer',
            'school_year' => 'required|string|max:20',
            'status' => 'required|in:draft,active,archived',
            'remarks' => 'nullable|string|max:1000',
            'details' => 'required|array|min:1',
            'details.*.instructor_id' => 'required|exists:users,id',
            'details.*.subject_code' => 'required|string|max:50',
            'details.*.section' => 'required|string|max:50',
            'details.*.day_of_week' => 'required|string|max:20',
            'details.*.time_start' => 'required|date_format:H:i',
            'details.*.time_end' => 'required|date_format:H:i|after:details.*.time_start',
            'details.*.room' => 'required|string|max:100',
            'details.*.units' => 'nullable|numeric|min:0|max:10',
        ]);

        // Check for duplicate header (same department, semester, school_year)
        $existing = FacultyLoadingHeader::where('department_id', $departmentId)
            ->where('semester', $request->semester)
            ->where('school_year', $request->school_year)
            ->first();

        if ($existing) {
            return back()->withErrors(['school_year' => 'Faculty loading already exists for this semester and school year.'])->withInput();
        }

        DB::beginTransaction();

        try {
            // Create header
            $header = FacultyLoadingHeader::create([
                'department_id' => $departmentId,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
                'uploaded_by' => Auth::id(),
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);

            // Create details and sync to schedules
            foreach ($request->details as $detailData) {
                $instructor = User::findOrFail($detailData['instructor_id']);

                // Validate instructor belongs to department
                if ($instructor->department_id != $departmentId) {
                    throw new \Exception('Instructor does not belong to your department.');
                }

                // Check for time conflicts
                $this->checkTimeConflict($detailData, $header->id);

                // Create detail
                $detail = FacultyLoadingDetail::create([
                    'faculty_loading_header_id' => $header->id,
                    'instructor_id' => $detailData['instructor_id'],
                    'subject_code' => $detailData['subject_code'],
                    'section' => $detailData['section'],
                    'day_of_week' => $detailData['day_of_week'],
                    'time_start' => $detailData['time_start'],
                    'time_end' => $detailData['time_end'],
                    'room' => $detailData['room'],
                    'units' => $detailData['units'] ?? null,
                ]);

                // Sync to schedules table if status is active
                if ($request->status === 'active') {
                    $this->syncToSchedules($detail, $header);
                }
            }

            DB::commit();

            Log::info('Faculty loading created', [
                'header_id' => $header->id,
                'flh_code' => $header->flh_code,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('department.faculty-loading.index')
                ->with('success', 'Faculty loading created successfully! FLH Code: ' . $header->flh_code);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Faculty loading creation failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to create faculty loading: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified faculty loading header.
     */
    public function show(FacultyLoadingHeader $facultyLoading)
    {
        $departmentId = $this->checkAccess();

        // Verify ownership
        if ($facultyLoading->department_id != $departmentId) {
            abort(403, 'Unauthorized access.');
        }

        $facultyLoading->load(['details.instructor', 'department', 'uploadedBy']);
        
        // Load subjects and calculate student counts for each detail
        $subjectCodes = $facultyLoading->details->pluck('subject_code')->unique();
        $subjects = Subject::whereIn('subject_code', $subjectCodes)->get()->keyBy('subject_code');
        
        // Load all sections for in-memory filtering (since full_name and abbreviated_name are accessors)
        $allSections = Section::with('department')->get();
        
        // Calculate student counts for each section
        foreach ($facultyLoading->details as $detail) {
            // Get subject title
            $detail->subject_title = $subjects->get($detail->subject_code)->subject_title ?? 'N/A';
            
            // Find section by matching section_name, full_name, or abbreviated_name (in-memory filtering)
            $section = $allSections->first(function($s) use ($detail) {
                return $s->section_name === $detail->section 
                    || $s->full_name === $detail->section 
                    || $s->abbreviated_name === $detail->section;
            });
            
            if ($section) {
                // Count by section_id
                $detail->total_students = Student::where('section_id', $section->id)
                    ->where('status', 'active')
                    ->count();
                
                // Fallback: if no students by section_id, try department + year_level
                if ($detail->total_students == 0) {
                    $detail->total_students = Student::where('department_id', $section->department_id)
                        ->where('year_level', $section->year_level)
                        ->where('status', 'active')
                        ->count();
                }
            } else {
                $detail->total_students = 0;
            }
        }

        return view('department.faculty-loading.show', compact('facultyLoading'));
    }

    /**
     * Show the form for editing the specified faculty loading header.
     */
    public function edit(FacultyLoadingHeader $facultyLoading)
    {
        $departmentId = $this->checkAccess();

        // Verify ownership
        if ($facultyLoading->department_id != $departmentId) {
            abort(403, 'Unauthorized access.');
        }

        $facultyLoading->load('details');

        $instructors = User::where('role', 'faculty')
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('department_id', $departmentId)
            ->orderBy('subject_code')
            ->get();

        // Prepare existing details for JavaScript with properly formatted times
        $existingDetails = $facultyLoading->details->map(function($d) {
            return [
                'id' => $d->id,
                'instructor_id' => $d->instructor_id,
                'subject_code' => $d->subject_code,
                'section' => $d->section,
                'day_of_week' => $d->day_of_week,
                'time_start' => $d->time_start ? (is_string($d->time_start) ? $d->time_start : $d->time_start->format('H:i')) : '',
                'time_end' => $d->time_end ? (is_string($d->time_end) ? $d->time_end : $d->time_end->format('H:i')) : '',
                'room' => $d->room,
                'units' => $d->units
            ];
        });

        return view('department.faculty-loading.edit', compact('facultyLoading', 'instructors', 'subjects', 'existingDetails'));
    }

    /**
     * Update the specified faculty loading header and details.
     */
    public function update(Request $request, FacultyLoadingHeader $facultyLoading)
    {
        $departmentId = $this->checkAccess();

        // Verify ownership
        if ($facultyLoading->department_id != $departmentId) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'semester' => 'required|in:1st,2nd,summer',
            'school_year' => 'required|string|max:20',
            'status' => 'required|in:draft,active,archived',
            'remarks' => 'nullable|string|max:1000',
            'details' => 'required|array|min:1',
            'details.*.instructor_id' => 'required|exists:users,id',
            'details.*.subject_code' => 'required|string|max:50',
            'details.*.section' => 'required|string|max:50',
            'details.*.day_of_week' => 'required|string|max:20',
            'details.*.time_start' => 'required|date_format:H:i',
            'details.*.time_end' => 'required|date_format:H:i|after:details.*.time_start',
            'details.*.room' => 'required|string|max:100',
            'details.*.units' => 'nullable|numeric|min:0|max:10',
        ]);

        // Check for duplicate header (excluding current)
        $existing = FacultyLoadingHeader::where('department_id', $departmentId)
            ->where('semester', $request->semester)
            ->where('school_year', $request->school_year)
            ->where('id', '!=', $facultyLoading->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['school_year' => 'Faculty loading already exists for this semester and school year.'])->withInput();
        }

        DB::beginTransaction();

        try {
            // Update header
            $facultyLoading->update([
                'semester' => $request->semester,
                'school_year' => $request->school_year,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);

            // Get existing detail IDs
            $existingDetailIds = $facultyLoading->details->pluck('id')->toArray();
            $newDetailIds = [];

            // Update or create details
            foreach ($request->details as $detailData) {
                $instructor = User::findOrFail($detailData['instructor_id']);

                // Validate instructor belongs to department
                if ($instructor->department_id != $departmentId) {
                    throw new \Exception('Instructor does not belong to your department.');
                }

                // Check for time conflicts (excluding current detail if updating)
                $this->checkTimeConflict($detailData, $facultyLoading->id, isset($detailData['id']) ? $detailData['id'] : null);

                if (isset($detailData['id']) && in_array($detailData['id'], $existingDetailIds)) {
                    // Update existing detail
                    $detail = FacultyLoadingDetail::findOrFail($detailData['id']);
                    $detail->update([
                        'instructor_id' => $detailData['instructor_id'],
                        'subject_code' => $detailData['subject_code'],
                        'section' => $detailData['section'],
                        'day_of_week' => $detailData['day_of_week'],
                        'time_start' => $detailData['time_start'],
                        'time_end' => $detailData['time_end'],
                        'room' => $detailData['room'],
                        'units' => $detailData['units'] ?? null,
                    ]);
                    $newDetailIds[] = $detail->id;

                    // Update schedules
                    if ($request->status === 'active') {
                        $this->syncToSchedules($detail, $facultyLoading, true);
                    }
                } else {
                    // Create new detail
                    $detail = FacultyLoadingDetail::create([
                        'faculty_loading_header_id' => $facultyLoading->id,
                        'instructor_id' => $detailData['instructor_id'],
                        'subject_code' => $detailData['subject_code'],
                        'section' => $detailData['section'],
                        'day_of_week' => $detailData['day_of_week'],
                        'time_start' => $detailData['time_start'],
                        'time_end' => $detailData['time_end'],
                        'room' => $detailData['room'],
                        'units' => $detailData['units'] ?? null,
                    ]);
                    $newDetailIds[] = $detail->id;

                    // Sync to schedules
                    if ($request->status === 'active') {
                        $this->syncToSchedules($detail, $facultyLoading);
                    }
                }
            }

            // Delete removed details
            $detailsToDelete = array_diff($existingDetailIds, $newDetailIds);
            foreach ($detailsToDelete as $detailId) {
                $detail = FacultyLoadingDetail::find($detailId);
                if ($detail) {
                    // Remove from schedules
                    Schedule::where('faculty_loading_detail_id', $detailId)->update(['faculty_loading_detail_id' => null]);
                    $detail->delete();
                }
            }

            DB::commit();

            Log::info('Faculty loading updated', [
                'header_id' => $facultyLoading->id,
                'flh_code' => $facultyLoading->flh_code,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('department.faculty-loading.index')
                ->with('success', 'Faculty loading updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Faculty loading update failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to update faculty loading: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified faculty loading header (soft delete).
     */
    public function destroy(FacultyLoadingHeader $facultyLoading)
    {
        $departmentId = $this->checkAccess();

        // Verify ownership
        if ($facultyLoading->department_id != $departmentId) {
            abort(403, 'Unauthorized access.');
        }

        DB::beginTransaction();

        try {
            // Remove from schedules
            foreach ($facultyLoading->details as $detail) {
                Schedule::where('faculty_loading_detail_id', $detail->id)
                    ->update(['faculty_loading_detail_id' => null]);
            }

            // Soft delete header (cascade will handle details)
            $facultyLoading->delete();

            DB::commit();

            Log::info('Faculty loading archived', [
                'header_id' => $facultyLoading->id,
                'archived_by' => Auth::id()
            ]);

            return redirect()->route('department.faculty-loading.index')
                ->with('success', 'Faculty loading archived successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Faculty loading deletion failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to archive faculty loading: ' . $e->getMessage());
        }
    }

    /**
     * Check for time conflicts.
     */
    private function checkTimeConflict($detailData, $headerId, $excludeDetailId = null)
    {
        $conflict = FacultyLoadingDetail::where('faculty_loading_header_id', $headerId)
            ->where('instructor_id', $detailData['instructor_id'])
            ->where('day_of_week', $detailData['day_of_week'])
            ->where(function ($query) use ($detailData) {
                $query->whereBetween('time_start', [$detailData['time_start'], $detailData['time_end']])
                    ->orWhereBetween('time_end', [$detailData['time_start'], $detailData['time_end']])
                    ->orWhere(function ($q) use ($detailData) {
                        $q->where('time_start', '<=', $detailData['time_start'])
                          ->where('time_end', '>=', $detailData['time_end']);
                    });
            });

        if ($excludeDetailId) {
            $conflict->where('id', '!=', $excludeDetailId);
        }

        if ($conflict->exists()) {
            throw new \Exception('Time conflict detected for instructor on ' . $detailData['day_of_week']);
        }
    }

    /**
     * Sync faculty loading detail to schedules table.
     */
    private function syncToSchedules(FacultyLoadingDetail $detail, FacultyLoadingHeader $header, $update = false)
    {
        $instructor = $detail->instructor;
        $subject = Subject::where('subject_code', $detail->subject_code)
            ->where('department_id', $header->department_id)
            ->first();

        $scheduleData = [
            'department_id' => $header->department_id,
            'semester' => $header->semester,
            'subject_code' => $detail->subject_code,
            'subject_title' => $subject ? $subject->subject_title : '',
            'section' => $detail->section,
            'day_of_week' => $detail->day_of_week,
            'time_start' => $detail->time_start,
            'time_end' => $detail->time_end,
            'room' => $detail->room,
            'instructor_id' => $detail->instructor_id,
            'instructor_name' => $instructor ? $instructor->name : '',
            'type' => 'REGULAR',
            'status' => 'active',
            'faculty_loading_detail_id' => $detail->id,
        ];

        if ($update) {
            // Update existing schedule
            $schedule = Schedule::where('faculty_loading_detail_id', $detail->id)->first();
            if ($schedule) {
                $schedule->update($scheduleData);
            } else {
                Schedule::create($scheduleData);
            }
        } else {
            // Create new schedule
            Schedule::create($scheduleData);
        }
    }
}


