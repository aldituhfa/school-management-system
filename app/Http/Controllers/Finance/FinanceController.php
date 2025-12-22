<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance;
use App\Models\FinanceLog;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    /**
     * Halaman utama keuangan (Blade full, tanpa JSON)
     */
    public function index()
    {
        // Query terpisah sesuai jenis
        $danaBos = Finance::with('user')
            ->where('type', 'dana_bos')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'danaBosPage'); // paginate khusus

        $kas = Finance::with('user')
            ->where('type', 'kas')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'kasPage');

        // Hitung saldo
        $danaBosIn  = Finance::where('type', 'dana_bos')->where('in_out', 'in')->sum('amount');
        $danaBosOut = Finance::where('type', 'dana_bos')->where('in_out', 'out')->sum('amount');
        $kasIn  = Finance::where('type', 'kas')->where('in_out', 'in')->sum('amount');
        $kasOut = Finance::where('type', 'kas')->where('in_out', 'out')->sum('amount');

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

        return view('roles.superadmin.finances', compact('danaBos', 'kas', 'totals'));
    }


    /**
     * Simpan transaksi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:dana_bos,kas',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'in_out' => 'required|in:in,out',
            'description' => 'nullable|string'
        ]);

        $data = $request->only('type', 'category', 'amount', 'in_out', 'description');
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

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Update transaksi
     */
    public function update(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);
        $before = $finance->amount;

        $request->validate([
            'type' => 'required|in:dana_bos,kas',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'in_out' => 'required|in:in,out',
            'description' => 'nullable|string'
        ]);

        // Simpan perubahan
        $finance->update([
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'in_out' => $request->in_out,
            'description' => $request->description,
        ]);

        // Catat ke log
        FinanceLog::create([
            'user_id' => Auth::id(),
            'finance_id' => $finance->id,
            'action' => 'update',
            'before_amount' => $before,
            'after_amount' => $finance->amount,
            'type' => $finance->type,
            'meta' => $finance->category
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil diupdate.');
    }


    /**
     * Hapus transaksi
     */
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

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
