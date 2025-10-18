<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Check if the current user has admin access
     */
    private function checkAdminAccess()
    {
        $userRole = strtolower(str_replace(' ', '', Auth::user()->role ?? ''));

        if (!in_array($userRole, ['admin', 'superadmin', 'super_admin', 'super admin'])) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
    }

    /**
     * Display a listing of schedules
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $query = Schedule::with(['instructor', 'department']);

        // Apply filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject_code', 'like', "%{$search}%")
                  ->orWhere('subject_title', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%");
            });
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('time_start')->paginate(20);

        $departments = Department::orderBy('name')->get();
        $instructors = User::where('role', 'faculty')->orderBy('name')->get();

        return view('admin.schedules.index', compact('schedules', 'departments', 'instructors'));
    }

    /**
     * Display schedule board (calendar-style view)
     */
    public function board(Request $request)
    {
        $this->checkAdminAccess();

        $selectedDay = $request->get('day', 'Monday');

        // Get all schedules for the selected day with relationships
        $schedules = Schedule::with(['instructor', 'department'])
            ->where('day_of_week', $selectedDay)
            ->orderBy('time_start')
            ->get();

        // Get all rooms
        $rooms = Room::orderBy('name')->get();

        // Generate time slots (7:00 AM to 8:00 PM, 30-minute intervals)
        $timeSlots = [];
        $start = strtotime('07:00');
        $end = strtotime('20:00');

        for ($time = $start; $time <= $end; $time += 1800) { // 1800 seconds = 30 minutes
            $timeSlots[] = date('g:i A', $time); // Use 12-hour format with AM/PM
        }

        return view('admin.schedules.board', compact('schedules', 'rooms', 'timeSlots', 'selectedDay'));
    }

    /**
     * Show the form for creating a new schedule
     */
    public function create()
    {
        $this->checkAdminAccess();

        $departments = Department::orderBy('name')->get();
        $instructors = User::where('role', 'faculty')->orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.schedules.create', compact('departments', 'instructors', 'rooms', 'days'));
    }

    /**
     * Store a newly created schedule in storage
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'instructor_id' => 'required|exists:users,id',
            'subject_code' => 'required|string|max:50',
            'subject_title' => 'required|string|max:255',
            'section' => 'required|string|max:50',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'room' => 'required|string|max:50',
            'semester' => 'nullable|string|max:50',
            'status' => 'required|string|in:active,inactive,pending,APPROVED',
        ]);

        // Check for time conflicts
        $conflict = Schedule::where('room', $validated['room'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('time_start', [$validated['time_start'], $validated['time_end']])
                    ->orWhereBetween('time_end', [$validated['time_start'], $validated['time_end']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('time_start', '<=', $validated['time_start'])
                          ->where('time_end', '>=', $validated['time_end']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'time' => 'Time conflict detected! This room is already booked for the selected time slot.'
            ])->withInput();
        }

    // Set type explicitly; admin-created schedules are regular by default
    $validated['type'] = 'REGULAR';

    Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule created successfully!');
    }

    /**
     * Show the form for editing the specified schedule
     */
    public function edit($id)
    {
        $this->checkAdminAccess();

        $schedule = Schedule::findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $instructors = User::where('role', 'faculty')->orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.schedules.edit', compact('schedule', 'departments', 'instructors', 'rooms', 'days'));
    }

    /**
     * Update the specified schedule in storage
     */
    public function update(Request $request, $id)
    {
        $this->checkAdminAccess();

        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'instructor_id' => 'required|exists:users,id',
            'subject_code' => 'required|string|max:50',
            'subject_title' => 'required|string|max:255',
            'section' => 'required|string|max:50',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'room' => 'required|string|max:50',
            'semester' => 'nullable|string|max:50',
            'status' => 'required|string|in:active,inactive,pending,APPROVED',
        ]);

        // Check for time conflicts (excluding current schedule)
        $conflict = Schedule::where('room', $validated['room'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('time_start', [$validated['time_start'], $validated['time_end']])
                    ->orWhereBetween('time_end', [$validated['time_start'], $validated['time_end']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('time_start', '<=', $validated['time_start'])
                          ->where('time_end', '>=', $validated['time_end']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'time' => 'Time conflict detected! This room is already booked for the selected time slot.'
            ])->withInput();
        }

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified schedule from storage
     */
    public function destroy($id)
    {
        $this->checkAdminAccess();

        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }
}
