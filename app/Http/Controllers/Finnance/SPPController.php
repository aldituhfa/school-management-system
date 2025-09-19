<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SPP;
use App\Models\Finance;
use App\Models\FinanceLog;
use Illuminate\Support\Facades\Auth;

class SPPController extends Controller
{
    public function index()
    {
        return response()->json(SPP::orderBy('year','desc')->orderBy('month','desc')->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'student_name' => 'required|string',
            'student_identifier' => 'nullable|string',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0'
        ]);

        $spp = SPP::create($request->only('student_name','student_identifier','month','year','amount'));
        return response()->json(['success'=>true,'spp'=>$spp]);
    }

    // proses pembayaran spp — akan membuat finance (kas in)
    public function pay($id)
    {
        $spp = SPP::findOrFail($id);
        if ($spp->status === 'paid') {
            return response()->json(['error'=>'SPP sudah dibayar'], 400);
        }

        // create finance masuk ke kas
        $finance = Finance::create([
            'type' => 'kas',
            'category' => 'spp',
            'amount' => $spp->amount,
            'in_out' => 'in',
            'description' => "Pembayaran SPP {$spp->student_name} {$spp->month}/{$spp->year}",
            'user_id' => Auth::id()
        ]);

        FinanceLog::create([
            'user_id' => Auth::id(),
            'finance_id' => $finance->id,
            'action' => 'payment',
            'before_amount' => 0,
            'after_amount' => $finance->amount,
            'type' => $finance->type,
            'meta' => "SPP #{$spp->id}"
        ]);

        $spp->update([
            'status' => 'paid',
            'tu_id' => Auth::id(),
            'paid_at' => now()
        ]);

        return response()->json(['success'=>true,'finance'=>$finance,'spp'=>$spp]);
    }

    public function destroy($id)
    {
        $spp = SPP::findOrFail($id);
        $spp->delete();
        return response()->json(['success'=>true]);
    }
}
