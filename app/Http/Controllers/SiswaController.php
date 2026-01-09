<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\StatusSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaPerKelasExport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'status']);

        // --- Filter & Search ---
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%')
                    ->orWhereHas('kelas', function ($k) use ($request) {
                        $k->where('nama_kelas', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->kelas_id === 'belum_kelas') {
            // siswa yang belum punya kelas
            $query->whereNull('kelas_id');
        } elseif ($request->kelas_id) {
            // siswa dengan kelas tertentu
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // --- Pagination ---
        $siswa = $query
            ->orderBy('nama_siswa', 'asc')
            ->paginate(10)
            ->appends($request->all());

        $kelas = Kelas::all();
        $status = StatusSiswa::all();
        $columns = Schema::getColumnListing('siswa');

        return view('roles.superadmin.siswa.index', compact('siswa', 'kelas', 'status', 'columns'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $status = StatusSiswa::all();
        return view('siswa.create', compact('kelas', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn',
            'nama_siswa' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required',
            'status_id' => 'required|exists:status_siswa,id',
        ]);

        $columns = Schema::getColumnListing('siswa');
        $data = $request->only($columns);

        // kelas_id boleh kosong
        $data['kelas_id'] = $request->kelas_id ?? null;

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }


    public function edit(Siswa $siswa)
    {
        return response()->json($siswa);
    }

    public function update(Request $request, Siswa $siswa)
    {
        // Validasi kolom wajib (yang pasti ada)
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required|integer|exists:kelas,id',
            'agama' => 'required|string|max:50',
            'status_id' => 'required|integer|exists:status_siswa,id',
        ]);

        // Ambil semua kolom dari tabel 'siswa'
        $columns = Schema::getColumnListing('siswa');

        // Ambil data dari request hanya untuk kolom yang benar-benar ada di tabel
        $data = $request->only($columns);

        // Update data
        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function addColumn(Request $request)
    {
        $request->validate([
            'column_name' => 'required|string|max:50|alpha_dash',
        ]);

        $column = $request->column_name;

        // Cek kalau kolom sudah ada
        if (Schema::hasColumn('siswa', $column)) {
            return redirect()->back()->with('error', 'Kolom sudah ada!');
        }

        // Tambah kolom baru ke tabel siswa
        Schema::table('siswa', function (Blueprint $table) use ($column) {
            $table->string($column)->nullable();
        });

        return redirect()->back()->with('success', 'Kolom "' . $column . '" berhasil ditambahkan!');
    }

    public function deleteColumn($column)
    {
        // Daftar kolom default yang tidak boleh dihapus
        $protected = ['id', 'nisn', 'nama_siswa', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'kelas_id', 'agama', 'status_id', 'created_at', 'updated_at'];

        if (in_array($column, $protected)) {
            return back()->withErrors(['Kolom ini tidak boleh dihapus.']);
        }

        Schema::table('siswa', function ($table) use ($column) {
            $table->dropColumn($column);
        });

        return back()->with('success', "Kolom '$column' berhasil dihapus!");
    }

    public function updateColumn(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:50|alpha_dash',
        ]);

        $old = $request->old_name;
        $new = $request->new_name;

        // Kolom default yang tidak boleh diedit
        $protected = [
            'id',
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'kelas_id',
            'agama',
            'status_id',
            'created_at',
            'updated_at'
        ];

        if (in_array($old, $protected)) {
            return back()->withErrors(['Kolom ini tidak boleh diedit.']);
        }

        // Cek kolom lama ada
        if (!Schema::hasColumn('siswa', $old)) {
            return back()->withErrors(['Kolom tidak ditemukan.']);
        }

        // Cek nama baru sudah ada
        if (Schema::hasColumn('siswa', $new)) {
            return back()->withErrors(['Nama kolom sudah digunakan.']);
        }

        // Rename kolom
        Schema::table('siswa', function (Blueprint $table) use ($old, $new) {
            $table->renameColumn($old, $new);
        });

        return back()->with('success', "Kolom '$old' berhasil diubah menjadi '$new'");
    }

    public function perKelas()
    {
        $kelas = Kelas::withCount('siswa')->get();

        if (auth()->user()->role === 'guru') {
            return view('roles.guru.siswa.perkelas', compact('kelas'));
        }

        return view('roles.superadmin.siswa.perkelas', compact('kelas'));
    }

    public function showByKelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $siswa = Siswa::where('kelas_id', $id)
            ->with('kelas', 'status')
            ->orderBy('nama_siswa', 'asc')
            ->paginate(10);

        $columns = Schema::getColumnListing('siswa');

        if (auth()->user()->role === 'guru') {
            return view(
                'roles.guru.siswa.detail_perkelas',
                compact('kelas', 'siswa', 'columns')
            );
        }

        return view(
            'roles.superadmin.siswa.detail_perkelas',
            compact('kelas', 'siswa', 'columns')
        );
    }

    public function exportPerKelasPdf($id)
    {
        $kelas = Kelas::findOrFail($id);

        $siswa = Siswa::where('kelas_id', $id)
            ->with('status')
            ->orderBy('nama_siswa')
            ->get();

        $pdf = Pdf::loadView(
            'roles.superadmin.siswa.export_perkelas_pdf',
            compact('kelas', 'siswa')
        )->setPaper('A4', 'landscape');

        return $pdf->download('siswa_kelas_' . $kelas->nama_kelas . '.pdf');
    }

    public function exportPerKelasExcel($id)
    {
        return Excel::download(
            new SiswaPerKelasExport($id),
            'siswa_kelas.xlsx'
        );
    }


    public function bulkUpdateKelas(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::whereIn('id', $request->siswa_ids)
            ->update(['kelas_id' => $request->kelas_id]);

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
        ]);

        $count = count($request->siswa_ids);

        Siswa::whereIn('id', $request->siswa_ids)->delete();

        return back()->with('success', "$count siswa berhasil dihapus.");
    }

    public function multiEditUpdate(Request $request)
    {
        foreach ($request->siswa as $data) {
            $id = $data['id'];
            unset($data['id']);

            Siswa::where('id', $id)->update($data);
        }

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }
}
