<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Finance;
use App\Models\FinanceLog;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        return response()->json(Payroll::orderBy('created_at','desc')->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_name' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $payroll = Payroll::create([
            'employee_name' => $request->employee_name,
            'admin_id' => Auth::id(),
            'amount' => $request->amount,
            'status' => 'unpaid'
        ]);

        return response()->json(['success'=>true,'payroll'=>$payroll]);
    }

    // pembayaran gaji -> akan membuat finance keluar (kas out)
    public function pay($id)
    {
        $payroll = Payroll::findOrFail($id);
        if ($payroll->status === 'paid') {
            return response()->json(['error'=>'Sudah dibayar'], 400);
        }

        $finance = Finance::create([
            'type' => 'kas',
            'category' => 'gaji',
            'amount' => $payroll->amount,
            'in_out' => 'out',
            'description' => "Pembayaran gaji {$payroll->employee_name}",
            'user_id' => Auth::id()
        ]);

        FinanceLog::create([
            'user_id' => Auth::id(),
            'finance_id' => $finance->id,
            'action' => 'payment',
            'before_amount' => 0,
            'after_amount' => $finance->amount,
            'type' => $finance->type,
            'meta' => "Payroll #{$payroll->id}"
        ]);

        $payroll->update([
            'status' => 'paid',
            'pay_date' => now()
        ]);

        return response()->json(['success'=>true,'finance'=>$finance,'payroll'=>$payroll]);
    }
}
