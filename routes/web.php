<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\AccountController;
use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\Finance\SPPController;
use App\Http\Controllers\Finance\PayrollController;
use App\Http\Controllers\Finance\LogController;

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

/*
|--------------------------------------------------------------------------
| Tambahan route untuk backend keuangan (finances / spp / payroll / logs)
| - Semua route baru berada di bawah auth middleware
| - Akses dibatasi dengan middleware 'role' sesuai requirement:
|   * lihat / totals / stats: semua role kecuali guru -> super_admin,admin,tu,payroll
|   * create/update/delete finance: super_admin,admin,tu,payroll
|   * spp create: super_admin,admin,tu
|   * spp pay: super_admin,admin,tu,payroll
|   * payroll create/pay: super_admin,admin,payroll
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Finances (DataTable CRUD) — hanya role selain guru
    Route::get('/finances', [FinanceController::class, 'index'])
        ->name('finances.index')
        ->middleware('role:super_admin,admin,tu,payroll');

    Route::post('/finances', [FinanceController::class, 'store'])
        ->name('finances.store')
        ->middleware('role:super_admin,admin,tu,payroll');

    Route::put('/finances/{id}', [FinanceController::class, 'update'])
        ->name('finances.update')
        ->middleware('role:super_admin,admin,tu,payroll');

    Route::delete('/finances/{id}', [FinanceController::class, 'destroy'])
        ->name('finances.destroy')
        ->middleware('role:super_admin,admin,tu,payroll');

    // Totals & Stats untuk card + Chart.js — role selain guru
    Route::get('/finances/totals', [FinanceController::class, 'totals'])
        ->name('finances.totals')
        ->middleware('role:super_admin,admin,tu,payroll');

    Route::get('/finances/stats', [FinanceController::class, 'stats'])
        ->name('finances.stats')
        ->middleware('role:super_admin,admin,tu,payroll');

    // SPP
Route::prefix('tu')->name('tu.')->group(function () {
    Route::get('spp', [SPPController::class, 'index'])->name('spp.index');
    Route::get('spp/create', [SPPController::class, 'create'])->name('spp.create');
    Route::post('spp', [SPPController::class, 'store'])->name('spp.store');
    Route::get('spp/{spp}/edit', [SPPController::class, 'edit'])->name('spp.edit');
    Route::put('spp/{spp}', [SPPController::class, 'update'])->name('spp.update');
    Route::delete('spp/{spp}', [SPPController::class, 'destroy'])->name('spp.destroy');

    // Tombol bayar
    Route::patch('spp/{id}/pay', [SPPController::class, 'pay'])->name('spp.pay');
});


    // Payroll
    Route::get('/payrolls', [PayrollController::class, 'index'])
        ->name('payrolls.index')
        ->middleware('role:super_admin,admin,payroll');

    Route::post('/payrolls', [PayrollController::class, 'store'])
        ->name('payrolls.store')
        ->middleware('role:super_admin,admin,payroll');

    Route::post('/payrolls/{id}/pay', [PayrollController::class, 'pay'])
        ->name('payrolls.pay')
        ->middleware('role:super_admin,admin,payroll');

    // Finance logs
    Route::get('/logs/finances', [LogController::class, 'index'])
        ->name('logs.finances')
        ->middleware('role:super_admin,admin,tu,payroll');
});


Route::prefix('finances')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('finances.index'); // halaman Blade
    Route::get('/list', [FinanceController::class, 'list'])->name('finances.list'); // DataTables JSON
    Route::post('/', [FinanceController::class, 'store'])->name('finances.store');
    Route::post('/{id}', [FinanceController::class, 'update'])->name('finances.update');
    Route::delete('/{id}', [FinanceController::class, 'destroy'])->name('finances.destroy');
});