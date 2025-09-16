<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('roles.superadmin.dashboard');
                case 'admin':
                    return redirect()->route('roles.admin.dashboard');
                case 'guru':
                    return redirect()->route('roles.guru.dashboard');
                case 'tu':
                    return redirect()->route('roles.tu.dashboard');
                case 'siswa':
                    return redirect()->route('roles.siswa.dashboard');
                case 'payroll':
                    return redirect()->route('roles.payroll.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error','Role tidak dikenali');
            }
        }

        return back()->with('error','Email atau password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('landing');
    }
}
