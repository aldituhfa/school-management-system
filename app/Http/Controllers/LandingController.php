<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data dari tabel users
        $tataUsaha = User::where('role', 'tu')->count();
        $guru      = User::where('role', 'guru')->count();

        // Ambil data siswa dari tabel siswa
        $siswa     = Siswa::count();

        // Nanti kepuasan bisa diambil dari tabel lain, untuk sekarang statis
        $kepuasan  = 99;

        // Kirim ke view
        return view('landing', compact('tataUsaha', 'siswa', 'guru', 'kepuasan'));
    }
}
