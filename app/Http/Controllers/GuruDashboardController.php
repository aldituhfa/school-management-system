<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\MateriPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruDashboardController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user();
        
        // Statistik Cards
        $totalSiswa = $this->getTotalSiswa($guru->id);
        $jadwalMingguIni = $this->getJadwalMingguIni($guru->id);
        $totalMateri = $this->getTotalMateri($guru->id);
        $totalKelas = $this->getTotalKelas($guru->id);
        
        // Data Siswa Terbaru dengan Pagination dan Search
        $searchSiswa = $request->input('search_siswa');
        $siswaTerbaru = $this->getSiswaTerbaru($guru->id, $searchSiswa);
        
        // Jadwal Minggu Ini Detail dengan Pagination
        $jadwalDetail = $this->getJadwalMingguIniDetail($guru->id);
        
        // Materi Pembelajaran dengan Pagination
        $materiPembelajaran = $this->getMateriPembelajaran($guru->id);
        
        // Aktivitas Terbaru dengan Pagination
        $aktivitasTerbaru = $this->getAktivitasTerbaru($guru->id);
        
        return view('roles.guru.dashboard', compact(
            'guru',
            'totalSiswa',
            'jadwalMingguIni',
            'totalMateri',
            'totalKelas',
            'siswaTerbaru',
            'jadwalDetail',
            'materiPembelajaran',
            'aktivitasTerbaru',
            'searchSiswa'
        ));
    }
    
    private function getTotalSiswa($guruId)
{
    $kelasIds = JadwalPelajaran::where('guru_id', $guruId)
        ->pluck('kelas_id');

    if ($kelasIds->isEmpty()) {
        return 0;
    }

    return Siswa::whereIn('kelas_id', $kelasIds)->count();
}

    
    private function getJadwalMingguIni($guruId)
    {
        // Hitung semua jadwal guru (tidak filter berdasarkan hari)
        return JadwalPelajaran::where('guru_id', $guruId)->count();
    }
    
    private function getTotalMateri($guruId)
    {
        return MateriPembelajaran::where('guru_id', $guruId)->count();
    }
    
    private function getSiswaTerbaru($guruId, $search = null)
    {
        $kelasIds = JadwalPelajaran::where('guru_id', $guruId)
            ->distinct()
            ->pluck('kelas_id');
        
        $query = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds);
        
        // Tambahkan search jika ada
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'LIKE', "%{$search}%")
                  ->orWhere('nisn', 'LIKE', "%{$search}%")
                  ->orWhere('tempat_lahir', 'LIKE', "%{$search}%")
                  ->orWhere('agama', 'LIKE', "%{$search}%");
            });
        }
        
        return $query->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'siswa_page');
    }
    
    private function getJadwalMingguIniDetail($guruId)
    {
        // Ambil semua jadwal guru dengan pagination per hari
        $allJadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('waktu_mulai')
            ->paginate(10, ['*'], 'jadwal_page');
        
        // Group by hari setelah pagination
        $grouped = $allJadwal->getCollection()->groupBy('hari');
        $allJadwal->setCollection(collect($grouped));
        
        return $allJadwal;
    }
    
    private function getMateriPembelajaran($guruId)
    {
        return MateriPembelajaran::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'materi_page');
    }
    
    private function getTotalKelas($guruId)
    {
        // Ambil jumlah kelas unik yang diajar guru
        return JadwalPelajaran::where('guru_id', $guruId)
            ->distinct()
            ->count('kelas_id');
    }
    
    private function getAktivitasTerbaru($guruId)
    {
        // Ambil aktivitas terbaru dengan pagination
        return MateriPembelajaran::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'aktivitas_page');
    }
    
    private function getDayName($dayOfWeek)
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];
        
        return $days[$dayOfWeek] ?? 'Senin';
    }
}