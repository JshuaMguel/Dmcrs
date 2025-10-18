<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;

class HeadReportController extends Controller
{
    /**
     * Retrieve reports dataset used across views and exports.
     */
    private function queryReports()
    {
        return DB::table('make_up_class_requests')
            ->join('users', 'make_up_class_requests.faculty_id', '=', 'users.id')
            ->leftJoin('approvals', 'make_up_class_requests.id', '=', 'approvals.make_up_class_request_id')
            ->select(
                'users.name as faculty',
                'make_up_class_requests.tracking_number',
                'make_up_class_requests.reason',
                'make_up_class_requests.subject',
                'make_up_class_requests.room',
                'make_up_class_requests.preferred_date',
                'make_up_class_requests.preferred_time',
                'approvals.decision as final_status',
                'approvals.remarks',
                'approvals.created_at as date_approved'
            )
            ->whereIn('make_up_class_requests.status', ['APPROVED', 'HEAD_REJECTED'])
            ->orderByDesc('make_up_class_requests.created_at')
            ->get();
    }
    /**
     * Export reports to Excel.
     */
    public function exportExcel(Request $request)
    {
        $reports = $this->queryReports();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['Faculty', 'Tracking #', 'Reason', 'Subject', 'Room', 'Date', 'Time', 'Final Status', 'Remarks', 'Date Approved']
        ], null, 'A1');

        $row = 2;
        foreach ($reports as $report) {
            $sheet->fromArray([
                $report->faculty,
                $report->tracking_number,
                $report->reason,
                $report->subject,
                $report->room,
                $report->preferred_date,
                $report->preferred_time,
                $report->final_status,
                $report->remarks,
                $report->date_approved,
            ], null, 'A' . $row);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'reports_' . date('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
    /**
     * Display logs & reports for Academic Head.
     */
    public function index(Request $request): View
    {
        $reports = $this->queryReports();

        return view('head.reports.index', compact('reports'));
    }

    /**
     * Export reports to PDF.
     */
    public function exportPdf()
    {
        $reports = $this->queryReports();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.head_reports', [
            'reports' => $reports,
            'generatedAt' => now()->timezone(config('app.timezone')),
              'generatedBy' => Auth::user()->name ?? 'System'
        ])->setPaper('a4', 'landscape');
        return $pdf->download('head_reports_'.now()->format('Ymd_His').'.pdf');
    }

    /**
     * Print friendly reports view.
     */
    public function print()
    {
        $reports = $this->queryReports();
        return view('print.head_reports', [
            'reports' => $reports,
            'generatedAt' => now()->timezone(config('app.timezone')),
              'generatedBy' => Auth::user()->name ?? 'System'
        ]);
    }
}
