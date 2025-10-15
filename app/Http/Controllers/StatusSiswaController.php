<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusSiswa;

class StatusSiswaController extends Controller
{
    public function index()
    {
        $status = StatusSiswa::all();
        return view('status.index', compact('status'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_status' => 'required']);
        StatusSiswa::create($request->all());
        return back()->with('success', 'Status berhasil ditambahkan.');
    }

    public function destroy(StatusSiswa $status)
    {
        $status->delete();
        return back()->with('success', 'Status berhasil dihapus.');
    }
}
