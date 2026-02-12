<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Finance;
use App\Models\FinanceLog;
use App\Models\Siswa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ==============================
        // TOTAL AKUN
        // ==============================
        $totalUsers = User::count();
        $totalSiswa = Siswa::count();

        // ==============================
        // SALDO
        // ==============================
        $bosIn  = Finance::where('type', 'dana_bos')->where('in_out', 'in')->sum('amount');
        $bosOut = Finance::where('type', 'dana_bos')->where('in_out', 'out')->sum('amount');

        $kasIn  = Finance::where('type', 'kas')->where('in_out', 'in')->sum('amount');
        $kasOut = Finance::where('type', 'kas')->where('in_out', 'out')->sum('amount');

        $totalBos = $bosIn - $bosOut;
        $totalKas = $kasIn - $kasOut;
        $totalSemua = $totalBos + $totalKas;

        $totalSaldo = $totalSemua;


        // ==============================
        // TRANSAKSI BULAN INI
        // ==============================
        $transaksiBulanIni = Finance::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ==============================
        // PENGELUARAN TERTINGGI
        // ==============================
        $pengeluaranTertinggi = Finance::where('in_out', 'out')->max('amount') ?? 0;

        // ==============================
        // AKTIVITAS TERBARU
        // ==============================
        $aktivitas = FinanceLog::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('roles.superadmin.dashboard', compact(
            'totalUsers',
            'totalSiswa',
            'totalSaldo',
            'transaksiBulanIni',
            'pengeluaranTertinggi',
            'totalBos',
            'totalKas',
            'totalSemua',
            'aktivitas'
        ));
    }
}
