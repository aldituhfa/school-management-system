<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;

class MataPelajaranController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran dengan fitur pencarian & pagination
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = MataPelajaran::query();

        if (!empty($search)) {
            $query->where('nama_mata_pelajaran', 'like', "%{$search}%");
        }

        $data = $query->orderBy('nama_mata_pelajaran', 'asc')->paginate(10);
        $data->appends(['search' => $search]);

        return view('roles.superadmin.mata_pelajaran.index', compact('data'));
    }

    /**
     * Menyimpan data mata pelajaran baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|string|max:255|unique:mata_pelajaran,nama_mata_pelajaran',
        ], [
            'nama_mata_pelajaran.unique' => 'Mata pelajaran sudah ada.',
        ]);

        MataPelajaran::create([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui data mata pelajaran
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|string|max:255|unique:mata_pelajaran,nama_mata_pelajaran,' . $id,
        ], [
            'nama_mata_pelajaran.unique' => 'Mata pelajaran sudah ada.',
        ]);

        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->update([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mata pelajaran
     */
    public function destroy($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->delete();

        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
