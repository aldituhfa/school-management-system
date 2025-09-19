<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance;
use App\Models\FinanceLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // simple filter support untuk DataTable
        $query = Finance::with('user')->orderBy('created_at','desc');

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('in_out')) $query->where('in_out', $request->in_out);

        $finances = $query->get();

        return response()->json($finances);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:dana_bos,kas',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'in_out' => 'required|in:in,out',
            'description' => 'nullable|string'
        ]);

        $data = $request->only('type','category','amount','in_out','description');
        $data['user_id'] = Auth::id();

        $finance = Finance::create($data);

        FinanceLog::create([
            'user_id' => Auth::id(),
            'finance_id' => $finance->id,
            'action' => 'create',
            'before_amount' => 0,
            'after_amount' => $finance->amount,
            'type' => $finance->type,
            'meta' => $finance->category
        ]);

        return response()->json(['success' => true, 'message' => 'Berhasil ditambahkan', 'finance' => $finance]);
    }

    public function update(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);
        $before = $finance->amount;

        $this->validate($request, [
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $finance->update($request->only('amount','description'));

        FinanceLog::create([
            'user_id' => Auth::id(),
            'finance_id' => $finance->id,
            'action' => 'update',
            'before_amount' => $before,
            'after_amount' => $finance->amount,
            'type' => $finance->type,
            'meta' => $finance->category
        ]);

        return response()->json(['success' => true, 'message' => 'Berhasil diupdate', 'finance' => $finance]);
    }

    public function destroy($id)
    {
        $finance = Finance::findOrFail($id);

        FinanceLog::create([
            'user_id' => Auth::id(),
            'finance_id' => $finance->id,
            'action' => 'delete',
            'before_amount' => $finance->amount,
            'after_amount' => 0,
            'type' => $finance->type,
            'meta' => $finance->category
        ]);

        $finance->delete();

        return response()->json(['success' => true, 'message' => 'Berhasil dihapus']);
    }

    // Totals untuk card di dashboard
    public function totals()
    {
        $danaBosIn  = Finance::where('type','dana_bos')->where('in_out','in')->sum('amount');
        $danaBosOut = Finance::where('type','dana_bos')->where('in_out','out')->sum('amount');

        $kasIn  = Finance::where('type','kas')->where('in_out','in')->sum('amount');
        $kasOut = Finance::where('type','kas')->where('in_out','out')->sum('amount');

        $totals = [
            'dana_bos' => [
                'in' => (float)$danaBosIn,
                'out' => (float)$danaBosOut,
                'saldo' => (float)($danaBosIn - $danaBosOut)
            ],
            'kas' => [
                'in' => (float)$kasIn,
                'out' => (float)$kasOut,
                'saldo' => (float)($kasIn - $kasOut)
            ],
            'total_saldo' => (float)(($danaBosIn - $danaBosOut) + ($kasIn - $kasOut))
        ];

        return response()->json($totals);
    }

    // Statistik untuk Chart.js (periode bulanan)
    public function stats()
    {
        $items = Finance::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, type, SUM(CASE WHEN in_out='in' THEN amount ELSE -amount END) as total")
            ->groupBy('period','type')
            ->orderBy('period')
            ->get();

        // build labels & datasets
        $periods = $items->pluck('period')->unique()->values()->all();

        $map = [];
        foreach ($items as $it) {
            $map[$it->period][$it->type] = (float)$it->total;
        }

        $dana_bos = [];
        $kas = [];
        foreach ($periods as $p) {
            $dana_bos[] = $map[$p]['dana_bos'] ?? 0;
            $kas[] = $map[$p]['kas'] ?? 0;
        }

        return response()->json([
            'labels' => $periods,
            'datasets' => [
                ['label'=>'Dana BOS','data'=>$dana_bos],
                ['label'=>'Kas','data'=>$kas],
            ]
        ]);
    }
}
