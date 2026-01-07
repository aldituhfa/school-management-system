<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalGuruController extends Controller
{
    /**
     * Menampilkan daftar kelas yang diajar oleh guru
     */
    public function index(Request $request)
    {
        $guru_id = Auth::id();
        
        // Query untuk mendapatkan kelas yang diajar
        $query = DB::table('jadwal_pelajaran')
            ->join('kelas', 'jadwal_pelajaran.kelas_id', '=', 'kelas.id')
            ->where('jadwal_pelajaran.guru_id', $guru_id)
            ->select(
                'kelas.id',
                'kelas.nama_kelas',
                DB::raw('COUNT(DISTINCT jadwal_pelajaran.hari) as jumlah_hari'),
                DB::raw('COUNT(jadwal_pelajaran.id) as jumlah_jadwal')
            )
            ->groupBy('kelas.id', 'kelas.nama_kelas');
        
        // Filter search berdasarkan nama kelas
        if ($request->filled('search')) {
            $query->where('kelas.nama_kelas', 'like', '%' . $request->search . '%');
        }
        
        $kelas_list = $query->orderBy('kelas.nama_kelas')->get();
        
        return view('roles.guru.jadwal.index', compact('kelas_list'));
    }
    
    /**
     * Menampilkan detail jadwal per kelas
     */
    public function show(Request $request, $kelas_id)
    {
        $guru_id = Auth::id();
        
        // Cek apakah guru mengajar di kelas ini
        $kelas = DB::table('kelas')
            ->whereExists(function ($query) use ($kelas_id, $guru_id) {
                $query->select(DB::raw(1))
                    ->from('jadwal_pelajaran')
                    ->whereColumn('jadwal_pelajaran.kelas_id', 'kelas.id')
                    ->where('jadwal_pelajaran.kelas_id', $kelas_id)
                    ->where('jadwal_pelajaran.guru_id', $guru_id);
            })
            ->where('kelas.id', $kelas_id)
            ->first();
        
        if (!$kelas) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini');
        }
        
        // Query jadwal
        $query = DB::table('jadwal_pelajaran')
            ->join('mata_pelajaran', 'jadwal_pelajaran.mata_pelajaran_id', '=', 'mata_pelajaran.id')
            ->join('users', 'jadwal_pelajaran.guru_id', '=', 'users.id')
            ->where('jadwal_pelajaran.kelas_id', $kelas_id)
            ->select(
                'jadwal_pelajaran.*',
                'mata_pelajaran.nama_mata_pelajaran',
                'users.name as nama_guru'
            );
        
        // Filter berdasarkan hari
        if ($request->filled('hari')) {
            $query->where('jadwal_pelajaran.hari', $request->hari);
        }
        
        $jadwal_list = $query->orderBy('jadwal_pelajaran.hari')
            ->orderBy('jadwal_pelajaran.jam_ke')
            ->get();
        
        // Group jadwal berdasarkan hari
        $jadwal_grouped = $jadwal_list->groupBy('hari');
        
        // Daftar hari
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        return view('roles.guru.jadwal.show', compact('kelas', 'jadwal_grouped', 'hari_list'));
    }
}