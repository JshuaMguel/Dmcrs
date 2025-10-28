<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Models\MakeUpClassConfirmation;
use App\Models\User;
use App\Notifications\MakeupClassStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MakeupClassController extends Controller
{
    public function confirmAttendance($id, $encryptedEmail)
    {
        try {
            $email = Crypt::decrypt($encryptedEmail);
            $makeupRequest = MakeUpClassRequest::findOrFail($id);
            
            // Find the student by email
            $student = User::where('email', $email)->first();
            if (!$student) {
                abort(404, 'Student not found');
            }

            // Save or update the confirmation
            $confirmation = MakeUpClassConfirmation::updateOrCreate([
                'make_up_class_request_id' => $makeupRequest->id,
                'student_id' => $student->id,
            ], [
                'status' => 'confirmed',
                'reason' => null,
                'attended' => true, // Mark as attended when confirmed
                'confirmation_date' => now(),
            ]);

            // Notify faculty about the confirmation
            $faculty = $makeupRequest->faculty;
            $faculty->notify(new MakeupClassStatusNotification($makeupRequest, 'confirmed', null, $student));

            return view('makeup-class.confirmed', [
                'makeupRequest' => $makeupRequest,
                'student' => $student
            ]);
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function showDeclineForm($id, $encryptedEmail)
    {
        try {
            $email = Crypt::decrypt($encryptedEmail);
            $makeupRequest = MakeUpClassRequest::findOrFail($id);

            return view('makeup-class.decline-form', [
                'makeupRequest' => $makeupRequest,
                'encryptedEmail' => $encryptedEmail
            ]);
        } catch (\Exception $e) {
            abort(403);
        }
    }

    public function declineAttendance(Request $request, $id, $encryptedEmail)
    {
        try {
            $email = Crypt::decrypt($encryptedEmail);
            $makeupRequest = MakeUpClassRequest::findOrFail($id);
            
            // Find the student by email
            $student = User::where('email', $email)->first();
            if (!$student) {
                abort(404, 'Student not found');
            }

            $request->validate([
                'reason' => 'required|string|min:10'
            ]);

            // Save or update the confirmation with decline reason
            $confirmation = MakeUpClassConfirmation::updateOrCreate([
                'make_up_class_request_id' => $makeupRequest->id,
                'student_id' => $student->id,
            ], [
                'status' => 'declined',
                'reason' => $request->reason,
                'attended' => false, // Mark as not attended when declined
                'confirmation_date' => now(),
            ]);

            // Notify faculty about the decline with reason
            $faculty = $makeupRequest->faculty;
            $faculty->notify(new MakeupClassStatusNotification($makeupRequest, 'declined', $request->reason, $student));

            return view('makeup-class.declined', [
                'makeupRequest' => $makeupRequest,
                'student' => $student
            ]);
        } catch (\Exception $e) {
            abort(403);
        }
    }
}
