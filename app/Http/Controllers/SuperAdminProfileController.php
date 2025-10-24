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
        return view('roles.superadmin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id()); // ✅ pastikan instance model User

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name', 'phone');

        if ($request->hasFile('profile_photo')) {
            // hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // simpan foto baru
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
