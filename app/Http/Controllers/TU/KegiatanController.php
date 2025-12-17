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
        $kegiatans = Kegiatan::latest()->get();

        $totalTarget = $kegiatans->sum('target_dana');
        $totalTerkumpul = $kegiatans->sum('terkumpul');
        $totalKurang = $totalTarget - $totalTerkumpul;

        return view(
            'roles.tu.kegiatan.index',
            compact('kegiatans', 'totalTarget', 'totalTerkumpul', 'totalKurang')
        );
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

        $kelasList = Kelas::whereIn('id', $kegiatan->kelas_ids ?? [])->get();
        $siswa = Siswa::whereIn('kelas_id', $kegiatan->kelas_ids ?? [])->with('kelas')->get();

        $pembayarans = KegiatanPembayaran::where('kegiatan_id', $kegiatan->id)
            ->whereIn('siswa_id', $siswa->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        // ============
        // Update Status ke "Sedang Berlangsung" jika ada pembayaran masuk
        // ============
        if ($kegiatan->terkumpul > 0 && $kegiatan->status == 'Perencanaan') {
            $kegiatan->update(['status' => 'Sedang Berlangsung']);
        }

        // Hitung progress
        $kegiatan->progress = $kegiatan->target_dana > 0
            ? ($kegiatan->terkumpul / $kegiatan->target_dana) * 100
            : 0;

        return view('roles.tu.kegiatan.detail', compact('kegiatan', 'kelasList', 'siswa', 'pembayarans'));
    }


    public function setSelesai($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->status == 'Sedang Berlangsung') {
            $kegiatan->update(['status' => 'Selesai']);
        }

        return back()->with('success', 'Kegiatan telah diselesaikan.');
    }


    public function bayar($id, $siswa_id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Tidak boleh membayar jika sudah selesai
        if ($kegiatan->status == 'Selesai') {
            return back()->with('error', 'Kegiatan sudah selesai. Tidak dapat melakukan pembayaran.');
        }

        $bayar = KegiatanPembayaran::where('kegiatan_id', $id)
            ->where('siswa_id', $siswa_id)
            ->first();

        if ($bayar && $bayar->status == 'belum') {
            $bayar->update([
                'status' => 'lunas',
                'tanggal_bayar' => now()
            ]);

            $kegiatan->increment('terkumpul', $bayar->jumlah);

            if ($kegiatan->status == 'Perencanaan') {
                $kegiatan->update(['status' => 'Sedang Berlangsung']);
            }
        }

        return back()->with('success', 'Pembayaran berhasil.');
    }


    public function cancel($id, $siswa_id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Tidak boleh membatalkan jika sudah selesai
        if ($kegiatan->status == 'Selesai') {
            return back()->with('error', 'Kegiatan sudah selesai. Tidak dapat membatalkan pembayaran.');
        }

        $bayar = KegiatanPembayaran::where('kegiatan_id', $id)
            ->where('siswa_id', $siswa_id)
            ->first();

        if ($bayar && $bayar->status == 'lunas') {
            $bayar->update([
                'status' => 'belum',
                'tanggal_bayar' => null
            ]);

            $kegiatan->decrement('terkumpul', $bayar->jumlah);

            if ($kegiatan->terkumpul == 0) {
                $kegiatan->update(['status' => 'Perencanaan']);
            }
        }

        return back()->with('success', 'Pembayaran dibatalkan.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            // Ambil kegiatan
            $kegiatan = Kegiatan::findOrFail($id);

            // Hapus semua pembayaran terkait kegiatan ini
            KegiatanPembayaran::where('kegiatan_id', $kegiatan->id)->delete();

            // Hapus kegiatan
            $kegiatan->delete();
        });

        return redirect()
            ->route('tu.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
