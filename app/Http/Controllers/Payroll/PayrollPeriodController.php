<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollPeriod;
use App\Models\PayrollHistory;


class PayrollPeriodController extends Controller
{
    public function index()
    {
        $periods = PayrollPeriod::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('roles.payroll.periode.index', compact('periods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric'
        ]);

        PayrollPeriod::create([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun
        ]);

        return back()->with('success', 'Periode berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $period = PayrollPeriod::findOrFail($id);

        // PROSES
        if ($request->action === 'process') {

            // cek apakah ada periode processing lain
            $existProcessing = PayrollPeriod::where('status', 'processing')->exists();

            if ($existProcessing) {
                return back()->with('error', 'Masih ada periode penggajian yang sedang diproses');
            }

            $period->update([
                'status' => 'processing',
                'tanggal_proses' => now()
            ]);

            return back()->with('success', 'Periode berhasil diproses');
        }

        // CANCEL
        // CANCEL
        if ($request->action === 'cancel') {

            $hasPayment = PayrollHistory::where('payroll_period_id', $period->id)->exists();

            if ($hasPayment) {
                return back()->with(
                    'error',
                    'Tidak bisa dibatalkan karena sudah ada transaksi gaji'
                );
            }

            $period->update([
                'status' => 'open',
                'tanggal_proses' => null
            ]);

            return back()->with('success', 'Proses periode berhasil dibatalkan');
        }

        return back();
    }

    public function destroy($id)
    {
        $period = PayrollPeriod::findOrFail($id);

        // ❌ Tidak boleh hapus jika sedang diproses atau sudah closed
        if (in_array($period->status, ['processing', 'closed'])) {
            return back()->with(
                'error',
                'Periode yang sedang diproses atau sudah ditutup tidak boleh dihapus'
            );
        }

        $period->delete();

        return back()->with('success', 'Periode penggajian berhasil dihapus');
    }
}
