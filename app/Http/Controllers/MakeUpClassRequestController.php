<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Notifications\MakeupClassStatusNotification;
use App\Notifications\InstantMakeupNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MakeUpClassRequestController extends Controller
{
    // 📌 Show all requests of logged-in faculty
    public function index()
    {
        $requests = MakeUpClassRequest::with(['subject', 'sectionRelation'])->where('faculty_id', Auth::id())->latest()->get();
        return view('faculty.makeup-requests.index', compact('requests'));
    }

    // 📌 Show request form
    public function create()
    {
        $user = Auth::user();
        $rooms = \App\Models\Room::all();
        
        // Get all departments (for department selection dropdown)
        $departments = \App\Models\Department::orderBy('name')->get();
        
        // Filter subjects and sections based on faculty's department for better UX
        // But still load all data since JavaScript filtering handles cross-department access
        $subjects = \App\Models\Subject::with('department')->orderBy('subject_code')->get();
        $sections = \App\Models\Section::with('department')->orderBy('department_id')->orderBy('year_level')->orderBy('section_name')->get();
        
        // Pass user's department for default selection
        $userDepartment = $user->department_id;
        
        return view('faculty.makeup-requests.create', compact('rooms', 'departments', 'subjects', 'sections', 'userDepartment'));
    }

    // 📌 Store new request
    public function store(Request $request)
    {
        // Add debugging
        Log::info('Make up class request submission started', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        Log::info('Starting validation');

        try {
            $request->validate([
                'department_id' => 'required|exists:departments,id',
                'subject_id' => 'required|exists:subjects,id',
                'subject' => 'required|string|max:100',
                'subject_title' => 'required|string|max:200',
                'section_id' => 'required|exists:sections,id',
                'section' => 'required|string|max:200', // Increased from 50 to 200
                'room' => 'nullable|string|max:100',
                'reason' => 'required|string',
                'preferred_date' => 'required|date',
                'preferred_time' => 'required',
                'end_time' => 'required',
                'attachment' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
                'student_list' => 'required|file|mimes:csv,xlsx|max:4096',
                'semester' => 'nullable|string|max:50',
            ]);
            
            // Additional validation: Ensure subject belongs to selected department
            $subject = \App\Models\Subject::find($request->subject_id);
            if (!$subject || $subject->department_id != $request->department_id) {
                return back()->withErrors(['subject_id' => 'Selected subject does not belong to the selected department.'])->withInput();
            }
            
            // Additional validation: Ensure section belongs to selected department  
            $section = \App\Models\Section::find($request->section_id);
            if (!$section || $section->department_id != $request->department_id) {
                return back()->withErrors(['section_id' => 'Selected section does not belong to the selected department.'])->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            throw $e; // Re-throw to show validation errors
        }

        Log::info('Validation passed successfully');

        // If room is not provided, set to 'Temporary Room'
        $room = $request->room ?: 'Temporary Room';

        Log::info('Processing file uploads');

        // Handle file uploads
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            Log::info('Attachment uploaded: ' . $attachmentPath);
        }
        $studentListPath = null;
        if ($request->hasFile('student_list')) {
            $studentListPath = $request->file('student_list')->store('student_lists', 'public');
            Log::info('Student list uploaded: ' . $studentListPath);
        }

        // Generate tracking number
        $trackingNumber = 'REQ-' . strtoupper(Str::random(8));
        Log::info('Generated tracking number: ' . $trackingNumber);

        // Get faculty information for department_id
        /** @var \App\Models\User $faculty */
        $faculty = Auth::user();

        Log::info('About to create make up class request', [
            'faculty_id' => $faculty->id,
            'department_id' => $faculty->department_id ?? 1
        ]);

        try {
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

            Log::info('Make up class request created successfully', [
                'request_id' => $makeupRequest->id,
                'tracking_number' => $trackingNumber
            ]);

            // 📌 Send student confirmation emails IMMEDIATELY (before Department Chair approval)
            try {
                Log::info('Parsing student list and sending confirmation emails', [
                    'request_id' => $makeupRequest->id
                ]);

                // Parse CSV to get student emails and data
                $parsedData = $makeupRequest->parseStudentListFromCSV();
                $studentEmails = $parsedData['emails'];
                $studentData = $parsedData['data'];

                if (!empty($studentEmails)) {
                    Log::info('Sending confirmation emails to ' . count($studentEmails) . ' students');
                    $makeupRequest->notifyStudents($studentEmails, $studentData);
                    Log::info('Student confirmation emails sent successfully');
                } else {
                    Log::warning('No student emails found in CSV file');
                }
            } catch (\Exception $e) {
                Log::error('Failed to send student confirmation emails', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't fail the request creation if email sending fails
            }

            // 📌 Send faculty notification (database only - notification bell, same as student confirmation)
            try {
                // Use InstantMakeupNotification for both LIVE and LOCAL (database only for 'submitted' status - no email)
                $faculty->notify(new InstantMakeupNotification($makeupRequest, 'submitted'));
                Log::info('Faculty notification sent', [
                    'faculty_id' => $faculty->id,
                    'request_id' => $makeupRequest->id,
                    'environment' => app()->environment()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send faculty notification', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // NOTE: Department Chair notification is NOT sent here
            // It will be sent when faculty clicks "Submit Official Request" after student confirmations

            return redirect()->route('makeup-requests.index')->with('success', 'Make-up class request created! Student confirmation emails have been sent. Please wait for student responses before submitting the official request. Tracking: ' . $trackingNumber);

        } catch (\Exception $e) {
            Log::error('Failed to create make up class request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return back()->withErrors(['error' => 'Failed to submit request: ' . $e->getMessage()])->withInput();
        }
    }

    // 📌 Show edit form
    public function edit($id)
    {
        $request = MakeUpClassRequest::with(['subject.department', 'sectionRelation.department'])->where('faculty_id', Auth::id())->findOrFail($id);
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

        // 📌 Notify faculty of update (database only - notification bell, same as student confirmation)
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notify(new InstantMakeupNotification($req, 'updated'));

        return redirect()->route('makeup-requests.index')->with('success', 'Request updated successfully!');
    }

    // 📌 Show request details
    public function show($id)
    {
        $request = MakeUpClassRequest::with(['subject', 'sectionRelation'])->where('faculty_id', Auth::id())->findOrFail($id);
        return view('faculty.makeup-requests.show', compact('request'));
    }

    // 📌 Delete request
    public function destroy($id)
    {
        $req = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);
        $req->delete();

        return redirect()->route('makeup-requests.index')->with('success', 'Request deleted successfully!');
    }

    // 📌 Submit official request to Department Chair (after student confirmations)
    public function submitOfficialRequest($id)
    {
        $makeupRequest = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);

        // Validate that request is still pending
        if ($makeupRequest->status !== 'pending') {
            return redirect()->route('faculty.student-confirmations')
                ->with('error', 'This request has already been submitted or processed.');
        }

        // Validate minimum confirmed students (at least 1)
        if (!$makeupRequest->hasMinimumConfirmedStudents(1)) {
            return redirect()->route('faculty.student-confirmations')
                ->with('error', 'You need at least 1 confirmed student before submitting the official request.');
        }

        try {
            // Mark as officially submitted to Department Chair
            $makeupRequest->submitted_to_chair_at = now();
            $makeupRequest->save();

            // Notify Department Chair
            $makeupRequest->notifyDepartmentChair();

            Log::info('Official request submitted to Department Chair', [
                'request_id' => $makeupRequest->id,
                'faculty_id' => Auth::id(),
                'confirmed_students' => $makeupRequest->confirmations()->where('status', 'confirmed')->count(),
                'submitted_at' => $makeupRequest->submitted_to_chair_at
            ]);

            return redirect()->route('faculty.student-confirmations')
                ->with('success', 'Official request has been submitted to Department Chair for approval!');
        } catch (\Exception $e) {
            Log::error('Failed to submit official request', [
                'request_id' => $makeupRequest->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('faculty.student-confirmations')
                ->with('error', 'Failed to submit official request: ' . $e->getMessage());
        }
    }

    // 📌 Upload proof of conduct (for ISO requirements) - Multiple images
    public function uploadProofOfConduct(Request $request, $id)
    {
        $makeupRequest = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);

        // Only allow upload for APPROVED requests
        if ($makeupRequest->status !== 'APPROVED') {
            return redirect()->route('makeup-requests.show', $id)
                ->with('error', 'You can only upload proof of conduct for approved makeup classes.');
        }

        $request->validate([
            'proof_of_conduct.*' => 'required|image|mimes:jpeg,jpg,png|max:15360', // Max 15MB per image (for high-quality phone cameras)
        ], [
            'proof_of_conduct.*.required' => 'Please select at least one image.',
            'proof_of_conduct.*.image' => 'Each file must be an image.',
            'proof_of_conduct.*.mimes' => 'Each image must be a JPEG, JPG, or PNG file.',
            'proof_of_conduct.*.max' => 'Each image must not exceed 15MB. If your image is too large, please compress it or reduce the quality.',
        ]);

        try {
            // Get existing proofs (if any)
            $existingProofs = $makeupRequest->proof_of_conduct ?? [];

            // Store new proof images
            $newProofs = [];
            if ($request->hasFile('proof_of_conduct')) {
                foreach ($request->file('proof_of_conduct') as $file) {
                    $proofPath = $file->store('proof_of_conduct', 'public');
                    $newProofs[] = $proofPath;
                }
            }

            // Merge with existing proofs
            $allProofs = array_merge($existingProofs, $newProofs);
            $makeupRequest->proof_of_conduct = $allProofs;
            $makeupRequest->save();

            Log::info('Proof of conduct uploaded', [
                'request_id' => $makeupRequest->id,
                'faculty_id' => Auth::id(),
                'new_images_count' => count($newProofs),
                'total_images_count' => count($allProofs)
            ]);

            $message = count($newProofs) > 1 
                ? count($newProofs) . ' proof images uploaded successfully!'
                : 'Proof image uploaded successfully!';

            return redirect()->route('makeup-requests.show', $id)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to upload proof of conduct', [
                'request_id' => $makeupRequest->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('makeup-requests.show', $id)
                ->with('error', 'Failed to upload proof: ' . $e->getMessage());
        }
    }

    // 📌 Delete a single proof image
    public function deleteProofImage($id, $imageIndex)
    {
        $makeupRequest = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);

        // Only allow delete for APPROVED requests
        if ($makeupRequest->status !== 'APPROVED') {
            return redirect()->route('makeup-requests.show', $id)
                ->with('error', 'You can only manage proof of conduct for approved makeup classes.');
        }

        try {
            $proofs = $makeupRequest->proof_of_conduct ?? [];
            $imageIndex = (int) $imageIndex; // Ensure it's an integer

            if (isset($proofs[$imageIndex])) {
                // Delete file from storage
                $imagePath = storage_path('app/public/' . $proofs[$imageIndex]);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Remove from array
                unset($proofs[$imageIndex]);
                $proofs = array_values($proofs); // Re-index array

                $makeupRequest->proof_of_conduct = $proofs;
                $makeupRequest->save();

                Log::info('Proof image deleted', [
                    'request_id' => $makeupRequest->id,
                    'faculty_id' => Auth::id(),
                    'image_index' => $imageIndex
                ]);

                return redirect()->route('makeup-requests.show', $id)
                    ->with('success', 'Proof image deleted successfully!');
            }

            return redirect()->route('makeup-requests.show', $id)
                ->with('error', 'Image not found.');
        } catch (\Exception $e) {
            Log::error('Failed to delete proof image', [
                'request_id' => $makeupRequest->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('makeup-requests.show', $id)
                ->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    // 📌 Generate PDF of student list for printing (from CSV file)
    public function printStudentList($id)
    {
        $makeupRequest = MakeUpClassRequest::where('faculty_id', Auth::id())->findOrFail($id);

        // Parse CSV file to get ALL students (not just confirmed)
        $parsedData = $makeupRequest->parseStudentListFromCSV();
        $studentEmails = $parsedData['emails'];
        $studentData = $parsedData['data'];

        // Get confirmation status for each student (if available)
        $confirmations = $makeupRequest->confirmations()
            ->whereIn('student_email', $studentEmails)
            ->get()
            ->keyBy('student_email');

        // Build student list with confirmation status
        $students = [];
        foreach ($studentEmails as $email) {
            $confirmation = $confirmations->get($email);
            $students[] = [
                'email' => $email,
                'student_id' => $studentData[$email]['student_id'] ?? ($confirmation->student_id_number ?? 'N/A'),
                'name' => $studentData[$email]['name'] ?? ($confirmation->student_name ?? explode('@', $email)[0]),
                'confirmed' => $confirmation ? ($confirmation->status === 'confirmed') : false,
                'status' => $confirmation ? $confirmation->status : 'pending'
            ];
        }

        // Sort by name
        usort($students, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        // Simple HTML view for printing (can be enhanced with PDF library later)
        $html = view('faculty.makeup-requests.print-student-list', [
            'makeupRequest' => $makeupRequest,
            'students' => $students
        ])->render();

        return response($html)
            ->header('Content-Type', 'text/html');
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

    // 📌 Show all approved requests for proof upload
    public function proofUploadIndex()
    {
        $approvedRequests = MakeUpClassRequest::with(['subject', 'sectionRelation'])
            ->where('faculty_id', Auth::id())
            ->where('status', 'APPROVED')
            ->latest()
            ->get();
        
        return view('faculty.proof-upload.index', compact('approvedRequests'));
    }
}
