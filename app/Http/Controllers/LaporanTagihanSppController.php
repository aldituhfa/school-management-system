<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanTagihanSppExport;

class LaporanTagihanSppController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::all();
        $kelas = Kelas::all();

        $tahunAjaranId = $request->get(
            'tahun_ajaran_id',
            session('tahun_ajaran_id', TahunAjaran::first()->id ?? null)
        );

        $kelasId = $request->get('kelas_id');
        $search = $request->get('search');

        if ($request->has('tahun_ajaran_id')) {
            session(['tahun_ajaran_id' => $request->tahun_ajaran_id]);
        }

        // Ambil siswa sesuai filter
        $siswa = Siswa::with(['kelas', 'status'])
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when(
                $search,
                fn($q) =>
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
            )
            ->orderBy('nama_siswa')
            ->get();

        // Nominal SPP per bulan
        $nominalBulanan = TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->value('nominal') ?? 0;

        $jumlahSiswa = $siswa->count();

        // TOTAL TAGIHAN IDEAL (12 BULAN × SEMUA SISWA)
        $totalTagihan = $nominalBulanan * 12 * $jumlahSiswa;

        // TOTAL LUNAS (REALISASI)
        $totalLunas = TagihanSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->when(
                $kelasId,
                fn($q) =>
                $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $kelasId))
            )
            ->where('status', 'lunas')
            ->sum('nominal');

        // HITUNG BULAN LUNAS & TUNGGAKAN PER SISWA
        $totalTunggakan = 0;

        foreach ($siswa as $sw) {
            $bulanLunas = TagihanSpp::where('siswa_id', $sw->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'lunas')
                ->count();

            $sw->bulan_lunas = $bulanLunas;
            $sw->total_bulan = 12;

            $tunggakanSiswa = (12 - $bulanLunas) * $nominalBulanan;
            $totalTunggakan += $tunggakanSiswa;
        }

        return view('roles.tu.laporan_tagihan_spp.index', compact(
            'tahunAjaran',
            'kelas',
            'tahunAjaranId',
            'kelasId',
            'siswa',
            'totalTagihan',
            'totalLunas',
            'totalTunggakan',
            'jumlahSiswa',
            'search'
        ));
    }


    public function exportPdf(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', session('tahun_ajaran_id'));
        $kelasId = $request->get('kelas_id');
        $search = $request->get('search');

        $siswa = Siswa::with(['kelas', 'status'])
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($search, fn($q) => $q->where('nama_siswa', 'like', "%$search%")->orWhere('nisn', 'like', "%$search%"))
            ->orderBy('nama_siswa')
            ->get();

        foreach ($siswa as $sw) {
            $totalTagihan = TagihanSpp::where('siswa_id', $sw->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();
            $bulanLunas = TagihanSpp::where('siswa_id', $sw->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'lunas')
                ->count();
            $sw->bulan_lunas = $bulanLunas;
            $sw->total_bulan = $totalTagihan ?: 12;
        }

        // Ambil tahun ajaran yang dipilih
        $tahunAjaran = \App\Models\TahunAjaran::find($tahunAjaranId);

        $pdf = Pdf::loadView('roles.tu.laporan_tagihan_spp.pdf', [
            'siswa' => $siswa,
            'tahunAjaran' => $tahunAjaran,
        ]);

        $fileName = 'laporan_tagihan_spp_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'laporan_tagihan_spp_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new LaporanTagihanSppExport($request->all()), $fileName);
    }
}
