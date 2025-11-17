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

    // 🔍 Pencarian berdasarkan nama kelas
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('kelas', function ($q) use ($search) {
            $q->where('nama_kelas', 'like', "%{$search}%");
        });
    }

    // 🏷️ Filter berdasarkan kelas tertentu
    if ($request->filled('filter_kelas')) {
        $query->where('kelas_id', $request->filter_kelas);
    }

    $data = $query->orderBy('kelas_id', 'asc')->get();

    // Untuk dropdown filter
    $kelasList = Kelas::orderBy('nama_kelas')->get();

    return view('roles.superadmin.jam_belajar.index', compact('data', 'kelasList'));
}


    public function create()
    {
        // hanya kelas yang belum memiliki jam belajar
        $kelas = Kelas::whereDoesntHave('jamBelajar')->get();
        return view('roles.superadmin.jam_belajar.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'               => 'required|exists:kelas,id',
            'total_jam_belajar'      => 'required|integer|min:1',
            'jam_mulai'              => 'required|date_format:H:i',
            'waktu_istirahat_mulai'  => 'nullable|date_format:H:i',
            'waktu_istirahat_selesai'=> 'nullable|date_format:H:i|after:waktu_istirahat_mulai',
        ]);

        // Cegah duplikasi kelas
        if (JamBelajar::where('kelas_id', $request->kelas_id)->exists()) {
            return back()->withErrors([
                'kelas_id' => 'Kelas ini sudah memiliki jam belajar!'
            ]);
        }

        // Hitung durasi istirahat (jika ada)
        $istirahat = 0;
        if ($request->waktu_istirahat_mulai && $request->waktu_istirahat_selesai) {
            $istirahat = strtotime($request->waktu_istirahat_selesai) - strtotime($request->waktu_istirahat_mulai);
        }

        // Hitung jam selesai otomatis:
        // jam_selesai = jam_mulai + (total_jam_belajar jam) + (durasi istirahat)
        $jamMulai = strtotime($request->jam_mulai);
        $durasiBelajar = $request->total_jam_belajar * 3600; // dikonversi ke detik
        $jamSelesai = $jamMulai + $durasiBelajar;

        // Masukkan jam selesai hasil perhitungan
        $request->merge([
            'jam_selesai' => date('H:i', $jamSelesai),
        ]);

        JamBelajar::create([
            'kelas_id'               => $request->kelas_id,
            'total_jam_belajar'      => $request->total_jam_belajar,
            'jam_mulai'              => $request->jam_mulai,
            'jam_selesai'            => $request->jam_selesai,
            'waktu_istirahat_mulai'  => $request->waktu_istirahat_mulai,
            'waktu_istirahat_selesai'=> $request->waktu_istirahat_selesai,
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
            'kelas_id'               => 'required|exists:kelas,id',
            'total_jam_belajar'      => 'required|integer|min:1',
            'jam_mulai'              => 'required|date_format:H:i',
            'waktu_istirahat_mulai'  => 'nullable|date_format:H:i',
            'waktu_istirahat_selesai'=> 'nullable|date_format:H:i|after:waktu_istirahat_mulai',
        ]);

        $istirahat = 0;
        if ($request->waktu_istirahat_mulai && $request->waktu_istirahat_selesai) {
            $istirahat = strtotime($request->waktu_istirahat_selesai) - strtotime($request->waktu_istirahat_mulai);
        }

        // Hitung jam selesai otomatis TANPA menambahkan durasi istirahat
            $jamMulai = strtotime($request->jam_mulai);
            $durasiBelajar = $request->total_jam_belajar * 3600; // dikonversi ke detik
            $jamSelesai = $jamMulai + $durasiBelajar;

        $request->merge([
            'jam_selesai' => date('H:i', $jamSelesai),
        ]);

        JamBelajar::where('id', $id)->update([
            'kelas_id'               => $request->kelas_id,
            'total_jam_belajar'      => $request->total_jam_belajar,
            'jam_mulai'              => $request->jam_mulai,
            'jam_selesai'            => $request->jam_selesai,
            'waktu_istirahat_mulai'  => $request->waktu_istirahat_mulai,
            'waktu_istirahat_selesai'=> $request->waktu_istirahat_selesai,
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
