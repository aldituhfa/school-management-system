<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollPeriod;
use App\Models\PayrollHistory;
use App\Models\User;
use App\Models\Finance;
use Illuminate\Support\Facades\DB;
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

    private function getSaldo($type)
    {
        return Finance::where('type', $type)
            ->selectRaw("
            SUM(CASE WHEN in_out='in' THEN amount ELSE 0 END) -
            SUM(CASE WHEN in_out='out' THEN amount ELSE 0 END) as saldo
        ")
            ->value('saldo') ?? 0;
    }


    public function pay(Request $request, $periodId, $userId)
    {
        $request->validate([
            'source' => 'required|in:kas,dana_bos'
        ]);

        $period = PayrollPeriod::findOrFail($periodId);
        $user   = User::with('payrollSetting')->findOrFail($userId);

        $gaji = $user->payrollSetting->gaji_pokok;

        // 🔎 CEK SALDO
        $saldo = $this->getSaldo($request->source);
        if ($saldo < $gaji) {
            return back()->with('error', 'Saldo ' . strtoupper($request->source) . ' tidak mencukupi');
        }

        DB::transaction(function () use ($request, $period, $user, $gaji) {

            // 💸 POTONG KAS / BOS
            $finance = Finance::create([
                'type'        => $request->source,
                'category'    => 'gaji',
                'amount'      => $gaji,
                'in_out'      => 'out',
                'description' => 'Pembayaran gaji ' . $user->name,
                'user_id'     => auth()->id(),
                'source' => 'payroll',
            ]);

            // 🧾 SIMPAN HISTORY
            PayrollHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'payroll_period_id' => $period->id
                ],
                [
                    'gaji_pokok' => $gaji,
                    'status'     => 'paid',
                    'paid_at'    => now(),
                    'finance_id' => $finance->id
                ]
            );
        });

        return back()->with('success', 'Gaji berhasil dibayar & saldo dipotong');
    }


    public function cancel($periodId, $userId)
    {
        $history = PayrollHistory::with('finance')
            ->where('user_id', $userId)
            ->where('payroll_period_id', $periodId)
            ->where('status', 'paid')
            ->first();

        if (!$history) {
            return back()->with('warning', 'Data penggajian tidak ditemukan');
        }

        DB::transaction(function () use ($history) {

            // 1️⃣ HAPUS TRANSAKSI KEUANGAN (SEPERTI SPP)
            if ($history->finance) {
                $history->finance->delete();
            }

            // 2️⃣ RESET STATUS PAYROLL
            $history->update([
                'status'     => 'pending',
                'paid_at'    => null,
                'finance_id' => null,
            ]);
        });

        return back()->with('success', 'Pembayaran gaji dibatalkan & saldo dikembalikan');
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
