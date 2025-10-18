<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MakeUpClassRequest;
use App\Models\MakeUpClassConfirmation;

class StudentMakeupClassController extends Controller
{
    // Confirm attendance
    public function confirm(Request $request)
    {
        $requestId = $request->query('request_id');
        $studentId = Auth::id();
        MakeUpClassConfirmation::updateOrCreate([
            'make_up_class_request_id' => $requestId,
            'student_id' => $studentId,
        ], [
            'status' => 'confirmed',
            'reason' => null,
        ]);
        return redirect()->route('notifications.index')->with('success', 'Attendance confirmed.');
    }

    // Show decline form
    public function declineForm(Request $request)
    {
        $requestId = $request->query('request_id');
        return view('student.makeup_class.decline', compact('requestId'));
    }

    // Handle decline submission
    public function decline(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:make_up_class_requests,id',
            'reason' => 'required|string|max:255',
        ]);
        $studentId = Auth::id();
        MakeUpClassConfirmation::updateOrCreate([
            'make_up_class_request_id' => $request->input('request_id'),
            'student_id' => $studentId,
        ], [
            'status' => 'declined',
            'reason' => $request->input('reason'),
        ]);
        return redirect()->route('notifications.index')->with('success', 'Decline submitted.');
    }
}
