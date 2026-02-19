<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\SlipGajiDikirimNotification;
use App\Models\User;

class SlipGajiController extends Controller
{
    public function index(Request $request)
    {
        $slips = PayrollHistory::with(['user', 'period'])
            ->where('status', 'paid')
            ->orderByDesc('payroll_period_id')
            ->get();

        $selectedSlip = null;

        if ($request->has('selected')) {
            $selectedSlip = $slips->firstWhere('id', $request->selected);
        } else {
            $selectedSlip = $slips->first();
        }

        return view(
            'roles.payroll.slip_gaji.index',
            compact('slips', 'selectedSlip')
        );
    }


    public function show($id)
    {
        return PayrollHistory::with(['user', 'period'])->findOrFail($id);
    }


    public function download($id)
    {
        $slip = PayrollHistory::with(['user', 'period'])->findOrFail($id);

        $pdf = Pdf::loadView('roles.payroll.slip_gaji.pdf', [
            'slip' => $slip
        ])->setPaper('A4', 'portrait');

        $fileName = 'slip-gaji-' .
            str_replace(' ', '-', strtolower($slip->user->name)) . '-' .
            strtolower($slip->period->bulan) . '-' .
            $slip->period->tahun . '.pdf';

        return $pdf->download($fileName);
    }


    public function kirim($id)
    {
        $slip = PayrollHistory::with(['user', 'period'])->findOrFail($id);

        // kalau sudah pernah dikirim, jangan kirim lagi
        if ($slip->slip_sent_at) {
            return back()->with('info', 'Slip sudah pernah dikirim.');
        }

        // kirim notifikasi
        $slip->user->notify(new SlipGajiDikirimNotification($slip));

        // update waktu kirim
        $slip->update([
            'slip_sent_at' => now()
        ]);

        return back()->with('success', 'Slip gaji berhasil dikirim.');
    }
}
