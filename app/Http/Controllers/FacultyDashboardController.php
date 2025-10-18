<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FacultyDashboardController extends Controller
{
    public function index()
    {
        return view('faculty.dashboard', [
            'title' => 'Faculty Dashboard',
        ]);
    }

    public function scheduleBoard(Request $request)
    {
        // Use the unified board layout in admin/schedules/board but view-only for faculty
        $canManage = false;
        $selectedDay = $request->get('day', 'Monday');
        $schedules = \App\Models\Schedule::with(['instructor','department'])
            ->where('day_of_week', $selectedDay)
            ->orderBy('time_start')
            ->get();
        $rooms = \App\Models\Room::orderBy('name')->get();
        // Generate time slots (7:00 AM to 8:00 PM, 30-minute intervals)
        $timeSlots = [];
        $start = strtotime('07:00');
        $end = strtotime('20:00');
        for ($time = $start; $time <= $end; $time += 1800) {
            $timeSlots[] = date('g:i A', $time);
        }
        return view('admin.schedules.board', compact('schedules','rooms','timeSlots','selectedDay','canManage'));
    }

}
