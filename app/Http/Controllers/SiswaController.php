<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\StatusSiswa;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'status']);

        // --- Filter & Search ---
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // --- Pagination ---
        $siswa = $query->paginate(10)->appends($request->all());

        $kelas = Kelas::all();
        $status = StatusSiswa::all();

        return view('roles.superadmin.siswa.index', compact('siswa', 'kelas', 'status'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $status = StatusSiswa::all();
        return view('siswa.create', compact('kelas', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn',
            'nama_siswa' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'agama' => 'required',
            'status_id' => 'required|exists:status_siswa,id',
        ]);

        Siswa::create($request->all());
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return response()->json($siswa);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required|integer|exists:kelas,id',
            'agama' => 'required|string|max:50',
            'status_id' => 'required|integer|exists:status_siswa,id',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }



    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
