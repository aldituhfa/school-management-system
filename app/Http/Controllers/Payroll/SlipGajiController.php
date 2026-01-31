<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;

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

        return view('roles.payroll.slip_gaji.pdf', compact('slip'));
    }
}
