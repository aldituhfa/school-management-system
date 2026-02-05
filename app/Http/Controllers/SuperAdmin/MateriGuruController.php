<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\MateriPembelajaran;

class MateriGuruController extends Controller
{
    public function index()
    {
        $guruList = User::where('role', 'guru')
            ->whereHas('materi')
            ->get();

        return view('roles.superadmin.materi.index', compact('guruList'));
    }

    public function show($guru_id)
    {
        $guru = User::where('role', 'guru')->findOrFail($guru_id);

        $materi = MateriPembelajaran::where('guru_id', $guru_id)
            ->latest()
            ->get();

        return view('roles.superadmin.materi.show', compact('guru', 'materi'));
    }
}