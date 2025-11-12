<?php
namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use App\Notifications\DatabaseOnlyMakeupNotification;
use App\Notifications\InstantMakeupNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class AcademicHeadDashboardController extends Controller
{
    /**
     * Display the academic head dashboard.
     */
    public function index(): View
    {
        $requests = MakeUpClassRequest::whereIn('status', ['CHAIR_APPROVED', 'HEAD_REJECTED', 'APPROVED'])->get();
        return view('academic.dashboard', compact('requests'));
    }

    /**
     * Approve a make-up class request
     */
    public function approve(Request $request, $id)
    {
        $makeupRequest = MakeUpClassRequest::findOrFail($id);
        $makeupRequest->status = 'APPROVED';
        $makeupRequest->head_remarks = $request->remarks;
        $makeupRequest->save();


        // Notify the faculty with error handling
        // Notify faculty directly to avoid duplicate notifications
        $faculty = $makeupRequest->faculty;
        if ($faculty) {
            try {
                // Use instant notification for all environments (no queue worker needed)
                $faculty->notify(new \App\Notifications\InstantMakeupNotification($makeupRequest, 'APPROVED', $request->remarks));
                Log::info('Faculty notification sent successfully');
            } catch (\Exception $e) {
                Log::warning('Faculty notification failed', ['error' => $e->getMessage()]);
            }
        }

        // Notify the department chair that request was approved by academic head
        $chair = \App\Models\User::where('role', 'department_chair')->first();
        if ($chair) {
            try {
                // Use instant notification for all environments (no queue worker needed)
                $chair->notify(new \App\Notifications\InstantMakeupNotification($makeupRequest, 'approved_by_head', $request->remarks));
                Log::info('Chair notification sent successfully');
            } catch (\Exception $e) {
                Log::warning('Chair notification failed', ['error' => $e->getMessage()]);
            }
        }

    // Read student emails from uploaded CSV file and notify students
        $studentEmails = [];
        if ($makeupRequest->student_list) {
            $path = storage_path('app/public/' . $makeupRequest->student_list);
            if (file_exists($path)) {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($extension === 'csv') {
                    $file = fopen($path, 'r');
                    $isHeader = true;
                    while (($row = fgetcsv($file)) !== false) {
                        // Skip header row
                        if ($isHeader) { $isHeader = false; continue; }
                        // Scan all columns for valid emails
                        foreach ($row as $cell) {
                            if (filter_var($cell, FILTER_VALIDATE_EMAIL)) {
                                $studentEmails[] = $cell;
                            }
                        }
                    }
                    fclose($file);
                }
            }
        }
        if ($studentEmails) {
            try {
                $makeupRequest->notifyStudents($studentEmails);
                Log::info('Student notifications sent successfully', ['count' => count($studentEmails)]);
                return redirect()->route('academic.dashboard')->with('success', 'Request approved and students notified via email');
            } catch (\Exception $e) {
                Log::warning('Student email notifications failed', ['error' => $e->getMessage()]);
                return redirect()->route('academic.dashboard')->with('success', 'Request approved successfully. However, student email notifications could not be sent due to email server issues.');
            }
        }

        return redirect()->route('academic.dashboard')->with('success', 'Request approved successfully');
    }

    /**
     * Reject a make-up class request
     */
    public function reject(Request $request, $id)
    {
        $makeupRequest = MakeUpClassRequest::findOrFail($id);
        $makeupRequest->status = 'HEAD_REJECTED';
        $makeupRequest->head_remarks = $request->remarks;
        $makeupRequest->save();

        // Notify the faculty directly about rejection
        $faculty = $makeupRequest->faculty;
        if ($faculty) {
            try {
                // Use instant notification for all environments (no queue worker needed)
                $faculty->notify(new \App\Notifications\InstantMakeupNotification($makeupRequest, 'HEAD_REJECTED', $request->remarks));
                Log::info('Faculty rejection notification sent successfully');
            } catch (\Exception $e) {
                Log::warning('Faculty rejection notification failed', ['error' => $e->getMessage()]);
            }
        }

        // Notify the department chair about rejection
        $chair = \App\Models\User::where('role', 'department_chair')->first();
        if ($chair) {
            try {
                // Use instant notification for all environments (no queue worker needed)
                $chair->notify(new \App\Notifications\InstantMakeupNotification($makeupRequest, 'rejected_by_head', $request->remarks));
                Log::info('Chair rejection notification sent successfully');
            } catch (\Exception $e) {
                Log::warning('Chair rejection notification failed', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('academic.dashboard')->with('success', 'Request rejected');
    }

    /**
     * Notify students about an approved make-up class
     */
    public function notifyStudents(Request $request, $id)
    {
        $request->validate([
            'student_emails' => 'required|array|min:1',
            'student_emails.*' => 'email'
        ]);

        $makeupRequest = MakeUpClassRequest::findOrFail($id);

        // Only allow notifying students for approved requests
        if ($makeupRequest->status !== 'APPROVED') {
            return redirect()->route('academic.dashboard')->with('error', 'Can only notify students for approved requests');
        }

        // Filter out empty emails
        $emails = array_filter($request->student_emails, function($email) {
            return !empty($email);
        });

        if (empty($emails)) {
            return redirect()->route('academic.dashboard')->with('error', 'Please provide at least one valid email address');
        }

        // Send notifications to students
        $makeupRequest->notifyStudents($emails);

        return redirect()->route('academic.dashboard')->with('success', 'Students have been notified');
    }
}
