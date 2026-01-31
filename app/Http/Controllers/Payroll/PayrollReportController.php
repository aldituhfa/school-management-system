<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollHistory;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;

class PayrollReportController extends Controller
{
    public function index(Request $request)
    {
        $periods = PayrollPeriod::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $selectedPeriod = null;
        $histories = collect();
        $totalPaid = 0;

        if ($request->filled('period_id')) {
            $selectedPeriod = PayrollPeriod::findOrFail($request->period_id);

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
}
