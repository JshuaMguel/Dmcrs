<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Notifications\MakeupClassStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MakeUpClassRequestController extends Controller
{
    // 📌 Show all requests of logged-in faculty
    public function index()
    {
        $requests = MakeUpClassRequest::with(['subject', 'section'])->where('faculty_id', Auth::id())->latest()->get();
        return view('faculty.makeup-requests.index', compact('requests'));
    }

    // 📌 Show request form
    public function create()
    {
        $rooms = \App\Models\Room::all();
        $departments = \App\Models\Department::orderBy('name')->get();
        $subjects = \App\Models\Subject::with('department')->orderBy('subject_code')->get();
        $sections = \App\Models\Section::with('department')->orderBy('department_id')->orderBy('year_level')->orderBy('section_name')->get();
        return view('faculty.makeup-requests.create', compact('rooms', 'departments', 'subjects', 'sections'));
    }

    // 📌 Store new request
    public function store(Request $request)
    {

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'subject' => 'required|string|max:100',
            'subject_title' => 'required|string|max:200',
            'section_id' => 'required|exists:sections,id',
            'section' => 'required|string|max:50', // Keep for backward compatibility
            'room' => 'nullable|string|max:100',
            'reason' => 'required|string',
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
            'end_time' => 'required',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
            'student_list' => 'required|file|mimes:csv|max:4096',
        ]);

        // If room is not provided, set to 'Temporary Room'
        $room = $request->room ?: 'Temporary Room';

        // Handle file uploads
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }
        $studentListPath = null;
        if ($request->hasFile('student_list')) {
            $studentListPath = $request->file('student_list')->store('student_lists', 'public');
        }

        // Generate tracking number
        $trackingNumber = 'REQ-' . strtoupper(Str::random(8));

        // Get faculty information for department_id
        $faculty = Auth::user();

        $makeupRequest = MakeUpClassRequest::create([
            'faculty_id' => Auth::id(),
            'subject_id' => $request->subject_id,
            'subject' => $request->subject,
            'subject_title' => $request->subject_title,
            'section_id' => $request->section_id,
            'room' => $room,
            'reason' => $request->reason,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'end_time' => $request->end_time,
            'attachment' => $attachmentPath,
            'student_list' => $studentListPath,
            'tracking_number' => $trackingNumber,
            'department_id' => $faculty->department_id ?? 1,
            'section' => $request->section, // Keep for backward compatibility
            'semester' => $request->semester ?? 'Current',
        ]);

        // 📌 Notify faculty of submission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notify(new MakeupClassStatusNotification($makeupRequest, 'submitted'));

        // 📌 Notify department chair about new request
        $makeupRequest->notifyDepartmentChair();

        return redirect()->route('makeup-requests.index')->with('success', 'Make-up class request submitted successfully!');
    }

    // 📌 Show edit form
    public function edit($id)
    {
        $request = MakeUpClassRequest::with(['subject.department', 'section.department'])->where('faculty_id', Auth::id())->findOrFail($id);
        $rooms = \App\Models\Room::all();
        $departments = \App\Models\Department::orderBy('name')->get();
        $subjects = \App\Models\Subject::with('department')->orderBy('subject_code')->get();
        $sections = \App\Models\Section::with('department')->orderBy('department_id')->orderBy('year_level')->orderBy('section_name')->get();
        return view('faculty.makeup-requests.edit', compact('request', 'rooms', 'departments', 'subjects', 'sections'));
    }

    // 📌 Update request
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'subject' => 'required|string|max:100',
            'subject_title' => 'required|string|max:200',
            'section_id' => 'required|exists:sections,id',
            'section' => 'required|string|max:50', // Keep for backward compatibility
            'room' => 'required|string|max:100',
            'reason' => 'required|string',
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
            'end_time' => 'required',
        ]);

        $req = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);

        // Update fields
        $req->update([
            'subject_id' => $request->subject_id,
            'subject' => $request->subject,
            'subject_title' => $request->subject_title,
            'section_id' => $request->section_id,
            'section' => $request->section, // Keep for backward compatibility
            'room' => $request->room,
            'reason' => $request->reason,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'end_time' => $request->end_time,
        ]);

        // 📌 Notify faculty of update
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notify(new MakeupClassStatusNotification($req, 'updated'));

        return redirect()->route('makeup-requests.index')->with('success', 'Request updated successfully!');
    }

    // 📌 Show request details
    public function show($id)
    {
        $request = MakeUpClassRequest::with(['subject', 'section'])->where('faculty_id', Auth::id())->findOrFail($id);
        return view('faculty.makeup-requests.show', compact('request'));
    }

    // 📌 Delete request
    public function destroy($id)
    {
        $req = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);
        $req->delete();

        return redirect()->route('makeup-requests.index')->with('success', 'Request deleted successfully!');
    }

    // 📌 Get sections by department (AJAX endpoint)
    public function getSectionsByDepartment(Request $request)
    {
        $departmentId = $request->get('department_id');

        if (!$departmentId) {
            return response()->json([]);
        }

        $sections = \App\Models\Section::where('department_id', $departmentId)
            ->orderBy('year_level')
            ->orderBy('section_name')
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'full_name' => $section->full_name
                ];
            });

        return response()->json($sections);
    }

    // 📌 Get available rooms for a given date and time (AJAX)
    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'preferred_date' => 'required|date',
            'preferred_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:preferred_time',
        ]);

        $date = \Carbon\Carbon::parse($request->preferred_date);
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->preferred_time)->format('H:i:s');
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');

        $dayFull = $date->format('l');  // Monday
        $dayShort = $date->format('D'); // Mon

        // Find rooms that are busy due to regular schedules at the given weekday and overlapping times
        $busyRoomsFromSchedules = \App\Models\Schedule::query()
            ->whereIn('day_of_week', [$dayFull, $dayShort])
            ->whereIn('status', ['active', 'APPROVED'])
            // Time overlap: start < end AND end > start
            ->where('time_start', '<', $end)
            ->where('time_end', '>', $start)
            ->pluck('room')
            ->filter()
            ->unique()
            ->values();

        // Also block rooms that are already chosen in makeup requests for the SAME date and overlapping time
        // Consider requests that are pending or approved by chair/head
        $blockingStatuses = ['pending', 'CHAIR_APPROVED', 'APPROVED'];
        $busyRoomsFromRequestsQuery = MakeUpClassRequest::query()
            ->whereDate('preferred_date', $date->toDateString())
            ->whereIn('status', $blockingStatuses)
            // Overlap on the same date
            ->where('preferred_time', '<', $end)
            ->where('end_time', '>', $start)
            // Only if a real room is selected (exclude placeholders)
            ->whereNotNull('room')
            ->where('room', '!=', '')
            ->where('room', '!=', 'Temporary Room')
        ;

        // Optionally ignore the current request (when editing) to avoid self-blocking
        if ($request->has('ignore_id') && is_numeric($request->get('ignore_id'))) {
            $busyRoomsFromRequestsQuery->where('id', '!=', (int) $request->get('ignore_id'));
        }

        $busyRoomsFromRequests = $busyRoomsFromRequestsQuery->pluck('room')
            ->filter()
            ->unique()
            ->values();

        // Merge busy rooms from schedules and requests
        $busyRooms = $busyRoomsFromSchedules->merge($busyRoomsFromRequests)->unique()->values();

        // Available rooms are those not in busy list
        $rooms = \App\Models\Room::orderBy('name')->get()
            ->filter(fn($r) => !$busyRooms->contains($r->name))
            ->map(fn($r) => ['name' => $r->name]);

        return response()->json([
            'available' => array_values($rooms->toArray()),
            'busy' => $busyRooms,
        ]);
    }
}
