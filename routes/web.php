<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AcademicHeadDashboardController;
use App\Http\Controllers\DepartmentChairDashboardController;
use App\Http\Controllers\FacultyDashboardController;
use App\Http\Controllers\MakeUpClassRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\HeadDashboardController;
use App\Http\Controllers\HeadRequestController;
use App\Http\Controllers\HeadScheduleController;
use App\Http\Controllers\HeadReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Debug routes removed - Email system now working

// All test routes removed - Email system working properly

// Debug routes removed - Internal notifications working with InstantMakeupNotification

// 🔹 Role-based Dashboard Redirect
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'academic_head' => redirect()->route('head.dashboard'),
           'department_chair' => redirect()->route('department.dashboard'),
           'faculty' => redirect()->route('faculty.dashboard'),
           default => redirect()->route('fallback'), // Redirect to fallback if role is missing/invalid
    };
})->name('dashboard');

// 🔹 Role-based Routes
// Fallback route for authenticated users with session/role issues
Route::middleware(['auth', 'verified'])->get('/fallback', function () {
    return view('fallback');
})->name('fallback');
Route::middleware(['auth', 'verified'])->group(function () {
    // Removed unused academic.dashboard route

    // Department Chair Dashboard
    Route::get('/department/dashboard', [DepartmentChairDashboardController::class, 'index'])
        ->middleware('role:department_chair')
        ->name('department.dashboard');

    Route::get('/department/requests', [DepartmentChairDashboardController::class, 'requests'])
        ->middleware('role:department_chair')
        ->name('department.requests');

    Route::get('/department/requests/{id}', [DepartmentChairDashboardController::class, 'show'])
        ->middleware('role:department_chair')
        ->name('department.requests.show');

    Route::get('/department/history', [DepartmentChairDashboardController::class, 'history'])
        ->middleware('role:department_chair')
        ->name('department.history');

    // Export / Print routes for department chair
    Route::get('/department/history/export/pdf', [DepartmentChairDashboardController::class, 'exportHistoryPdf'])
        ->middleware('role:department_chair')
        ->name('department.history.export.pdf');
    Route::get('/department/history/export/excel', [DepartmentChairDashboardController::class, 'exportHistoryExcel'])
        ->middleware('role:department_chair')
        ->name('department.history.export.excel');
    Route::get('/department/approvals/export/pdf', [DepartmentChairDashboardController::class, 'exportApprovalsPdf'])
        ->middleware('role:department_chair')
        ->name('department.approvals.export.pdf');
    Route::get('/department/approvals/export/excel', [DepartmentChairDashboardController::class, 'exportApprovalsExcel'])
        ->middleware('role:department_chair')
        ->name('department.approvals.export.excel');
    Route::get('/department/history/print', [DepartmentChairDashboardController::class, 'printHistory'])
        ->middleware('role:department_chair')
        ->name('department.history.print');
    Route::get('/department/approvals/print', [DepartmentChairDashboardController::class, 'printApprovals'])
        ->middleware('role:department_chair')
        ->name('department.approvals.print');

    Route::post('/department/approve/{id}', [DepartmentChairDashboardController::class, 'approve'])
        ->middleware('role:department_chair')
        ->name('department.chair.approve');

    Route::post('/department/reject/{id}', [DepartmentChairDashboardController::class, 'reject'])
        ->middleware('role:department_chair')
        ->name('department.chair.reject');

    // Faculty Dashboard
    Route::get('/faculty/dashboard', [FacultyDashboardController::class, 'index'])
        ->middleware('role:faculty')
        ->name('faculty.dashboard');
});

// Notifications route for all authenticated users
Route::middleware(['auth'])->get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
// 🔹 Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include other route files
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/notification.php';
require __DIR__.'/student.php';

