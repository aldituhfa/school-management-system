<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class LaporanSppBulananController extends Controller
{
    public function index(Request $request)
    {
        // =============================
        // DATA MASTER
        // =============================
        $tahunAjaran = TahunAjaran::all();
        $kelas       = Kelas::all();

        // =============================
        // FILTER
        // =============================
        $tahunAjaranId = $request->get(
            'tahun_ajaran_id',
            session('tahun_ajaran_id', TahunAjaran::first()->id ?? null)
        );

        $bulan = $request->get('bulan', 'Januari');
        $kelasId = $request->get('kelas_id');

        if ($request->has('tahun_ajaran_id')) {
            session(['tahun_ajaran_id' => $request->tahun_ajaran_id]);
        }

        // =============================
        // LAPORAN PER SISWA
        // =============================
        $laporanBulanan = Siswa::query()
            ->leftJoin('kelas', 'kelas.id', '=', 'siswa.kelas_id')

            ->leftJoin('biaya_spps', function ($join) use ($tahunAjaranId) {
                $join->on('biaya_spps.tahun_ajaran_id', DB::raw($tahunAjaranId));
            })

            ->leftJoin('tagihan_spps', function ($join) use ($tahunAjaranId, $bulan) {
                $join->on('siswa.id', '=', 'tagihan_spps.siswa_id')
                    ->where('tagihan_spps.tahun_ajaran_id', $tahunAjaranId)
                    ->where('tagihan_spps.bulan', $bulan);
            })

            ->when(
                $kelasId,
                fn($q) =>
                $q->where('siswa.kelas_id', $kelasId)
            )

            ->select(
                DB::raw("'$bulan' as bulan"),
                'siswa.nama_siswa',
                'kelas.nama_kelas',
                DB::raw('COALESCE(biaya_spps.nominal, 0) as nominal'),
                'tagihan_spps.tanggal_bayar',
                DB::raw("
            CASE 
                WHEN tagihan_spps.tanggal_bayar IS NOT NULL 
                THEN 'Lunas' 
                ELSE 'Menunggak' 
            END as status
        ")
            )
            ->orderBy('siswa.nama_siswa')
            ->get();

        // =============================
        // RINGKASAN
        // =============================
        $totalTagihan = $laporanBulanan->sum('nominal');
        $totalDibayar = $laporanBulanan
            ->where('status', 'Lunas')
            ->sum('nominal');

        $totalTunggakan = $totalTagihan - $totalDibayar;

        $totalSiswa = $laporanBulanan->count();
        $siswaLunas = $laporanBulanan
            ->where('status', 'Lunas')
            ->count();

        $daftarBulan = [
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

        return view('roles.tu.laporan_spp_bulanan.index', compact(
            'tahunAjaran',
            'kelas',
            'tahunAjaranId',
            'bulan',
            'kelasId',
            'laporanBulanan',
            'daftarBulan',
            'totalTagihan',
            'totalDibayar',
            'totalTunggakan',
            'totalSiswa',
            'siswaLunas'
        ));
    }
}
