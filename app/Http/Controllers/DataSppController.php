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

        // List bulan
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

        // Generate tagihan otomatis jika belum ada
        foreach ($bulanList as $bulan) {
            $namaBulan = $bulan . ' ' . $tahunMulai;
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

            if ($tagihan->nominal != $nominal) {
                $tagihan->update(['nominal' => $nominal]);
            }
        }

        // Ambil semua tagihan untuk tahun ajaran yang dipilih
        $tagihan = TagihanSpp::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderByRaw("FIELD(bulan, 'Januari $tahunMulai','Februari $tahunMulai','Maret $tahunMulai','April $tahunMulai','Mei $tahunMulai','Juni $tahunMulai','Juli $tahunMulai','Agustus $tahunMulai','September $tahunMulai','Oktober $tahunMulai','November $tahunMulai','Desember $tahunMulai')")
            ->get();

        // === Statistik SPP ===
        $totalTagihan = $tagihan->sum('nominal');
        $totalDibayar = $tagihan->where('status', 'lunas')->sum('nominal');
        $sisaTagihan = $totalTagihan - $totalDibayar;
        $bulanLunas = $tagihan->where('status', 'lunas')->count();
        $bulanBelum = $tagihan->where('status', 'belum lunas')->count();
        $terakhirBayar = $tagihan->where('status', 'lunas')->max('tanggal_bayar');

        return view('roles.tu.data_spp.detail', compact(
            'siswa',
            'tagihan',
            'tahunAjaranId',
            'nominal',
            'tahunAjaran',
            'totalTagihan',
            'totalDibayar',
            'sisaTagihan',
            'bulanLunas',
            'bulanBelum',
            'terakhirBayar'
        ));
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

    public function cancel($id)
    {
        $tagihan = TagihanSpp::findOrFail($id);

        // Update data tagihan jadi belum lunas kembali
        $tagihan->update([
            'tanggal_bayar' => null,
            'status' => 'belum lunas',
            'keterangan' => 'Dibatalkan pada ' . now()->translatedFormat('d F Y'),
        ]);

        return back()->with('success', 'Pembayaran bulan ' . $tagihan->bulan . ' telah dibatalkan.');
    }

    public function update(Request $request, $id)
    {
        $tagihan = TagihanSpp::findOrFail($id);

        $request->validate([
            'tanggal_bayar' => 'nullable|date',
            'keterangan_tambahan' => 'nullable|string|max:255',
        ]);

        // Format tanggal bayar
        $tanggalBayar = $request->tanggal_bayar ? \Carbon\Carbon::parse($request->tanggal_bayar) : null;
        $textTanggal = $tanggalBayar ? 'Dibayar pada ' . $tanggalBayar->translatedFormat('d F Y') : null;

        // Gabungkan dengan keterangan tambahan
        $keteranganFinal = $textTanggal;
        if ($request->filled('keterangan_tambahan')) {
            $keteranganFinal .= "\n" . $request->keterangan_tambahan;
        }

        $tagihan->update([
            'tanggal_bayar' => $tanggalBayar,
            'keterangan' => $keteranganFinal,
        ]);

        return back()->with('success', 'Tagihan bulan ' . $tagihan->bulan . ' berhasil diperbarui.');
    }
}
