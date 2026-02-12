<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PayrollPeriod;
use App\Models\PayrollHistory;
use App\Models\Finance;
use Illuminate\Http\Request;

class PayrollDashboardController extends Controller
{
    public function index(Request $request)
    {
        // =====================
        // LIST PERIODE (UNTUK FILTER)
        // =====================
        $periods = PayrollPeriod::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        // =====================
        // PERIODE TERPILIH
        // =====================
        $currentPeriod = $request->filled('period_id')
            ? PayrollPeriod::findOrFail($request->period_id)
            : $periods->first();

        // =====================
        // PEGAWAI AKTIF DIGAJI
        // =====================
        $totalPegawaiAktif = User::whereHas('payrollSetting', function ($q) {
            $q->where('status_aktif', 1)
                ->where('status_penggajian', 1);
        })->count();

        // =====================
        // DATA BERDASARKAN PERIODE
        // =====================
        $totalDibayar = 0;
        $totalNominal = 0;
        $belumDibayar = $totalPegawaiAktif;

        if ($currentPeriod) {
            $totalDibayar = PayrollHistory::where('payroll_period_id', $currentPeriod->id)
                ->where('status', 'paid')
                ->count();

            $totalNominal = PayrollHistory::where('payroll_period_id', $currentPeriod->id)
                ->where('status', 'paid')
                ->sum('gaji_pokok');

            $belumDibayar = max($totalPegawaiAktif - $totalDibayar, 0);
        }

        // =====================
        // PROGRESS
        // =====================
        $progress = $totalPegawaiAktif > 0
            ? round(($totalDibayar / $totalPegawaiAktif) * 100)
            : 0;

        // =====================
        // SALDO
        // =====================
        $saldoKas = $this->getSaldo('kas');
        $saldoBos = $this->getSaldo('dana_bos');

        // =====================
        // GRAFIK TAHUNAN (TETAP)
        // =====================
        $chart = PayrollHistory::selectRaw(
            'MONTH(paid_at) as bulan, SUM(gaji_pokok) as total'
        )
            ->whereYear('paid_at', now()->year)
            ->where('status', 'paid')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartLabels = [];
        $chartValues = [];

        foreach (range(1, 12) as $bulan) {
            $data = $chart->firstWhere('bulan', $bulan);
            $chartLabels[] = date('M', mktime(0, 0, 0, $bulan, 1));
            $chartValues[] = $data ? (int) $data->total : 0;
        }

        return view('roles.payroll.dashboard', compact(
            'periods',
            'currentPeriod',
            'totalPegawaiAktif',
            'totalDibayar',
            'belumDibayar',
            'totalNominal',
            'progress',
            'saldoKas',
            'saldoBos',
            'chartLabels',
            'chartValues'
        ));
    }

    private function getSaldo($type)
    {
        return Finance::where('type', $type)
            ->selectRaw("
                SUM(CASE WHEN in_out='in' THEN amount ELSE 0 END) -
                SUM(CASE WHEN in_out='out' THEN amount ELSE 0 END) as saldo
            ")
            ->value('saldo') ?? 0;
    }
}