// 🔹 Faculty specific routes
Route::middleware(['auth', 'verified', 'role:faculty'])->group(function () {
    Route::get('/faculty/dashboard', [FacultyDashboardController::class, 'index'])->name('faculty.dashboard');
    Route::get('/faculty/schedule', [FacultyDashboardController::class, 'scheduleBoard'])->name('faculty.schedule');
    Route::get('/faculty/student-confirmations', [FacultyDashboardController::class, 'studentConfirmations'])->name('faculty.student-confirmations');

    Route::get('/faculty/makeup-requests', [MakeUpClassRequestController::class, 'index'])->name('makeup-requests.index');
    Route::get('/faculty/makeup-requests/create', [MakeUpClassRequestController::class, 'create'])->name('makeup-requests.create');
    Route::post('/faculty/makeup-requests', [MakeUpClassRequestController::class, 'store'])->name('makeup-requests.store');
    Route::get('/faculty/makeup-requests/{id}', [MakeUpClassRequestController::class, 'show'])->name('makeup-requests.show');
    Route::get('/faculty/makeup-requests/{id}/edit', [MakeUpClassRequestController::class, 'edit'])->name('makeup-requests.edit');
    Route::put('/faculty/makeup-requests/{id}', [MakeUpClassRequestController::class, 'update'])->name('makeup-requests.update');
    Route::delete('/faculty/makeup-requests/{id}', [MakeUpClassRequestController::class, 'destroy'])->name('makeup-requests.destroy');
    Route::post('/faculty/makeup-requests/{id}/submit-official', [MakeUpClassRequestController::class, 'submitOfficialRequest'])->name('makeup-requests.submit-official');
    Route::post('/faculty/makeup-requests/{id}/upload-proof', [MakeUpClassRequestController::class, 'uploadProofOfConduct'])->name('makeup-requests.upload-proof');
    Route::delete('/faculty/makeup-requests/{id}/proof/{imageIndex}', [MakeUpClassRequestController::class, 'deleteProofImage'])->name('makeup-requests.delete-proof-image');
    Route::get('/faculty/makeup-requests/{id}/print-student-list', [MakeUpClassRequestController::class, 'printStudentList'])->name('makeup-requests.print-student-list');
    Route::get('/faculty/sections-by-department', [MakeUpClassRequestController::class, 'getSectionsByDepartment'])->name('makeup-requests.sections-by-department');
    Route::get('/faculty/available-rooms', [MakeUpClassRequestController::class, 'getAvailableRooms'])->name('makeup-requests.available-rooms');
    
    // Proof of Conduct Upload
    Route::get('/faculty/proof-upload', [MakeUpClassRequestController::class, 'proofUploadIndex'])->name('proof-upload.index');

    // Faculty Class Schedule Board
    Route::get('/faculty/schedule', [FacultyDashboardController::class, 'scheduleBoard'])->name('faculty.schedule');
});

// 🔹 Academic Head specific routes
Route::middleware(['auth', 'verified', 'role:academic_head'])->group(function () {
    Route::get('/head/dashboard', [HeadDashboardController::class, 'index'])->name('head.dashboard');
    Route::get('/head/requests', [HeadRequestController::class, 'index'])->name('head.requests.index');
    Route::get('/head/requests/{id}', [HeadRequestController::class, 'show'])->name('head.requests.show');
    Route::post('/head/requests/{id}/approve', [HeadRequestController::class, 'approve'])->name('head.requests.approve');
    Route::post('/head/requests/{id}/reject', [HeadRequestController::class, 'reject'])->name('head.requests.reject');
    Route::get('/head/schedule', [HeadScheduleController::class, 'index'])->name('head.schedule.index');
    Route::get('/head/schedule/board', [HeadScheduleController::class, 'board'])->name('head.schedule.board');
    Route::post('/head/schedule/upload', [HeadScheduleController::class, 'upload'])->name('head.schedule.upload');
    Route::get('/head/reports', [HeadReportController::class, 'index'])->name('head.reports.index');
    Route::get('/head/reports/export-excel', [HeadReportController::class, 'exportExcel'])->name('head.reports.exportExcel');
    Route::get('/head/reports/export-pdf', [HeadReportController::class, 'exportPdf'])->name('head.reports.exportPdf');
    Route::get('/head/reports/print', [HeadReportController::class, 'print'])->name('head.reports.print');
    // Removed duplicate notification route. Use shared route below.
    // Academic Head Quick Access Routes - Redirect to Head controllers
    Route::get('/academic/requests', function() {
        return redirect()->route('head.requests.index');
    })->name('academic.requests');
    Route::get('/academic/history', function() {
        return redirect()->route('head.reports.index');
    })->name('academic.history');
    Route::get('/academic/approvals', function() {
        return redirect()->route('head.reports.index');
    })->name('academic.approvals');
    Route::get('/academic/schedule', function() {
        return redirect()->route('head.schedule.index');
    })->name('academic.schedule');
        // Schedule CRUD routes (Academic Head only)
        Route::middleware(['auth', 'role:academic_head'])->group(function () {
            Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
            Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
            Route::get('/schedules/{id}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
            Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
            Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        });
});

// View-only schedule board for all authenticated users (Faculty, Department Chair, Academic Head)
Route::middleware(['auth', 'verified'])->get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');

// FullCalendar API endpoint

// Student Make-Up Class Confirmation
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/student/makeup-class/confirm', [\App\Http\Controllers\StudentMakeupClassController::class, 'confirm'])
        ->name('student.makeup-class.confirm');
    Route::get('/student/makeup-class/decline', [\App\Http\Controllers\StudentMakeupClassController::class, 'declineForm'])
        ->name('student.makeup-class.decline');
    Route::post('/student/makeup-class/decline', [\App\Http\Controllers\StudentMakeupClassController::class, 'decline'])
        ->name('student.makeup-class.decline.submit');
});
