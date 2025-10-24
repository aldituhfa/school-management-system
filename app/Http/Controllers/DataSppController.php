<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\BiayaSpp;
use App\Models\TahunAjaran;
use App\Models\TagihanSpp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataSppController extends Controller
{
    // INDEX: daftar siswa + dropdown tahun ajaran
    public function index(Request $request)
    {
        // Ambil semua tahun ajaran
        $tahunAjaran = TahunAjaran::all();

        // Cek apakah user memilih tahun ajaran baru
        if ($request->has('tahun_ajaran_id')) {
            session(['tahun_ajaran_id' => $request->tahun_ajaran_id]);
        }

        // Ambil tahun ajaran yang tersimpan di session, atau default ke pertama
        $tahunAjaranId = session('tahun_ajaran_id', TahunAjaran::first()->id ?? null);

        // Data lainnya
        $kelas = Kelas::all();
        $siswa = Siswa::with(['kelas', 'status'])->paginate(10);

        return view('roles.tu.data_spp.index', compact('siswa', 'kelas', 'tahunAjaran', 'tahunAjaranId'));
    }


    // DETAIL: detail siswa + tabel tagihan spp
    public function show($id, Request $request)
    {
        $siswa = Siswa::with(['kelas', 'status'])->findOrFail($id);

        // Ambil ID tahun ajaran dari request atau session
        $tahunAjaranId = $request->get('tahun_ajaran_id') ?? session('tahun_ajaran_id', TahunAjaran::first()->id);

        // Ambil data tahun ajaran
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        $tahunMulai = $tahunAjaran ? $tahunAjaran->tahun_mulai : date('Y');

        // Ambil nominal biaya SPP
        $biaya = BiayaSpp::where('tahun_ajaran_id', $tahunAjaranId)->first();
        $nominal = $biaya ? $biaya->nominal : 0;

        // List bulan 1–12 dengan tahun dari tahun ajaran
        $bulanList = [
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

        foreach ($bulanList as $bulan) {
            $namaBulan = $bulan . ' ' . $tahunMulai; // contoh: Januari 2022

            $tagihan = TagihanSpp::firstOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'bulan' => $namaBulan,
                ],
                [
                    'nominal' => $nominal,
                    'status' => 'belum lunas',
                ]
            );

            // Update nominal jika berubah
            if ($tagihan->nominal != $nominal) {
                $tagihan->update(['nominal' => $nominal]);
            }
        }

        // Ambil semua tagihan untuk siswa dan tahun ajaran yang dipilih
        $tagihan = TagihanSpp::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderByRaw("FIELD(bulan, 'Januari $tahunMulai','Februari $tahunMulai','Maret $tahunMulai','April $tahunMulai','Mei $tahunMulai','Juni $tahunMulai','Juli $tahunMulai','Agustus $tahunMulai','September $tahunMulai','Oktober $tahunMulai','November $tahunMulai','Desember $tahunMulai')")
            ->get();

        return view('roles.tu.data_spp.detail', compact('siswa', 'tagihan', 'tahunAjaranId', 'nominal', 'tahunAjaran'));
    }


    public function bayar(Request $request, $id)
    {
        $tagihan = TagihanSpp::findOrFail($id);

        // Update data tagihan menjadi lunas
        $tagihan->update([
            'tanggal_bayar' => now(),
            'status' => 'lunas',
            'keterangan' => 'Dibayar pada ' . now()->translatedFormat('d F Y'),
        ]);

        return back()->with('success', 'Tagihan bulan ' . $tagihan->bulan . ' telah dibayar.');
    }
}
