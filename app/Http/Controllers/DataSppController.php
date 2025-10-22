<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class DataSppController extends Controller
{
    // Halaman Index — menampilkan daftar siswa (ringkas)
    public function index()
    {
        $siswa = Siswa::with(['kelas', 'status'])->paginate(10);
        $kelas = Kelas::all();
        return view('roles.tu.data_spp.index', compact('siswa', 'kelas'));
    }

    // Halaman Detail — menampilkan semua data satu siswa
    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'status'])->findOrFail($id);
        return view('roles.tu.data_spp.detail', compact('siswa'));
    }
}
