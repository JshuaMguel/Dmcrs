<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MakeupClassController extends Controller
{
    public function confirmAttendance($id, $encryptedEmail)
    {
        try {
            $email = Crypt::decrypt($encryptedEmail);
            $makeupRequest = MakeUpClassRequest::findOrFail($id);

            // Here you would typically update a student responses table
            // For now, we'll just return a success message
            return view('makeup-class.confirmed', [
                'makeupRequest' => $makeupRequest
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

            $request->validate([
                'reason' => 'required|string|min:10'
            ]);

            // Here you would typically update a student responses table
            // For now, we'll just return a success message
            return view('makeup-class.declined', [
                'makeupRequest' => $makeupRequest
            ]);
        } catch (\Exception $e) {
            abort(403);
        }
    }
}
