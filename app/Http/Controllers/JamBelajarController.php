<?php

namespace App\Http\Controllers;

use App\Models\JamBelajar;
use App\Models\Kelas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JamBelajarController extends Controller
{
    public function index(Request $request)
    {
        $query = JamBelajar::with('kelas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kelas', function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_kelas')) {
            $query->where('kelas_id', $request->filter_kelas);
        }

        $data = $query->orderBy('kelas_id', 'asc')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('roles.superadmin.jam_belajar.index', compact('data', 'kelasList'));
    }

    public function create()
    {
        $kelas = Kelas::whereDoesntHave('jamBelajar')->get();
        return view('roles.superadmin.jam_belajar.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'                 => 'required|exists:kelas,id',
            'total_jam_belajar'        => 'required|integer|min:1',
            'jam_mulai'                => 'required|date_format:H:i',

            // ISTIRAHAT 1
            'waktu_istirahat_mulai'    => 'nullable|date_format:H:i',
            'waktu_istirahat_selesai'  => 'nullable|date_format:H:i|after:waktu_istirahat_mulai',

            // ➕ ISTIRAHAT 2 (DITAMBAHKAN)
            'waktu_istirahat2_mulai'   => 'nullable|date_format:H:i',
            'waktu_istirahat2_selesai' => 'nullable|date_format:H:i|after:waktu_istirahat2_mulai',
        ]);

        if (JamBelajar::where('kelas_id', $request->kelas_id)->exists()) {
            return back()->withErrors([
                'kelas_id' => 'Kelas ini sudah memiliki jam belajar!'
            ]);
        }

        // tetap tidak menghitung istirahat pada jam selesai (sesuai kode asli)
        $jamMulai = strtotime($request->jam_mulai);
        $durasiBelajar = $request->total_jam_belajar * 3600;
        $jamSelesai = $jamMulai + $durasiBelajar;

        $request->merge([
            'jam_selesai' => date('H:i', $jamSelesai),
        ]);

        JamBelajar::create([
            'kelas_id'                  => $request->kelas_id,
            'total_jam_belajar'         => $request->total_jam_belajar,
            'jam_mulai'                 => $request->jam_mulai,
            'jam_selesai'               => $request->jam_selesai,

            // simpan istirahat 1
            'waktu_istirahat_mulai'     => $request->waktu_istirahat_mulai,
            'waktu_istirahat_selesai'   => $request->waktu_istirahat_selesai,

            // simpan istirahat 2 (TAMBAHAN)
            'waktu_istirahat2_mulai'    => $request->waktu_istirahat2_mulai,
            'waktu_istirahat2_selesai'  => $request->waktu_istirahat2_selesai,
        ]);

        return redirect()->route('superadmin.jam-belajar.index')
            ->with('success', 'Jam Belajar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jamBelajar = JamBelajar::findOrFail($id);
        $kelas = Kelas::all();
        return view('roles.superadmin.jam_belajar.edit', compact('jamBelajar', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id'                 => 'required|exists:kelas,id',
            'total_jam_belajar'        => 'required|integer|min:1',
            'jam_mulai'                => 'required|date_format:H:i',

            // ISTIRAHAT 1
            'waktu_istirahat_mulai'    => 'nullable|date_format:H:i',
            'waktu_istirahat_selesai'  => 'nullable|date_format:H:i|after:waktu_istirahat_mulai',

            // ➕ ISTIRAHAT 2 (DITAMBAHKAN)
            'waktu_istirahat2_mulai'   => 'nullable|date_format:H:i',
            'waktu_istirahat2_selesai' => 'nullable|date_format:H:i|after:waktu_istirahat2_mulai',
        ]);

        // tetap tanpa logika istirahat ke jam selesai (sesuai kode asli)
        $jamMulai = strtotime($request->jam_mulai);
        $durasiBelajar = $request->total_jam_belajar * 3600;
        $jamSelesai = $jamMulai + $durasiBelajar;

        $request->merge([
            'jam_selesai' => date('H:i', $jamSelesai),
        ]);

        JamBelajar::where('id', $id)->update([
            'kelas_id'                  => $request->kelas_id,
            'total_jam_belajar'         => $request->total_jam_belajar,
            'jam_mulai'                 => $request->jam_mulai,
            'jam_selesai'               => $request->jam_selesai,

            // update istirahat 1
            'waktu_istirahat_mulai'     => $request->waktu_istirahat_mulai,
            'waktu_istirahat_selesai'   => $request->waktu_istirahat_selesai,

            // update istirahat 2
            'waktu_istirahat2_mulai'    => $request->waktu_istirahat2_mulai,
            'waktu_istirahat2_selesai'  => $request->waktu_istirahat2_selesai,
        ]);

        return redirect()
            ->route('superadmin.jam-belajar.index')
            ->with('success', 'Jam belajar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        JamBelajar::findOrFail($id)->delete();
        return redirect()->route('superadmin.jam-belajar.index')
            ->with('success', 'Jam Belajar berhasil dihapus!');
    }
}
