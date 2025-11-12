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
        // Academic Head should only see requests approved by Department Chair (CHAIR_APPROVED)
        // NOT pending requests (those are still with Department Chair)
        $pendingCount = MakeUpClassRequest::where('status', 'CHAIR_APPROVED')->count();
        $approvedCount = MakeUpClassRequest::where('status', 'APPROVED')->count();
        $rejectedCount = MakeUpClassRequest::where('status', 'HEAD_REJECTED')->count();

        $latestPending = MakeUpClassRequest::with('subject')
            ->where('status', 'CHAIR_APPROVED') // Only show CHAIR_APPROVED, not pending
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('head.dashboard', compact('pendingCount', 'approvedCount', 'rejectedCount', 'latestPending'));
    }
}
