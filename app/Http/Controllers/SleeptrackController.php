<?php

namespace App\Http\Controllers;

use App\Models\SleepReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SleepReportExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SleeptrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sleeptrack $sleeptrack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sleeptrack $sleeptrack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sleeptrack $sleeptrack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sleeptrack $sleeptrack)
    {
        //
    }

    protected function filteredReports(Request $request)
    {
        $query = SleepReport::with(['driver', 'sleeptracks']);

        if ($request->filled('driverName')) {
            $driverName = $request->string('driverName')->trim();

            $query->whereHas('driver', function ($q) use ($driverName) {
                $q->where('name', 'ilike', '%'.$driverName.'%');
            });
        }

        if ($request->filled('dateFrom')) {
            $query->whereDate('date', '>=', $request->date('dateFrom')->toDateString());
        }

        if ($request->filled('dateTo')) {
            $query->whereDate('date', '<=', $request->date('dateTo')->toDateString());
        }

        $reports = $query
            ->latest('date')
            ->get();

        if ($request->filled('sleepCategory')) {
            $category = $request->string('sleepCategory')->toString();

            $reports = $reports->filter(function (SleepReport $report) use ($category) {
                return $report->sleep_category === $category;
            });
        }

        return $reports;
    }

    public function exportExcel(Request $request)
    {
        $reports = $this->filteredReports($request);

        $fileName = 'sleepreports_'.Carbon::now()->format('Ymd_His');

        return Excel::download(new SleepReportExport($reports),"${fileName}.xlsx");
        // return response()->stream($callback, 200, $headers);
    }

    // public function exportExcel(Request $request): StreamedResponse
    // {
    //     $reports = $this->filteredReports($request);

    //     $fileName = 'sleepreports_'.Carbon::now()->format('Ymd_His').'.csv';

    //     $headers = [
    //         'Content-Type' => 'text/csv; charset=UTF-8',
    //         'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
    //     ];

    //     $columns = ['ID', 'Nama AMT', 'Status', 'Tanggal', 'Kecukupan Tidur', 'Durasi (jam)'];

    //     $callback = function () use ($reports, $columns) {
    //         $handle = fopen('php://output', 'w');

    //         // BOM for Excel UTF-8
    //         fwrite($handle, "\xEF\xBB\xBF");

    //         fputcsv($handle, $columns);

    //         foreach ($reports as $report) {
    //             fputcsv($handle, [
    //                 $report->id,
    //                 $report->driver->name ?? '-',
    //                 $report->status,
    //                 optional($report->date)->format('d/m/Y'),
    //                 $report->sleep_category,
    //                 number_format($report->sleep_duration_hours, 1),
    //             ]);
    //         }

    //         fclose($handle);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    public function exportPdf(Request $request)
    {
        $reports = $this->filteredReports($request);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('exports.sleepreports', [
            'reports' => $reports,
        ])->setPaper('A4', 'landscape');

        $fileName = 'sleepreports_'.Carbon::now()->format('Ymd_His').'.pdf';

        return $pdf->download($fileName);
    }
}
