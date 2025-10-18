<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HeadScheduleController extends Controller
{
    // CSV upload logic for schedules removed. Schedules are now managed via database only.
    /**
     * Display the master schedule board for Academic Head.
     */
    public function index(Request $request): View
    {
        // Unified board view (view-only)
        $canManage = false;
        $selectedDay = $request->get('day', 'Monday');

        $schedules = \App\Models\Schedule::with(['instructor','department'])
            ->where('day_of_week', $selectedDay)
            ->orderBy('time_start')
            ->get();

        $rooms = \App\Models\Room::orderBy('name')->get();

        // Time slots (7:00 AM to 8:00 PM, 30-minute intervals)
        $timeSlots = [];
        $start = strtotime('07:00');
        $end = strtotime('20:00');
        for ($time = $start; $time <= $end; $time += 1800) {
            $timeSlots[] = date('g:i A', $time);
        }

        return view('admin.schedules.board', compact('schedules','rooms','timeSlots','selectedDay','canManage'));
    }

    /**
     * Display the schedule board view for Academic Head.
     */
    public function board(Request $request): View
    {
        // Unified board view (view-only)
        $canManage = false;
        $selectedDay = $request->get('day', 'Monday');

        $schedules = \App\Models\Schedule::with(['instructor','department'])
            ->where('day_of_week', $selectedDay)
            ->orderBy('time_start')
            ->get();

        $rooms = \App\Models\Room::orderBy('name')->get();

        $timeSlots = [];
        $start = strtotime('07:00');
        $end = strtotime('20:00');
        for ($time = $start; $time <= $end; $time += 1800) {
            $timeSlots[] = date('g:i A', $time);
        }

        return view('admin.schedules.board', compact('schedules','rooms','timeSlots','selectedDay','canManage'));
    }
}
