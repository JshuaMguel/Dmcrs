<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{
    // View-only schedule board for Faculty, Department Chair, and Academic Head
    public function index(Request $request)
    {
        $user = Auth::user();

        // View-only access using the unified admin board view
        $canManage = false;

        $selectedDay = $request->get('day', 'Monday');

        // Get all schedules for the selected day with relationships
        $schedules = Schedule::with(['instructor', 'department'])
            ->where('day_of_week', $selectedDay)
            ->orderBy('time_start')
            ->get();

        // Get all rooms
        $rooms = \App\Models\Room::orderBy('name')->get();

        // Generate time slots (7:00 AM to 8:00 PM, 30-minute intervals)
        $timeSlots = [];
        $start = strtotime('07:00');
        $end = strtotime('20:00');

        for ($time = $start; $time <= $end; $time += 1800) { // 1800 seconds = 30 minutes
            $timeSlots[] = date('g:i A', $time); // Use 12-hour format with AM/PM
        }

        return view('admin.schedules.board', compact('schedules', 'rooms', 'timeSlots', 'selectedDay', 'canManage', 'user'));
    }

    // Only allow Academic Head to access these methods
    public function create()
    {
        if (Auth::user()->role !== 'academic_head') abort(403);
        $departments = Department::all();
        $faculty = User::where('role', 'faculty')->get();
        $rooms = \App\Models\Room::all();
    return view('head.schedule.create', compact('departments', 'faculty', 'rooms'));
    }

    // Store new schedule
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'academic_head') abort(403);
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'subject_code' => 'required|string|max:255',
            'subject_title' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'day' => 'required|string|in:Mon,Tue,Wed,Thu,Fri,Sat',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'room' => 'required|string|max:255',
            'instructor_id' => 'nullable|exists:users,id',
            'instructor_name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Require one instructor field, not both
        if (!$request->instructor_id && !$request->instructor_name) {
            return back()->withErrors(['instructor' => 'Select a faculty or enter a name.'])->withInput();
        }
        if ($request->instructor_id && $request->instructor_name) {
            return back()->withErrors(['instructor' => 'Choose only one instructor field.'])->withInput();
        }
        // Conflict check: Only block if same room, same day, and overlapping time
        $conflict = Schedule::where('room', $request->room)
            ->where('day_of_week', $request->day)
            ->where(function($q) use ($request) {
                $q->where('time_start', '<', $request->time_end)
                  ->where('time_end', '>', $request->time_start);
            })->exists();
        // Schedules with same time but different rooms are allowed
        if ($conflict) {
            return back()->withErrors(['room' => 'Room not available at this time.'])->withInput();
        }
        $data = $request->only([
            'semester', 'department_id', 'subject_code', 'subject_title', 'section', 'day', 'time_start', 'time_end', 'room', 'instructor_id', 'instructor_name'
        ]);
        $data['day_of_week'] = $data['day'];
        unset($data['day']);
        // Default manually created schedules to REGULAR
        $data['type'] = 'REGULAR';
        // Auto-add room if not existing
        if (!empty($data['room'])) {
            $roomModel = \App\Models\Room::where('name', $data['room'])->first();
            if (!$roomModel) {
                \App\Models\Room::create(['name' => $data['room']]);
            }
        }
        Schedule::create($data);
        return redirect()->route('schedules.index')->with('success', 'Schedule added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        if (Auth::user()->role !== 'academic_head') abort(403);
        $schedule = Schedule::findOrFail($id);
        $departments = Department::all();
        $faculty = User::where('role', 'faculty')->get();
        $rooms = \App\Models\Room::all();
    return view('head.schedule.edit', compact('schedule', 'departments', 'faculty', 'rooms'));
    }

    // Update schedule
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'academic_head') abort(403);
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'subject_code' => 'required|string|max:255',
            'subject_title' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'day' => 'required|string|in:Mon,Tue,Wed,Thu,Fri,Sat',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'room' => 'required|string|max:255',
            'instructor_id' => 'nullable|exists:users,id',
            'instructor_name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if (!$request->instructor_id && !$request->instructor_name) {
            return back()->withErrors(['instructor' => 'Select a faculty or enter a name.'])->withInput();
        }
        if ($request->instructor_id && $request->instructor_name) {
            return back()->withErrors(['instructor' => 'Choose only one instructor field.'])->withInput();
        }
        $conflict = Schedule::where('room', $request->room)
            ->where('day_of_week', $request->day)
            ->where('id', '!=', $id)
            ->where(function($q) use ($request) {
                $q->where('time_start', '<', $request->time_end)
                  ->where('time_end', '>', $request->time_start);
            })->exists();
        if ($conflict) {
            return back()->withErrors(['room' => 'Room not available at this time.'])->withInput();
        }
        $data = $request->only([
            'department_id', 'subject_code', 'subject_title', 'section', 'day', 'time_start', 'time_end', 'room', 'instructor_id', 'instructor_name'
        ]);
        $data['day_of_week'] = $data['day'];
        unset($data['day']);
        $data['type'] = 'REGULAR';
        $schedule = Schedule::findOrFail($id);
        $schedule->update($data);
        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully!');
    }

    // Delete schedule
    public function destroy($id)
    {
        if (Auth::user()->role !== 'academic_head') abort(403);
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully!');
    }

}
