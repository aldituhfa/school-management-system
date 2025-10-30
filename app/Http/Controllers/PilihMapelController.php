<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class PilihMapelController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $guru = $query->with('mataPelajaran')->get(); 

        return view('roles.superadmin.pilihmapel.index', compact('guru'));
    }

    public function edit($id)
    {
        $guru = User::findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        return view('roles.superadmin.pilihmapel.edit', compact('guru', 'mataPelajaran'));
    }

    public function update(Request $request, $id)
    {
        $guru = User::findOrFail($id);
        $guru->mataPelajaran()->sync($request->mata_pelajaran);

        return redirect()
            ->route('roles.superadmin.pilihmapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui!');
    }
}
