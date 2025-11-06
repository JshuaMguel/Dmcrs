<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentChairDashboardController;
use App\Http\Controllers\AcademicHeadDashboardController;
use App\Http\Controllers\AdminController;



// Department Chair routes
Route::middleware(['auth', 'role:department_chair'])->group(function () {
    Route::get('/department/dashboard', [DepartmentChairDashboardController::class, 'index'])->name('department.dashboard');
    Route::post('/department/makeup-requests/{id}/approve', [DepartmentChairDashboardController::class, 'approve'])->name('department.makeup-requests.approve');
    Route::post('/department/makeup-requests/{id}/reject', [DepartmentChairDashboardController::class, 'reject'])->name('department.makeup-requests.reject');
    Route::get('/department/approvals', [DepartmentChairDashboardController::class, 'approvals'])->name('department.approvals');
    Route::get('/department/schedule', [DepartmentChairDashboardController::class, 'schedule'])->name('department.schedule');
});

// Academic Head routes
Route::middleware(['auth', 'role:academic_head'])->group(function () {
    // Removed unused academic.dashboard route
    Route::post('/academic/makeup-requests/{id}/approve', [AcademicHeadDashboardController::class, 'approve'])->name('academic.makeup-requests.approve');
    Route::post('/academic/makeup-requests/{id}/reject', [AcademicHeadDashboardController::class, 'reject'])->name('academic.makeup-requests.reject');
    Route::post('/academic/makeup-requests/{id}/notify-students', [AcademicHeadDashboardController::class, 'notifyStudents'])->name('academic.makeup-requests.notify-students');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

    // Department Management
    Route::get('/admin/departments', [AdminController::class, 'departments'])->name('admin.departments');
    Route::post('/admin/departments', [AdminController::class, 'createDepartment'])->name('admin.createDepartment');
    Route::get('/admin/departments/{id}/edit', [AdminController::class, 'editDepartment'])->name('admin.editDepartment');
    Route::put('/admin/departments/{id}', [AdminController::class, 'updateDepartment'])->name('admin.updateDepartment');
    Route::delete('/admin/departments/{id}', [AdminController::class, 'deleteDepartment'])->name('admin.deleteDepartment');

    // Subject Management
    Route::resource('/admin/subjects', \App\Http\Controllers\AdminSubjectController::class, [
        'names' => [
            'index' => 'admin.subjects.index',
            'create' => 'admin.subjects.create',
            'store' => 'admin.subjects.store',
            'show' => 'admin.subjects.show',
            'edit' => 'admin.subjects.edit',
            'update' => 'admin.subjects.update',
            'destroy' => 'admin.subjects.destroy',
        ]
    ]);
    Route::get('/admin/subjects-by-department', [\App\Http\Controllers\AdminSubjectController::class, 'getByDepartment'])->name('admin.subjects.by-department');

    // Section Management
    Route::resource('/admin/sections', \App\Http\Controllers\AdminSectionController::class, [
        'names' => [
            'index' => 'admin.sections.index',
            'create' => 'admin.sections.create',
            'store' => 'admin.sections.store',
            'edit' => 'admin.sections.edit',
            'update' => 'admin.sections.update',
            'destroy' => 'admin.sections.destroy',
        ]
    ]);
    Route::get('/admin/sections-by-department', [\App\Http\Controllers\AdminSectionController::class, 'getByDepartment'])->name('admin.sections.by-department');

    // Schedule Management
    Route::get('/admin/schedules/board', [\App\Http\Controllers\Admin\ScheduleController::class, 'board'])->name('admin.schedules.board');
    Route::resource('/admin/schedules', \App\Http\Controllers\Admin\ScheduleController::class, [
        'names' => [
            'index' => 'admin.schedules.index',
            'create' => 'admin.schedules.create',
            'store' => 'admin.schedules.store',
            'edit' => 'admin.schedules.edit',
            'update' => 'admin.schedules.update',
            'destroy' => 'admin.schedules.destroy',
        ]
    ]);

    // Room Management
    Route::resource('/admin/rooms', \App\Http\Controllers\Admin\RoomController::class, [
        'names' => [
            'index' => 'admin.rooms.index',
            'create' => 'admin.rooms.create',
            'store' => 'admin.rooms.store',
            'edit' => 'admin.rooms.edit',
            'update' => 'admin.rooms.update',
            'destroy' => 'admin.rooms.destroy',
        ]
    ]);

    // System Settings
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.updateSettings');

    // Separate Settings Routes
    Route::post('/admin/settings/general', [AdminController::class, 'updateGeneralSettings'])->name('admin.settings.general');
    Route::post('/admin/settings/security', [AdminController::class, 'updateSecuritySettings'])->name('admin.settings.security');
    Route::post('/admin/settings/makeup', [AdminController::class, 'updateMakeupSettings'])->name('admin.settings.makeup');
    Route::get('/admin/settings/system-info', [AdminController::class, 'getSystemInfo'])->name('admin.settings.system-info');

    // Database Management
    Route::get('/admin/database', [\App\Http\Controllers\Admin\DatabaseController::class, 'index'])->name('admin.database.index');
    Route::get('/admin/database/table/{table}', [\App\Http\Controllers\Admin\DatabaseController::class, 'table'])->name('admin.database.table');
    Route::delete('/admin/database/table/{table}/record/{id}', [\App\Http\Controllers\Admin\DatabaseController::class, 'deleteRecord'])->name('admin.database.delete-record');
    Route::post('/admin/database/table/{table}/truncate', [\App\Http\Controllers\Admin\DatabaseController::class, 'truncateTable'])->name('admin.database.truncate');
});
