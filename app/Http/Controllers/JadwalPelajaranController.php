<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\User;
use App\Models\MataPelajaran;
use App\Models\JamBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalPelajaranController extends Controller
{
    /**
     * Tampilkan halaman jadwal
     */
    public function index(Request $request)
    {
        $query = JadwalPelajaran::with(['kelas', 'guru', 'mataPelajaran']);

        // Filter berdasarkan guru
        if ($request->has('guru_id') && $request->guru_id != '') {
            $query->where('guru_id', $request->guru_id);
        }

        // Filter berdasarkan kelas
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter berdasarkan hari
        if ($request->has('hari') && $request->hari != '') {
            $query->where('hari', $request->hari);
        }

        $jadwal = $query->orderBy('hari')
                       ->orderBy('jam_ke')
                       ->get();

        // Data untuk filter dropdown
        $kelasList = Kelas::all();
        $guruList = User::where('role', 'guru')->with('mataPelajaran')->get();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('roles.superadmin.jadwal.index', compact('jadwal', 'kelasList', 'guruList', 'hari'));
    }

    /**
     * Get mata pelajaran berdasarkan guru (AJAX)
     */
    public function getMataPelajaranByGuru($guruId)
    {
        $guru = User::with('mataPelajaran')->findOrFail($guruId);
        return response()->json([
            'success' => true,
            'data' => $guru->mataPelajaran
        ]);
    }

    /**
     * Get jam belajar kelas (AJAX)
     */
    public function getJamBelajarKelas($kelasId)
    {
        $jamBelajar = JamBelajar::where('kelas_id', $kelasId)->first();
        
        if (!$jamBelajar) {
            return response()->json([
                'success' => false,
                'message' => 'Data jam belajar untuk kelas ini belum diatur'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $jamBelajar
        ]);
    }

    /**
     * Get jam ke berikutnya (AJAX)
     */
    public function getNextJamKe(Request $request)
    {
        $kelasId = $request->kelas_id;
        $hari = $request->hari;

        if (!$kelasId || !$hari) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas dan hari harus dipilih'
            ], 400);
        }

        $nextJam = JadwalPelajaran::getNextJamKe($kelasId, $hari);

        return response()->json([
            'success' => true,
            'jam_ke' => $nextJam
        ]);
    }

    /**
     * Simpan jadwal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_ke' => 'required|integer|min:1',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'keterangan' => 'nullable|string'
        ]);

        // Validasi waktu dalam jam belajar kelas
        $validasiJamBelajar = JadwalPelajaran::validasiWaktuJamBelajar(
            $request->kelas_id,
            $request->waktu_mulai,
            $request->waktu_selesai
        );

        if ($validasiJamBelajar !== true) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validasiJamBelajar);
        }

        // Cek bentrok dengan waktu istirahat
        $bentrokIstirahat = JadwalPelajaran::cekBentrokIstirahat(
            $request->kelas_id,
            $request->waktu_mulai,
            $request->waktu_selesai
        );

        if ($bentrokIstirahat) {
            return redirect()->back()
                ->withInput()
                ->with('error', $bentrokIstirahat);
        }

        // Cek bentrok guru
        if (JadwalPelajaran::cekBentrokGuru(
            $request->guru_id,
            $request->hari,
            $request->waktu_mulai,
            $request->waktu_selesai
        )) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Guru sudah memiliki jadwal di waktu yang sama!');
        }

        // Cek bentrok kelas
        if (JadwalPelajaran::cekBentrokKelas(
            $request->kelas_id,
            $request->hari,
            $request->waktu_mulai,
            $request->waktu_selesai
        )) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kelas sudah memiliki jadwal di waktu yang sama!');
        }

        JadwalPelajaran::create($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Update jadwal
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_ke' => 'required|integer|min:1',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'keterangan' => 'nullable|string'
        ]);

        // Validasi waktu dalam jam belajar kelas
        $validasiJamBelajar = JadwalPelajaran::validasiWaktuJamBelajar(
            $request->kelas_id,
            $request->waktu_mulai,
            $request->waktu_selesai
        );

        if ($validasiJamBelajar !== true) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validasiJamBelajar);
        }

        // Cek bentrok dengan waktu istirahat
        $bentrokIstirahat = JadwalPelajaran::cekBentrokIstirahat(
            $request->kelas_id,
            $request->waktu_mulai,
            $request->waktu_selesai
        );

        if ($bentrokIstirahat) {
            return redirect()->back()
                ->withInput()
                ->with('error', $bentrokIstirahat);
        }

        // Cek bentrok guru (exclude jadwal ini sendiri)
        if (JadwalPelajaran::cekBentrokGuru(
            $request->guru_id,
            $request->hari,
            $request->waktu_mulai,
            $request->waktu_selesai,
            $id
        )) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Guru sudah memiliki jadwal di waktu yang sama!');
        }

        // Cek bentrok kelas (exclude jadwal ini sendiri)
        if (JadwalPelajaran::cekBentrokKelas(
            $request->kelas_id,
            $request->hari,
            $request->waktu_mulai,
            $request->waktu_selesai,
            $id
        )) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kelas sudah memiliki jadwal di waktu yang sama!');
        }

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Hapus jadwal
     */
    public function destroy($id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Tampilkan jadwal per guru
     */
    public function jadwalGuru($guruId)
    {
        $guru = User::findOrFail($guruId);
        
        $jadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get()
            ->groupBy('hari');

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('roles.superadmin.jadwal.guru', compact('guru', 'jadwal', 'hari'));
    }

    /**
     * Tampilkan jadwal per kelas
     */
    public function jadwalKelas($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        
        $jadwal = JadwalPelajaran::with(['guru', 'mataPelajaran'])
            ->where('kelas_id', $kelasId)
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get()
            ->groupBy('hari');

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('roles.superadmin.jadwal.kelas', compact('kelas', 'jadwal', 'hari'));
    }
}