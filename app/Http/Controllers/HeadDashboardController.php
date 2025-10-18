<?php

namespace App\Http\Controllers;

use App\Models\MakeUpClassRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeadDashboardController extends Controller
{
    /**
     * Display the academic head dashboard summary and latest requests.
     */
    public function index(): View
    {
    $pendingCount = MakeUpClassRequest::whereIn('status', ['pending', 'CHAIR_APPROVED'])->count();
        $approvedCount = MakeUpClassRequest::where('status', 'APPROVED')->count();
        $rejectedCount = MakeUpClassRequest::where('status', 'HEAD_REJECTED')->count();

        $latestPending = MakeUpClassRequest::with('subject')
            ->whereIn('status', ['pending', 'CHAIR_APPROVED'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('head.dashboard', compact('pendingCount', 'approvedCount', 'rejectedCount', 'latestPending'));
    }
}
