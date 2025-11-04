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
            
            // Find the student by email, or create a temporary record for non-registered students
            $student = User::where('email', $email)->first();
            
            // Get student info from CSV if available
            $studentInfo = $this->getStudentInfoFromCSV($makeupRequest, $email);
            
            if (!$student) {
                // For non-registered students from CSV, create a virtual student record
                $student = (object) [
                    'id' => null,
                    'name' => $studentInfo['name'] ?? 'Student',
                    'email' => $email,
                    'role' => 'student'
                ];
            }

            // Save or update the confirmation
            $confirmation = MakeUpClassConfirmation::updateOrCreate([
                'make_up_class_request_id' => $makeupRequest->id,
                'student_id' => $student->id, // This will be null for non-registered students
                'student_email' => $email, // Store email for non-registered students
            ], [
                'status' => 'confirmed',
                'reason' => null,
                'attended' => true, // Mark as attended when confirmed
                'confirmation_date' => now(),
                'student_id_number' => $studentInfo['student_id'] ?? null,
                'student_name' => $studentInfo['name'] ?? null,
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
                'encryptedEmail' => $encryptedEmail,
                'email' => $email
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
            
            // Find the student by email, or create a temporary record for non-registered students
            $student = User::where('email', $email)->first();
            
            // Get student info from CSV if available
            $studentInfo = $this->getStudentInfoFromCSV($makeupRequest, $email);
            
            if (!$student) {
                // For non-registered students from CSV, create a virtual student record
                $student = (object) [
                    'id' => null,
                    'name' => $studentInfo['name'] ?? ('Student (' . explode('@', $email)[0] . ')'),
                    'email' => $email,
                    'role' => 'student'
                ];
            }

            $request->validate([
                'reason' => 'required|string|min:10'
            ]);

            // Save or update the confirmation with decline reason
            $confirmation = MakeUpClassConfirmation::updateOrCreate([
                'make_up_class_request_id' => $makeupRequest->id,
                'student_id' => $student->id, // This will be null for non-registered students
                'student_email' => $email, // Store email for non-registered students
            ], [
                'status' => 'declined',
                'reason' => $request->reason,
                'attended' => false, // Mark as not attended when declined
                'confirmation_date' => now(),
                'student_id_number' => $studentInfo['student_id'] ?? null,
                'student_name' => $studentInfo['name'] ?? null,
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
    
    /**
     * Get student information from CSV file
     */
    private function getStudentInfoFromCSV($makeupRequest, $email)
    {
        $studentInfo = ['email' => $email, 'student_id' => null, 'name' => null];
        
        if (!$makeupRequest->student_list) {
            return $studentInfo;
        }
        
        $path = storage_path('app/public/' . $makeupRequest->student_list);
        if (!file_exists($path)) {
            return $studentInfo;
        }
        
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension !== 'csv') {
            return $studentInfo;
        }
        
        try {
            $file = fopen($path, 'r');
            $header = fgetcsv($file);
            
            // Find column indices
            $emailColumnIndex = -1;
            $studentIdColumnIndex = -1;
            $nameColumnIndex = -1;

            if ($header) {
                foreach ($header as $index => $columnName) {
                    $columnName = strtolower(trim($columnName));
                    if ($columnName === 'email') {
                        $emailColumnIndex = $index;
                    } elseif (in_array($columnName, ['student id', 'student_id', 'id'])) {
                        $studentIdColumnIndex = $index;
                    } elseif (in_array($columnName, ['name', 'student name', 'student_name', 'full name'])) {
                        $nameColumnIndex = $index;
                    }
                }
            }

            // Find the row with matching email
            while (($row = fgetcsv($file)) !== false) {
                if ($emailColumnIndex >= 0 && isset($row[$emailColumnIndex])) {
                    $rowEmail = trim($row[$emailColumnIndex]);
                    if ($rowEmail === $email) {
                        $studentInfo['student_id'] = $studentIdColumnIndex >= 0 && isset($row[$studentIdColumnIndex]) ? trim($row[$studentIdColumnIndex]) : null;
                        $studentInfo['name'] = $nameColumnIndex >= 0 && isset($row[$nameColumnIndex]) ? trim($row[$nameColumnIndex]) : null;
                        break;
                    }
                }
            }
            
            fclose($file);
        } catch (\Exception $e) {
            // If parsing fails, return basic info
        }
        
        return $studentInfo;
    }
}
