<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserAccountNotification;
use App\Services\BrevoApiService;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::whereIn('role', ['faculty', 'department_chair', 'academic_head'])->get();
        $departments = Department::all();
        return view('admin.users', compact('users', 'departments'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:faculty,department_chair,academic_head',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $departmentId = null;
        if (in_array($request->role, ['faculty', 'department_chair'])) {
            $departmentId = $request->department_id;
        }

        // Store plain password for email before hashing
        $plainPassword = $request->password;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department_id' => $departmentId,
        ]);

        // Load department relationship if exists
        $user->load('department');

        try {
            // Send email notification with account details using Brevo API
            Log::info('Starting email send process for new user', [
                'user_email' => $user->email,
                'user_id' => $user->id
            ]);

            $brevoService = app(BrevoApiService::class);
            Log::info('Brevo service initialized successfully');
            
            $subject = 'Your DMCRS Account Details';
            
            // Check if view exists
            if (!view()->exists('emails.new-user-account')) {
                throw new \Exception('Email template emails.new-user-account not found');
            }
            
            $htmlContent = view('emails.new-user-account', [
                'user' => $user,
                'password' => $plainPassword
            ])->render();

            Log::info('Email template rendered successfully', ['content_length' => strlen($htmlContent)]);

            $result = $brevoService->sendEmail(
                $user->email,
                $subject,
                $htmlContent,
                null,
                'USTP Balubal Campus - DMCRS',
                'ustpbalubal.dmcrs@gmail.com'
            );

            if ($result) {
                Log::info('User account email sent successfully via Brevo API', [
                    'user_email' => $user->email,
                    'user_id' => $user->id
                ]);
                return redirect()->route('admin.users')->with('success', 'User created successfully and account details sent via email.');
            } else {
                Log::error('Brevo API returned false for email send');
                return redirect()->route('admin.users')->with('success', 'User created successfully. However, email notification could not be sent.');
            }
        } catch (\Exception $e) {
            // Log the detailed error
            Log::error('Failed to send new user notification email via Brevo API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_email' => $user->email ?? 'unknown'
            ]);

            return redirect()->route('admin.users')->with('success', 'User created successfully. However, email notification could not be sent.');
        }
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        return view('admin.edit-user', compact('user', 'departments'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:faculty,department_chair,academic_head',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'required|boolean',
        ]);
        $departmentId = null;
        if (in_array($request->role, ['faculty', 'department_chair'])) {
            $departmentId = $request->department_id;
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department_id' => $departmentId,
            'is_active' => $request->is_active,
        ]);

        $statusText = $request->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users')->with('success', "User updated and {$statusText} successfully.");
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
    // Department Management
    public function departments()
    {
        $departments = Department::all();
        return view('admin.departments', compact('departments'));
    }
    public function createDepartment(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Department::create(['name' => $request->name]);
        return redirect()->route('admin.departments')->with('success', 'Department created successfully.');
    }
    public function editDepartment($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.edit-department', compact('department'));
    }
    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $department->update(['name' => $request->name]);
        return redirect()->route('admin.departments')->with('success', 'Department updated successfully.');
    }
    public function deleteDepartment($id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->route('admin.departments')->with('success', 'Department deleted successfully.');
    }

    // System Settings
    public function settings()
    {
        // Get current settings from config or database
        $settings = [
            'system_name' => config('app.name'),
            'system_description' => 'Department Makeup Class Request System',
            'admin_email' => 'admin@ustp.edu.ph',
            'support_email' => 'support@ustp.edu.ph',
            'session_timeout' => config('session.lifetime'),
            'max_login_attempts' => 5,
            'password_min_length' => 8,
            'lockout_duration' => 15,
            'max_advance_days' => 30,
            'min_notice_hours' => 24,
            'auto_approve_threshold' => 72,
            'max_daily_requests' => 3,
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'system_name' => 'required|string|max:255',
            'system_description' => 'nullable|string|max:500',
            'admin_email' => 'required|email|max:255',
            'support_email' => 'required|email|max:255',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'password_min_length' => 'required|integer|min:6|max:32',
            'lockout_duration' => 'required|integer|min:5|max:60',
            'max_advance_days' => 'required|integer|min:1|max:90',
            'min_notice_hours' => 'required|integer|min:1|max:168',
            'auto_approve_threshold' => 'required|integer|min:0|max:168',
            'max_daily_requests' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Here you would typically save to database or config files
            // For now, we'll simulate successful update

            // You can implement actual settings storage here
            // Example: Store in database settings table or update config files

            // Log the settings update
            Log::info('System settings updated by admin', [
                'admin_id' => Auth::id(),
                'settings' => $request->all()
            ]);

            return redirect()->route('admin.settings')->with('success', 'System settings have been updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating system settings: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Failed to update system settings. Please try again.');
        }
    }

    // Separate functions for each settings tab

    /**
     * Update General Settings (Tab 1)
     */
    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'system_name' => 'required|string|max:255',
            'system_description' => 'nullable|string|max:500',
            'admin_email' => 'required|email|max:255',
            'support_email' => 'required|email|max:255',
        ]);

        try {
            // Update general settings in database or config
            // Implementation here for saving general settings

            Log::info('General settings updated by admin', [
                'admin_id' => Auth::id(),
                'settings' => $request->only(['system_name', 'system_description', 'admin_email', 'support_email'])
            ]);

            return redirect()->route('admin.settings')->with('success', 'General settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating general settings: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Failed to update general settings.');
        }
    }

    /**
     * Update Security Settings (Tab 2)
     */
    public function updateSecuritySettings(Request $request)
    {
        $request->validate([
            'session_timeout' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'password_min_length' => 'required|integer|min:6|max:32',
            'lockout_duration' => 'required|integer|min:5|max:60',
        ]);

        try {
            // Update security settings in database or config
            // Implementation here for saving security settings

            Log::info('Security settings updated by admin', [
                'admin_id' => Auth::id(),
                'settings' => $request->only(['session_timeout', 'max_login_attempts', 'password_min_length', 'lockout_duration'])
            ]);

            return redirect()->route('admin.settings')->with('success', 'Security settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating security settings: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Failed to update security settings.');
        }
    }

    /**
     * Update Makeup Class Settings (Tab 3)
     */
    public function updateMakeupSettings(Request $request)
    {
        $request->validate([
            'max_advance_days' => 'required|integer|min:1|max:90',
            'min_notice_hours' => 'required|integer|min:1|max:168',
            'auto_approve_threshold' => 'required|integer|min:0|max:168',
            'max_daily_requests' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Update makeup class settings in database or config
            // Implementation here for saving makeup class settings

            Log::info('Makeup class settings updated by admin', [
                'admin_id' => Auth::id(),
                'settings' => $request->only(['max_advance_days', 'min_notice_hours', 'auto_approve_threshold', 'max_daily_requests'])
            ]);

            return redirect()->route('admin.settings')->with('success', 'Makeup class settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating makeup class settings: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Failed to update makeup class settings.');
        }
    }

    /**
     * Get System Information (Tab 4 - Read Only)
     */
    public function getSystemInfo()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'total_users' => User::count(),
            'total_departments' => Department::count(),
            'disk_space' => $this->getDiskSpace(),
            'last_backup' => 'Not configured', // Implement backup tracking
        ];

        return response()->json($systemInfo);
    }

    /**
     * Helper function to get disk space information
     */
    private function getDiskSpace()
    {
        $bytes = disk_total_space(base_path());
        $free = disk_free_space(base_path());

        if ($bytes && $free) {
            $used = $bytes - $free;
            $usedPercent = round(($used / $bytes) * 100, 2);

            return [
                'total' => $this->formatBytes($bytes),
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($free),
                'used_percent' => $usedPercent
            ];
        }

        return ['total' => 'Unknown', 'used' => 'Unknown', 'free' => 'Unknown', 'used_percent' => 0];
    }

    /**
     * Helper function to format bytes to readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
