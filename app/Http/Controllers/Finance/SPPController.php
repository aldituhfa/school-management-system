<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SPP;
use Illuminate\Support\Facades\Auth;

class SPPController extends Controller
{
    // Tampilkan daftar SPP
    public function index()
    {
        $spps = SPP::orderByRaw('CAST(`year` AS UNSIGNED) DESC')
                   ->orderBy('month', 'desc')
                   ->get();

        return view('roles.tu.spp.index', compact('spps'));
    }

    // Form tambah pembayaran
    public function create()
    {
        return view('roles.tu.spp.create');
    }

    // Simpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_identifier' => 'nullable|string|max:255',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'amount' => 'required|numeric|min:0',
        ]);

        SPP::create([
            'student_name' => $request->student_name,
            'student_identifier' => $request->student_identifier,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
            'status' => 'unpaid',
            'tu_id' => Auth::id(),
        ]);

        return redirect()->route('tu.spp.index')->with('success', 'Data SPP berhasil ditambahkan!');
    }

    // Form edit pembayaran
    public function edit($id)
    {
        $spp = SPP::findOrFail($id);
        return view('roles.tu.spp.edit', compact('spp'));
    }

    // Update pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_identifier' => 'nullable|string|max:255',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
        ]);

        $spp = SPP::findOrFail($id);
        $spp->update([
            'student_name' => $request->student_name,
            'student_identifier' => $request->student_identifier,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
            'status' => $request->status,
        ]);

        return redirect()->route('tu.spp.index')->with('success', 'Data SPP berhasil diperbarui!');
    }

    // Hapus pembayaran
    public function destroy($id)
    {
        $spp = SPP::findOrFail($id);
        $spp->delete();

        return redirect()->route('tu.spp.index')->with('success', 'Data SPP berhasil dihapus!');
    }

    // Bayar SPP
    public function pay($id)
    {
        $spp = SPP::findOrFail($id);

        $spp->update([
            'status'   => 'paid',
            'paid_at'  => now(),      // kolom datetime nullable
            'tu_id'    => Auth::id(),
        ]);

        return redirect()->route('tu.spp.index')->with('success', 'SPP berhasil dibayar!');
    }
}
