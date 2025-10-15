<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;


class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('kelas.index', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kelas' => 'required']);
        Kelas::create($request->all());
        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
