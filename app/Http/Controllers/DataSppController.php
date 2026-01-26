<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\BiayaSpp;
use App\Models\TahunAjaran;
use App\Models\TagihanSpp;
use Carbon\Carbon;
use App\Models\Finance;
use Illuminate\Support\Facades\Auth;
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
        // Ambil status tahun ajaran dari biaya SPP
        $statusTahunAjaran = BiayaSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->with('status')
            ->first();

        $isNonaktif = $statusTahunAjaran && strtolower($statusTahunAjaran->status->nama_status) === 'nonaktif';

        // Data lainnya
        $kelas = Kelas::all();
        $siswa = Siswa::with(['kelas', 'status'])->paginate(10);

        $view = request()->routeIs('superadmin.*')
            ? 'roles.superadmin.tata_usaha.data_spp.index'
            : 'roles.tu.data_spp.index';

        return view($view, compact('siswa', 'kelas', 'tahunAjaran', 'tahunAjaranId', 'isNonaktif'));
    }


    // DETAIL: detail siswa + tabel tagihan spp
    public function show($id, Request $request)
    {
        $siswa = Siswa::with(['kelas', 'status'])->findOrFail($id);

        // Ambil ID tahun ajaran dari request atau session
        $tahunAjaranId = $request->get('tahun_ajaran_id') ?? session('tahun_ajaran_id', TahunAjaran::first()->id);
        $statusTahunAjaran = BiayaSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->with('status')
            ->first();

        $isNonaktif = $statusTahunAjaran && strtolower($statusTahunAjaran->status->nama_status) === 'nonaktif';


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

        $view = request()->routeIs('superadmin.*')
            ? 'roles.superadmin.tata_usaha.data_spp.detail'
            : 'roles.tu.data_spp.detail';

        return view($view, compact(
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
            'terakhirBayar',
            'isNonaktif'
        ));
    }


    public function bayar(Request $request, $id)
    {
        $tagihan = TagihanSpp::with('siswa')->findOrFail($id);

        // CEK JIKA SUDAH LUNAS (ANTI DOUBLE INPUT)
        if ($tagihan->status === 'lunas') {
            return back()->with('warning', 'Tagihan ini sudah lunas.');
        }

        // Update status SPP
        $tagihan->update([
            'tanggal_bayar' => now(),
            'status' => 'lunas',
            'keterangan' => 'Dibayar pada ' . now()->translatedFormat('d F Y'),
        ]);

        // === MASUK KE KAS ===
        Finance::create([
            'type' => 'kas',
            'category' => 'spp',
            'amount' => $tagihan->nominal,
            'in_out' => 'in',
            'description' => 'Pembayaran SPP Siswa',
            'user_id' => Auth::id(),
            'source' => 'spp',
        ]);

        return back()->with('success', 'Tagihan bulan ' . $tagihan->bulan . ' berhasil dibayar & masuk ke kas.');
    }


    public function cancel($id)
    {
        $tagihan = TagihanSpp::with('siswa')->findOrFail($id);

        if ($tagihan->status !== 'lunas') {
            return back()->with('warning', 'Tagihan belum lunas.');
        }

        // HAPUS TRANSAKSI KAS TERKAIT SPP
        Finance::where([
            'type' => 'kas',
            'category' => 'spp',
            'amount' => $tagihan->nominal,
            'description' => 'Pembayaran SPP Siswa',
        ])->latest()->first()?->delete();

        // Update status tagihan
        $tagihan->update([
            'tanggal_bayar' => null,
            'status' => 'belum lunas',
            'keterangan' => 'Dibatalkan pada ' . now()->translatedFormat('d F Y'),
        ]);

        return back()->with('success', 'Pembayaran SPP dibatalkan & kas dikoreksi.');
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
