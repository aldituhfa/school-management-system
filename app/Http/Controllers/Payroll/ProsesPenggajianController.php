<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollPeriod;
use App\Models\PayrollHistory;
use App\Models\User;
use Illuminate\Http\Request;

class ProsesPenggajianController extends Controller
{
    public function index()
    {
        $period = PayrollPeriod::where('status', 'processing')->first();

        if (!$period) {
            return view('roles.payroll.proses.empty');
        }

        $users = User::whereHas('payrollSetting', function ($q) {
            $q->where('status_aktif', 1)
                ->where('status_penggajian', 1);
        })
            ->with([
                'payrollSetting',
                'payrollHistories' => function ($q) use ($period) {
                    $q->where('payroll_period_id', $period->id);
                }
            ])
            ->get();

        return view('roles.payroll.proses.index', compact('period', 'users'));
    }



    public function pay($periodId, $userId)
    {
        $period = PayrollPeriod::findOrFail($periodId);

        PayrollHistory::updateOrCreate(
            [
                'user_id' => $userId,
                'payroll_period_id' => $period->id
            ],
            [
                'gaji_pokok' => User::find($userId)->payrollSetting->gaji_pokok,
                'status' => 'paid',
                'paid_at' => now()
            ]
        );

        return back()->with('success', 'Gaji berhasil dibayar');
    }

    public function cancel($periodId, $userId)
    {
        PayrollHistory::where([
            'user_id' => $userId,
            'payroll_period_id' => $periodId
        ])->delete();

        return back()->with('success', 'Pembayaran dibatalkan');
    }


    public function closePeriod($id)
    {
        $period = PayrollPeriod::findOrFail($id);

        $totalUser = User::whereHas('payrollSetting', function ($q) {
            $q->where('status_aktif', 1)
                ->where('status_penggajian', 1);
        })->count();

        $paid = PayrollHistory::where('payroll_period_id', $period->id)
            ->where('status', 'paid')
            ->count();

        if ($paid < $totalUser) {
            return back()->with('error', 'Masih ada pegawai yang belum dibayar');
        }

        $period->update(['status' => 'closed']);

        return back()->with('success', 'Periode berhasil ditutup');
    }
}
