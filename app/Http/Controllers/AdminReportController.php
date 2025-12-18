<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AdminReportController extends Controller
{
    private function checkAdminAccess()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin', 'super admin', 'superadmin'])) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display the reports index page.
     */
    public function index()
    {
        $this->checkAdminAccess();

        return view('admin.reports.index');
    }

    /**
     * Display a specific report.
     */
    public function show($type)
    {
        $this->checkAdminAccess();

        $validTypes = ['users', 'departments', 'subjects', 'sections', 'schedules', 'rooms', 'students'];

        if (!in_array($type, $validTypes)) {
            abort(404, 'Report type not found.');
        }

        $data = $this->getReportData($type);

        return view('admin.reports.show', compact('type', 'data'));
    }

    /**
     * Export report to PDF.
     */
    public function exportPdf($type)
    {
        $this->checkAdminAccess();

        $validTypes = ['users', 'departments', 'subjects', 'sections', 'schedules', 'rooms', 'students'];

        if (!in_array($type, $validTypes)) {
            abort(404, 'Report type not found.');
        }

        $data = $this->getReportData($type);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', [
            'type' => $type,
            'data' => $data,
            'generatedAt' => now()->timezone(config('app.timezone')),
            'generatedBy' => Auth::user()->name
        ])->setPaper('a4', 'landscape');

        return $pdf->download($type . '_report_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export report to Excel.
     */
    public function exportExcel($type)
    {
        $this->checkAdminAccess();

        $validTypes = ['users', 'departments', 'subjects', 'sections', 'schedules', 'rooms', 'students'];

        if (!in_array($type, $validTypes)) {
            abort(404, 'Report type not found.');
        }

        $data = $this->getReportData($type);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers based on type
        $headers = $this->getReportHeaders($type);
        $sheet->fromArray([$headers], null, 'A1');

        // Style header row
        $sheet->getStyle('A1:' . $this->getColumnLetter(count($headers)) . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $this->getColumnLetter(count($headers)) . '1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:' . $this->getColumnLetter(count($headers)) . '1')->getFill()->getStartColor()->setRGB('023047');
        $sheet->getStyle('A1:' . $this->getColumnLetter(count($headers)) . '1')->getFont()->getColor()->setRGB('FFFFFF');

        // Add data rows
        $row = 2;
        foreach ($data as $item) {
            $rowData = $this->formatReportRow($type, $item);
            $sheet->fromArray([$rowData], null, 'A' . $row);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', $this->getColumnLetter(count($headers))) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = $type . '_report_' . date('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Get report data based on type.
     */
    private function getReportData($type)
    {
        switch ($type) {
            case 'users':
                return User::with('department')
                    ->whereIn('role', ['faculty', 'department_chair', 'academic_head'])
                    ->orderBy('name')
                    ->get();

            case 'departments':
                return Department::withCount(['users', 'subjects', 'sections', 'students'])
                    ->orderBy('name')
                    ->get();

            case 'subjects':
                return Subject::with('department')
                    ->orderBy('subject_code')
                    ->get();

            case 'sections':
                return Section::with('department')
                    ->orderBy('department_id')
                    ->orderBy('year_level')
                    ->orderBy('section_name')
                    ->get();

            case 'schedules':
                return Schedule::with(['department', 'instructor'])
                    ->orderBy('day_of_week')
                    ->orderBy('time_start')
                    ->get();

            case 'rooms':
                return Room::orderBy('name')->get();

            case 'students':
                return Student::with(['department', 'section'])
                    ->orderBy('last_name')
                    ->orderBy('first_name')
                    ->get();

            default:
                return collect();
        }
    }

    /**
     * Get report headers based on type.
     */
    private function getReportHeaders($type)
    {
        switch ($type) {
            case 'users':
                return ['Name', 'Email', 'Role', 'Department', 'Status', 'Created At'];

            case 'departments':
                return ['Name', 'Users Count', 'Subjects Count', 'Sections Count', 'Students Count', 'Created At'];

            case 'subjects':
                return ['Subject Code', 'Subject Title', 'Department', 'Created At'];

            case 'sections':
                return ['Section Name', 'Year Level', 'Department', 'Created At'];

            case 'schedules':
                return ['Subject Code', 'Section', 'Day', 'Time', 'Room', 'Instructor', 'Type', 'Status'];

            case 'rooms':
                return ['Name', 'Capacity', 'Created At'];

            case 'students':
                return ['Student ID', 'Name', 'Email', 'Department', 'Year Level', 'Section', 'Status'];

            default:
                return [];
        }
    }

    /**
     * Format report row data.
     */
    private function formatReportRow($type, $item)
    {
        switch ($type) {
            case 'users':
                return [
                    $item->name,
                    $item->email,
                    ucfirst(str_replace('_', ' ', $item->role)),
                    $item->department ? $item->department->name : 'N/A',
                    $item->is_active ? 'Active' : 'Inactive',
                    $item->created_at ? $item->created_at->format('M d, Y') : 'N/A',
                ];

            case 'departments':
                return [
                    $item->name,
                    $item->users_count ?? 0,
                    $item->subjects_count ?? 0,
                    $item->sections_count ?? 0,
                    $item->students_count ?? 0,
                    $item->created_at ? $item->created_at->format('M d, Y') : 'N/A',
                ];

            case 'subjects':
                return [
                    $item->subject_code,
                    $item->subject_title,
                    $item->department ? $item->department->name : 'N/A',
                    $item->created_at ? $item->created_at->format('M d, Y') : 'N/A',
                ];

            case 'sections':
                return [
                    $item->full_name ?? ($item->year_level . $item->section_name),
                    $item->year_level,
                    $item->department ? $item->department->name : 'N/A',
                    $item->created_at ? $item->created_at->format('M d, Y') : 'N/A',
                ];

            case 'schedules':
                $timeStr = $item->time_start ? \Carbon\Carbon::parse($item->time_start)->format('g:i A') : '';
                if ($item->time_end) {
                    $timeStr .= ' - ' . \Carbon\Carbon::parse($item->time_end)->format('g:i A');
                }
                return [
                    $item->subject_code,
                    $item->section,
                    $item->day_of_week,
                    $timeStr,
                    $item->room,
                    $item->instructor ? $item->instructor->name : ($item->instructor_name ?? 'N/A'),
                    $item->type ?? 'N/A',
                    ucfirst($item->status ?? 'N/A'),
                ];

            case 'rooms':
                return [
                    $item->name,
                    $item->capacity ?? 'N/A',
                    $item->created_at ? $item->created_at->format('M d, Y') : 'N/A',
                ];

            case 'students':
                return [
                    $item->student_id_number,
                    $item->full_name ?? ($item->first_name . ' ' . $item->last_name),
                    $item->email,
                    $item->department ? $item->department->name : 'N/A',
                    $item->year_level,
                    $item->section ? ($item->section->full_name ?? $item->section->section_name) : 'N/A',
                    ucfirst($item->status),
                ];

            default:
                return [];
        }
    }

    /**
     * Get column letter from number (A, B, C, ..., Z, AA, AB, ...).
     */
    private function getColumnLetter($number)
    {
        $letter = '';
        while ($number > 0) {
            $number--;
            $letter = chr(65 + ($number % 26)) . $letter;
            $number = intval($number / 26);
        }
        return $letter;
    }
}


