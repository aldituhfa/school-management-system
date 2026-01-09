<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMateriRequest;
use App\Models\MateriPembelajaran;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    // Step 1: Menampilkan daftar jadwal
    public function index(Request $request)
    {
        $query = JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])
            ->where('guru_id', auth()->id());

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('mataPelajaran', function ($q) use ($search) {
                    $q->where('nama_mata_pelajaran', 'like', "%{$search}%");
                })
                ->orWhereHas('kelas', function ($q) use ($search) {
                    $q->where('nama_kelas', 'like', "%{$search}%");
                });
            });
        }

        $jadwals = $query->get();

        $materis = MateriPembelajaran::with(['jadwal.mataPelajaran', 'jadwal.kelas'])
            ->where('guru_id', auth()->id())
            ->latest()
            ->get()
            ->groupBy('jadwal_id');

        return view('roles.guru.materi.index', compact('jadwals', 'materis'));
    }

    // Step 2: Form upload materi
    public function create(Request $request)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])
            ->where('id', $request->jadwal_id)
            ->where('guru_id', auth()->id())
            ->firstOrFail();

        return view('roles.guru.materi.create', compact('jadwal'));
    }

    // Proses upload materi
    public function store(StoreMateriRequest $request)
    {
        try {
            $file = $request->file('file');

            $fileName = Str::slug($request->judul) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('materi', $fileName, 'public');

            $materi = MateriPembelajaran::create([
                'jadwal_id'   => $request->jadwal_id,
                'guru_id'     => auth()->id(),
                'judul'       => $request->judul,
                'deskripsi'   => $request->deskripsi,
                'tipe_materi' => $request->tipe_materi,
                'file_path'   => $filePath,
                'file_name'   => $file->getClientOriginalName(),
                'file_size'   => $file->getSize(),
            ]);

            $materi->load('jadwal.mataPelajaran', 'jadwal.kelas');

            return redirect()->route('guru.materi.success')->with('materi', [
                'judul'      => $materi->judul,
                'deskripsi'  => $materi->deskripsi,
                'mapel'      => $materi->jadwal->mataPelajaran->nama_mata_pelajaran,
                'kelas'      => $materi->jadwal->kelas->nama_kelas,
                'file_name'  => $materi->file_name,
                'file_size'  => $materi->file_size_formatted ?? $materi->file_size,
                'created_at' => $materi->created_at->format('d M Y, H:i'),
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload materi: ' . $e->getMessage())
                         ->withInput();
        }
    }

    // Step 3: Success page
    public function success()
    {
        if (!session()->has('materi')) {
            return redirect()->route('guru.materi.index');
        }

        return view('roles.guru.materi.success');
    }

    // Halaman list semua materi
    public function list(Request $request)
    {
        $query = MateriPembelajaran::with(['jadwal.mataPelajaran', 'jadwal.kelas'])
            ->where('guru_id', auth()->id());

        if ($request->filled('jadwal_id')) {
            $query->where('jadwal_id', $request->jadwal_id);
        }

        if ($request->filled('tipe_materi')) {
            $query->where('tipe_materi', $request->tipe_materi);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $materis = $query->latest()->paginate(10);

        $jadwals = JadwalPelajaran::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', auth()->id())
            ->get();

        return view('roles.guru.materi.list', compact('materis', 'jadwals'));
    }

    // Download materi
    public function download($id)
    {
        $materi = MateriPembelajaran::where('guru_id', auth()->id())
            ->findOrFail($id);

        return Storage::disk('public')->download($materi->file_path, $materi->file_name);
    }

    // Hapus materi
    public function destroy($id)
    {
        try {
            $materi = MateriPembelajaran::where('guru_id', auth()->id())
                ->findOrFail($id);

            if (Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $materi->delete();

            return back()->with('success', 'Materi berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }

    // API AJAX
    public function getJadwal(Request $request)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])
            ->where('id', $request->id)
            ->where('guru_id', auth()->id())
            ->first();

        return response()->json($jadwal);
    }
}
