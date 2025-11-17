<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SuperAdminSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('roles.superadmin.setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first() ?? new Setting();

        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:5120',
            'logo_name' => 'nullable|string|max:255',
        ]);

        // Hapus logo lama jika diganti
        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            $path = $request->file('logo')->store('logo', 'public');
            $validated['logo'] = $path;
        }

        // Jika tombol hapus ditekan
        if ($request->has('delete_logo') && $request->delete_logo == '1') {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = null;
        }

        $setting->fill($validated);
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
