<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChairDashboardController extends Controller
{
    public function scheduleBoard(Request $request)
    {
        $chair = Auth::user();
        $query = DB::table('schedules')
            ->where('department_id', $chair->department_id)
            ->select('*');
        // Add filters if needed
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->input('day_of_week'));
        }
        if ($request->filled('subject_code')) {
            $query->where('subject_code', 'like', '%' . $request->input('subject_code') . '%');
        }
        if ($request->filled('section')) {
            $query->where('section', $request->input('section'));
        }
        if ($request->filled('room')) {
            $query->where('room', $request->input('room'));
        }
        $schedules = $query->orderBy('day_of_week')->orderBy('time_start')->get();
        return view('department.schedule', compact('schedules'));
    }
}
