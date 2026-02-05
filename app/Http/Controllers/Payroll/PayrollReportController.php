<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollHistory;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use PDF;
use App\Exports\PayrollReportExport;
use Maatwebsite\Excel\Facades\Excel;

class PayrollReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua periode (buat dropdown)
        $periods = PayrollPeriod::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        // DEFAULT: periode terbaru
        $selectedPeriod = $request->filled('period_id')
            ? PayrollPeriod::findOrFail($request->period_id)
            : $periods->first();

        $histories = collect();
        $totalPaid = 0;

        if ($selectedPeriod) {
            $histories = PayrollHistory::with('user')
                ->where('payroll_period_id', $selectedPeriod->id)
                ->get();

            $totalPaid = $histories
                ->where('status', 'paid')
                ->sum('gaji_pokok');
        }

        return view(
            'roles.payroll.laporan_gaji.index',
            compact(
                'periods',
                'selectedPeriod',
                'histories',
                'totalPaid'
            )
        );
    }


    // ==========================
    // EXPORT PDF
    // ==========================
    public function exportPdf(Request $request)
    {
        $period = PayrollPeriod::findOrFail($request->period_id);

        $histories = PayrollHistory::with('user')
            ->where('payroll_period_id', $period->id)
            ->get();

        $pdf = PDF::loadView(
            'roles.payroll.laporan_gaji.pdf',
            compact('period', 'histories')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'laporan-gaji-' . $period->bulan . '-' . $period->tahun . '.pdf'
        );
    }

    // ==========================
    // EXPORT EXCEL
    // ==========================
    public function exportExcel(Request $request)
    {
        $period = PayrollPeriod::findOrFail($request->period_id);

        return Excel::download(
            new PayrollReportExport($period),
            'laporan-gaji-' . $period->bulan . '-' . $period->tahun . '.xlsx'
        );
    }
}
