<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PayrollSetting;
use Illuminate\Http\Request;

class DataPenggajianController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');

        $query = User::whereIn('role', ['super_admin', 'guru', 'payroll', 'tu'])
            ->with('payrollSetting');

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->get();

        return view('roles.payroll.data_penggajian.index', compact('users', 'role'));
    }


    public function edit($id)
    {
        $user = User::with('payrollSetting')->findOrFail($id);
        return view('roles.payroll.data_penggajian.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'status_penggajian' => 'required|boolean',
            'status_aktif' => 'required|boolean',
        ]);

        // ❌ RULE UTAMA: Pegawai nonaktif tidak boleh digaji
        if ($request->status_aktif == 0 && $request->status_penggajian == 1) {
            return back()->withErrors([
                'status_penggajian' => 'Pegawai nonaktif tidak boleh memiliki status penggajian aktif.'
            ])->withInput();
        }

        PayrollSetting::updateOrCreate(
            ['user_id' => $id],
            [
                'gaji_pokok' => $request->gaji_pokok,
                'status_penggajian' => $request->status_penggajian,
                'status_aktif' => $request->status_aktif,
            ]
        );

        return redirect()->route('payroll.data_penggajian.index')
            ->with('success', 'Data penggajian berhasil diperbarui');
    }
}
