<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanSppBulananExport;
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

        $statusTahunAjaran = \App\Models\BiayaSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->with('status')
            ->first();

        $isNonaktif = $statusTahunAjaran
            && $statusTahunAjaran->status
            && strtolower($statusTahunAjaran->status->nama_status) === 'nonaktif';
            

        $bulan = $request->get(
            'bulan',
            session('bulan', 'Januari')
        );
        $kelasId = $request->get('kelas_id');

        if ($request->has('tahun_ajaran_id')) {
            session(['tahun_ajaran_id' => $request->tahun_ajaran_id]);
        }

        if ($request->has('tahun_ajaran_id')) {
            session(['tahun_ajaran_id' => $request->tahun_ajaran_id]);
        }

        if ($request->has('bulan')) {
            session(['bulan' => $request->bulan]);
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
            'siswaLunas',
            'isNonaktif'
        ));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredData($request);

        $pdf = Pdf::loadView(
            'roles.tu.laporan_spp_bulanan.pdf',
            $data
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan_spp_bulanan.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanSppBulananExport($request),
            'laporan_spp_bulanan.xlsx'
        );
    }

    private function getFilteredData(Request $request)
    {
        $tahunAjaranId = $request->tahun_ajaran_id;
        $bulan = $request->bulan;
        $kelasId = $request->kelas_id;

        $tahunAjaranNama = \App\Models\TahunAjaran::where('id', $tahunAjaranId)
            ->value('nama_tahun') ?? '-';

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

        return [
            'laporanBulanan' => $laporanBulanan,
            'bulan' => $bulan,
            'tahunAjaranNama' => $tahunAjaranNama,
        ];
    }
}
