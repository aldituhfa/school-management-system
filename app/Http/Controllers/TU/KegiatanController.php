<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\KegiatanPembayaran;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::all();

        $totalTarget = $kegiatans->sum('target_dana');
        $totalTerkumpul = $kegiatans->sum('terkumpul');
        $totalKurang = $totalTarget - $totalTerkumpul;

        return view('roles.tu.kegiatan.index', compact('kegiatans', 'totalTarget', 'totalTerkumpul', 'totalKurang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'target_dana' => 'required|numeric|min:0',
            'nominal_per_siswa' => 'required|numeric|min:0',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'target_dana' => $request->target_dana,
            'nominal_per_siswa' => $request->nominal_per_siswa,
            'kelas_ids' => $request->kelas_ids,
            'terkumpul' => 0,
        ]);

        // Optional: buat baris pembayaran awal (status 'belum') untuk semua siswa di kelas yang dipilih
        // Kalau dataset besar, pertimbangkan background job; di sini kita langsung insert
        $siswaList = Siswa::whereIn('kelas_id', $request->kelas_ids)->get();

        $insert = [];
        foreach ($siswaList as $s) {
            $insert[] = [
                'kegiatan_id' => $kegiatan->id,
                'siswa_id' => $s->id,
                'kelas_id' => $s->kelas_id,
                'jumlah' => $kegiatan->nominal_per_siswa,
                'status' => 'belum',
                'tanggal_bayar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($insert)) {
            // gunakan upsert agar tidak error jika sudah ada
            KegiatanPembayaran::insert($insert);
        }

        return redirect()->route('tu.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Ambil kelas yang terlibat
        $kelasList = Kelas::whereIn('id', $kegiatan->kelas_ids ?? [])->get();

        // Ambil semua siswa di kelas yang dipilih
        $siswa = Siswa::whereIn('kelas_id', $kegiatan->kelas_ids ?? [])->with('kelas')->get();

        // Ambil pembayaran terkait (keyed by siswa_id)
        $pembayarans = KegiatanPembayaran::where('kegiatan_id', $kegiatan->id)
            ->whereIn('siswa_id', $siswa->pluck('id')->toArray())
            ->get()
            ->keyBy('siswa_id');

        // Update otomatis status kegiatan jika ada terkumpul
        if ($kegiatan->terkumpul > 0 && $kegiatan->status == 'Perencanaan') {
            $kegiatan->update(['status' => 'Sedang Berlangsung']);
        }

        return view('roles.tu.kegiatan.detail', compact('kegiatan', 'kelasList', 'siswa', 'pembayarans'));
    }

    // tandai selesai kegiatan
    public function selesai($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'Selesai']);
        return back()->with('success', 'Kegiatan telah diselesaikan.');
    }

    // bayar per siswa (POST)
    public function bayar(Request $request, $kegiatanId, $siswaId)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatanId);
        $siswa = Siswa::findOrFail($siswaId);

        DB::transaction(function () use ($kegiatan, $siswa) {
            $p = KegiatanPembayaran::updateOrCreate(
                ['kegiatan_id' => $kegiatan->id, 'siswa_id' => $siswa->id],
                [
                    'kelas_id' => $siswa->kelas_id,
                    'jumlah' => $kegiatan->nominal_per_siswa,
                    'status' => 'lunas',
                    'tanggal_bayar' => now(),
                ]
            );

            // update total terkumpul di kegiatan (pastikan tidak double count jika sebelumnya sudah lunas)
            // jika sebelumnya belum lunas tambah jumlah; jika sebelumnya lunas, jangan tambah lagi
            // cek apakah sebelumnya sudah lunas
            // kita rely pada apakah created_at == updated_at? safer to check previous status
            // retrieve previous record
            // simpler: decrement/increment based on whether previous status was 'belum'
            // get previous
            // But updateOrCreate returns model; to determine previous, try find first
            // We'll implement with upsert logic: check existing row before update
            $existing = KegiatanPembayaran::where('kegiatan_id', $kegiatan->id)
                ->where('siswa_id', $siswa->id)
                ->first();

            // If existing was null OR existing.status == 'belum' then increment
            if (!$existing || $existing->wasRecentlyCreated || $existing->status == 'belum') {
                $kegiatan->increment('terkumpul', $kegiatan->nominal_per_siswa);
            }
        });

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    // batalkan pembayaran (POST)
    public function cancel(Request $request, $kegiatanId, $siswaId)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatanId);
        $p = KegiatanPembayaran::where('kegiatan_id', $kegiatanId)
            ->where('siswa_id', $siswaId)
            ->first();

        if ($p && $p->status == 'lunas') {
            DB::transaction(function () use ($p, $kegiatan) {
                // kurangi total terkumpul
                $kegiatan->decrement('terkumpul', $p->jumlah);
                // set status kembali
                $p->update([
                    'status' => 'belum',
                    'tanggal_bayar' => null,
                ]);
            });
        }

        return back()->with('success', 'Pembayaran dibatalkan.');
    }
}
