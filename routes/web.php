<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\AccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard sesuai role
Route::get('/roles/superadmin/dashboard', function () {
    return view('roles.superadmin.dashboard');
})->name('roles.superadmin.dashboard');

Route::get('/roles/admin/dashboard', function () {
    return view('roles.admin.dashboard');
})->name('roles.admin.dashboard');

Route::get('/roles/guru/dashboard', function () {
    return view('roles.guru.dashboard');
})->name('roles.guru.dashboard');

Route::get('/roles/tu/dashboard', function () {
    return view('roles.tu.dashboard');
})->name('roles.tu.dashboard');

Route::get('/roles/siswa/dashboard', function () {
    return view('roles.siswa.dashboard');
})->name('roles.siswa.dashboard');

Route::get('/roles/payroll/dashboard', function () {
    return view('roles.payroll.dashboard');
})->name('roles.payroll.dashboard');

//super admin > crud account di masing' role
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/roles/superadmin/account/{role}', [AccountController::class, 'index'])->name('account.index');
    Route::post('/roles/superadmin/account/{role}', [AccountController::class, 'store'])->name('account.store');
    Route::post('/roles/superadmin/account/update/{id}', [AccountController::class, 'update'])->name('account.update');
    Route::delete('roles/superadmin/account/delete/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
});
