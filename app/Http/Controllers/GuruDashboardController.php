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
    public function index()
    {
        $guru = Auth::user();
        
        // Debug: Cek guru_id
        \Log::info('Guru ID: ' . $guru->id);
        \Log::info('Guru Name: ' . $guru->name);
        
        // Statistik Cards
        $totalSiswa = $this->getTotalSiswa($guru->id);
        $jadwalMingguIni = $this->getJadwalMingguIni($guru->id);
        $totalMateri = $this->getTotalMateri($guru->id);
        $totalKelas = $this->getTotalKelas($guru->id);
        
        // Debug: Cek jumlah jadwal
        \Log::info('Total Jadwal: ' . $jadwalMingguIni);
        
        // Data Siswa Terbaru (4 siswa terbaru dari kelas yang diajar guru)
        $siswaTerbaru = $this->getSiswaTerbaru($guru->id);
        
        // Jadwal Minggu Ini Detail
        $jadwalDetail = $this->getJadwalMingguIniDetail($guru->id);
        
        // Debug: Cek jadwal detail
        \Log::info('Jadwal Detail Count: ' . $jadwalDetail->count());
        \Log::info('Jadwal Detail: ' . json_encode($jadwalDetail));
        
        // Materi Pembelajaran
        $materiPembelajaran = $this->getMateriPembelajaran($guru->id);
        
        // Aktivitas Terbaru
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
            'aktivitasTerbaru'
        ));
    }
    
    private function getTotalSiswa($guruId)
    {
        // Ambil semua kelas yang diajar guru
        $kelasIds = JadwalPelajaran::where('guru_id', $guruId)
            ->distinct()
            ->pluck('kelas_id');
        
        // Hitung total siswa dari kelas-kelas tersebut
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
    
    private function getSiswaTerbaru($guruId)
    {
        $kelasIds = JadwalPelajaran::where('guru_id', $guruId)
            ->distinct()
            ->pluck('kelas_id');
        
        return Siswa::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    }
    
    private function getJadwalMingguIniDetail($guruId)
    {
        $today = Carbon::now();
        $currentDayOfWeek = $today->dayOfWeek; // 0 = Minggu, 1 = Senin, dst
        
        // Mapping hari dalam bahasa Indonesia
        $hariMapping = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            0 => 'Minggu'
        ];
        
        // Ambil semua jadwal guru, tidak filter berdasarkan hari
        // Karena kita ingin tampilkan semua jadwal minggu ini
        $jadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('waktu_mulai')
            ->get()
            ->groupBy('hari');
        
        return $jadwal;
    }
    
    private function getMateriPembelajaran($guruId)
    {
        return MateriPembelajaran::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
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
        // Ambil 10 aktivitas terbaru (upload materi)
        return MateriPembelajaran::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
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