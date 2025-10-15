<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiayaSpp;
use App\Models\TahunAjaran;
use App\Models\Tingkat;
use App\Models\Status;

class BiayaSppController extends Controller
{
    // ===============================
    //  INDEX
    // ===============================
    public function index()
    {
        $biayaSpps = BiayaSpp::with(['tahunAjaran', 'tingkat', 'status'])->get();
        $tahunAjarans = TahunAjaran::all();
        $tingkats = Tingkat::all();
        $statuses = Status::all();

        return view('roles.superadmin.biayaspp.index', compact(
            'biayaSpps',
            'tahunAjarans',
            'tingkats',
            'statuses'
        ));
    }

    // ===============================
    //  CRUD BIAYA SPP
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required',
            'tingkat_id' => 'required',
            'status_id' => 'required',
            'nominal' => 'required|numeric',
        ]);

        BiayaSpp::create($request->all());
        return back()->with('success', 'Data Biaya SPP berhasil ditambahkan.');
    }

    public function update(Request $request, BiayaSpp $spp)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required',
            'tingkat_id' => 'required',
            'status_id' => 'required',
            'nominal' => 'required|numeric',
        ]);

        $spp->update($request->all());
        return back()->with('success', 'Data Biaya SPP berhasil diperbarui.');
    }

    public function destroy(BiayaSpp $spp)
    {
        $spp->delete();
        return back()->with('success', 'Data Biaya SPP berhasil dihapus.');
    }

    // ===============================
    //  CRUD TAHUN AJARAN
    // ===============================
    public function storeTahunAjaran(Request $request)
    {
        $request->validate([
            'nama_tahun' => 'required|string|max:255',
        ]);

        TahunAjaran::create([
            'nama_tahun' => $request->nama_tahun,
        ]);

        return back()->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function destroyTahunAjaran(TahunAjaran $tahun)
    {
        $tahun->delete();
        return back()->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    // ===============================
    //  CRUD TINGKAT
    // ===============================
    public function storeTingkat(Request $request)
    {
        $request->validate([
            'nama_tingkat' => 'required|string|max:255',
        ]);

        Tingkat::create([
            'nama_tingkat' => $request->nama_tingkat,
        ]);

        return back()->with('success', 'Tingkat berhasil ditambahkan.');
    }

    public function destroyTingkat(Tingkat $tingkat)
    {
        $tingkat->delete();
        return back()->with('success', 'Tingkat berhasil dihapus.');
    }

    // ===============================
    //  CRUD STATUS
    // ===============================
    public function storeStatus(Request $request)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
        ]);

        Status::create([
            'nama_status' => $request->nama_status,
        ]);

        return back()->with('success', 'Status berhasil ditambahkan.');
    }

    public function destroyStatus(Status $status)
    {
        $status->delete();
        return back()->with('success', 'Status berhasil dihapus.');
    }
}
