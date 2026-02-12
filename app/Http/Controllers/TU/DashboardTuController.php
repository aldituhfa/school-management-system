<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\Kegiatan;
use App\Models\BiayaSpp;

class DashboardTuController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // TAHUN AJARAN
        // =========================
        $tahunAjaran = TahunAjaran::all();

        if ($request->has('tahun_ajaran_id')) {
            session(['tahun_ajaran_id' => $request->tahun_ajaran_id]);
        }

        $tahunAjaranId = session(
            'tahun_ajaran_id',
            TahunAjaran::first()->id ?? null
        );

        // =========================
        // CEK STATUS TAHUN AJARAN (FIX)
        // =========================
        $statusTahunAjaran = BiayaSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->with('status')
            ->first();

        $tahunAjaranNonaktif = $statusTahunAjaran
            && $statusTahunAjaran->status
            && strtolower($statusTahunAjaran->status->nama_status) === 'nonaktif';

        // =========================
        // DATA SPP
        // =========================
        $totalSiswa = Siswa::count();

        $totalTagihan = TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)->sum('nominal');

        $totalDibayar = TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'lunas')
            ->sum('nominal');

        $totalTunggakan = $totalTagihan - $totalDibayar;

        // =========================
        // DATA BULANAN
        // =========================
        $bulanLabels = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $sppBulanan = [];

        foreach ($bulanLabels as $bulan) {
            $sppBulanan[] = TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'lunas')
                ->where('bulan', $bulan)
                ->sum('nominal');
        }


        // =========================
        // STATUS TAHUNAN
        // =========================
        $sppTahunan = [
            'lunas' => TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'lunas')->count(),
            'belum' => TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'belum lunas')->count(),
        ];

        // =========================
        // KEGIATAN
        // =========================
        $totalKegiatan = Kegiatan::count();
        $kegiatanAktif = Kegiatan::where('status', 'Sedang Berlangsung')->count();
        $kegiatanSelesai = Kegiatan::where('status', 'Selesai')->count();

        return view('roles.tu.dashboard', compact(
            'tahunAjaran',
            'tahunAjaranId',
            'tahunAjaranNonaktif',
            'totalSiswa',
            'totalTagihan',
            'totalDibayar',
            'totalTunggakan',
            'bulanLabels',
            'sppBulanan',
            'sppTahunan',
            'totalKegiatan',
            'kegiatanAktif',
            'kegiatanSelesai'
        ));
    }
}
