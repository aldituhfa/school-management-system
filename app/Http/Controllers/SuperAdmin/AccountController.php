<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index($role)
    {
        // FIX: gunakan paginate agar Blade bisa pakai ->hasPages()
        $users = User::where('role', $role)->paginate(5);

        return view("roles.superadmin.account.$role", compact('users', 'role'));
    }

    public function store(Request $request, $role)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role
        ]);

        return back()->with('success', 'Akun berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$id",
            'password' => 'nullable|min:6'
        ]);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Akun berhasil diupdate.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'Akun berhasil dihapus.');
    }

    public function dashboard()
    {
        $counts = [
            'admin'   => User::where('role', 'admin')->count(),
            'guru'    => User::where('role', 'guru')->count(),
            'payroll' => User::where('role', 'payroll')->count(),
            'siswa'   => User::where('role', 'siswa')->count(),
            'tu'      => User::where('role', 'tu')->count(),
        ];

        $total = array_sum($counts);

        $lastUpdate = User::latest('updated_at')->value('updated_at');
        $latestUsers = User::latest()->take(5)->get();

        return view('roles.superadmin.account.account', compact('counts', 'total', 'lastUpdate', 'latestUsers'));
    }

    
}
