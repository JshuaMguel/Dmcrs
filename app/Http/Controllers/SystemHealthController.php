<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MakeUpClassRequest;

class SystemHealthController extends Controller
{
    public function checkSystem()
    {
        $status = [
            'database' => $this->checkDatabase(),
            'authentication' => $this->checkAuthentication(),
            'routes' => $this->checkRoutes(),
            'permissions' => $this->checkPermissions(),
            'notifications' => $this->checkNotifications(),
        ];

        return response()->json($status);
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            $userCount = User::count();
            $requestCount = MakeUpClassRequest::count();

            return [
                'status' => 'OK',
                'users' => $userCount,
                'requests' => $requestCount,
                'connection' => 'Connected'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }

    private function checkAuthentication()
    {
        try {
            return [
                'status' => 'OK',
                'authenticated' => Auth::check(),
                'user_role' => Auth::check() ? Auth::user()->role : null
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }

    private function checkRoutes()
    {
        $routes = [
            'faculty.dashboard' => route('faculty.dashboard', [], false),
            'department.dashboard' => route('department.dashboard', [], false),
            'head.dashboard' => route('head.dashboard', [], false),
            'admin.dashboard' => route('admin.dashboard', [], false),
        ];

        return [
            'status' => 'OK',
            'routes' => $routes
        ];
    }

    private function checkPermissions()
    {
        if (!Auth::check()) {
            return ['status' => 'NOT_AUTHENTICATED'];
        }

        $user = Auth::user();
        $permissions = [];

        // Check role-based permissions
        switch ($user->role) {
            case 'faculty':
                $permissions['can_create_requests'] = true;
                $permissions['can_approve'] = false;
                break;
            case 'department_chair':
                $permissions['can_create_requests'] = false;
                $permissions['can_approve'] = true;
                $permissions['can_view_department_requests'] = true;
                break;
            case 'academic_head':
                $permissions['can_create_requests'] = false;
                $permissions['can_approve'] = true;
                $permissions['can_view_all_requests'] = true;
                break;
            case 'super_admin':
                $permissions['can_manage_users'] = true;
                $permissions['can_manage_system'] = true;
                break;
        }

        return [
            'status' => 'OK',
            'role' => $user->role,
            'permissions' => $permissions
        ];
    }

    private function checkNotifications()
    {
        try {
            if (Auth::check()) {
                $unreadCount = Auth::user()->unreadNotifications->count();
                return [
                    'status' => 'OK',
                    'unread_notifications' => $unreadCount
                ];
            }
            return ['status' => 'NOT_AUTHENTICATED'];
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }
}
