<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class SuperAdminProfileController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Jika role guru, ambil semua mata pelajaran yang dia ajar
    $mataPelajaran = null;
    if ($user->role === 'guru') {
        $mataPelajaran = $user->mataPelajaran; // dari relasi di model
    }

    // Tentukan tampilan sesuai role
    $viewPath = match ($user->role) {
        'super_admin' => 'roles.superadmin.profile.index',
        'admin'       => 'roles.admin.profile.index',
        'guru'        => 'roles.guru.profile.index',
        'tu'          => 'roles.tu.profile.index',
        'payroll'     => 'roles.payroll.profile.index',
        default       => abort(403, 'Role tidak dikenali'),
    };

    return view($viewPath, compact('user', 'mataPelajaran'));
}
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:9120',
        ]);

        $data = $request->only('name', 'phone');

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
