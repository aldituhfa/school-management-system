<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SuperAdmin\AccountController;
use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\Finance\SPPController;
// use App\Http\Controllers\Finance\PayrollController;
use App\Http\Controllers\Finance\LogController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\StatusSiswaController;
use App\Http\Controllers\BiayaSppController;
use App\Http\Controllers\Superadmin\MataPelajaranController;
use App\Http\Controllers\SuperAdminProfileController;
use App\Http\Controllers\PilihMapelController;

// use App\Http\Controllers\TahunAjaranController;
// use App\Http\Controllers\TingkatController;
// use App\Http\Controllers\StatusController;
use App\Models\User;

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

Route::get('/', [LandingController::class, 'index'])->name('landing');


//LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================
// DASHBOARD SETIAP ROLE
// ==========================

// SUPER ADMIN
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/roles/superadmin/dashboard', function () {
        return view('roles.superadmin.dashboard');
    })->name('roles.superadmin.dashboard');
});

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/roles/admin/dashboard', function () {
        return view('roles.admin.dashboard');
    })->name('roles.admin.dashboard');
});

// GURU
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/roles/guru/dashboard', function () {
        return view('roles.guru.dashboard');
    })->name('roles.guru.dashboard');
});

// TU
Route::middleware(['auth', 'role:tu'])->group(function () {
    Route::get('/roles/tu/dashboard', function () {
        return view('roles.tu.dashboard');
    })->name('roles.tu.dashboard');
});

// SISWA
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/roles/siswa/dashboard', function () {
        return view('roles.siswa.dashboard');
    })->name('roles.siswa.dashboard');
});

// PAYROLL
Route::middleware(['auth', 'role:payroll'])->group(function () {
    Route::get('/roles/payroll/dashboard', function () {
        return view('roles.payroll.dashboard');
    })->name('roles.payroll.dashboard');
});


Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // SUPER ADMIN > managemen akun
    Route::get('/roles/superadmin/account', [AccountController::class, 'dashboard'])->name('account.main');
    //crud akun
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

    // SUPER ADMIN > finance
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

    // SUPER ADMIN > Finance logs
    Route::get('/logs/finances', [LogController::class, 'index'])
        ->name('logs.finances')
        ->middleware('role:super_admin,admin,tu,payroll');

    // Totals & Stats untuk card + Chart.js — role selain guru
    // Route::get('/finances/totals', [FinanceController::class, 'totals'])
    //     ->name('finances.totals')
    //     ->middleware('role:super_admin,admin,tu,payroll');

    // Route::get('/finances/stats', [FinanceController::class, 'stats'])
    //     ->name('finances.stats')
    //     ->middleware('role:super_admin,admin,tu,payroll');


    // TU > SPP
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

    // TU > data SPP
    Route::get('/tu/data-spp', [App\Http\Controllers\DataSppController::class, 'index'])->name('tu.data_spp.index');
    Route::get('/tu/data-spp/{id}', [App\Http\Controllers\DataSppController::class, 'show'])->name('tu.data_spp.detail');
    // Route::post('/tu/data_spp/bayar', [app\Http\Controllers\DataSppController::class, 'bayar'])->name('tu.data_spp.bayar');
    Route::post('/tu/data_spp/cancel/{id}', [App\Http\Controllers\DataSppController::class, 'cancel'])->name('tu.data_spp.cancel');
    Route::post('/tu/data-spp/{id}/update', [App\Http\Controllers\DataSppController::class, 'update'])
        ->name('tu.data_spp.update');
    Route::post('/tu/data-spp/{id}/bayar', [App\Http\Controllers\DataSppController::class, 'bayar'])
        ->name('tu.data_spp.bayar');



        // SUPER ADMIN > pilih mata pelajaran untuk guru
     Route::prefix('roles/superadmin')->name('roles.superadmin.')->group(function () {
    Route::get('/pilihmapel', [PilihMapelController::class, 'index'])->name('pilihmapel.index');
    Route::get('/pilihmapel/{id}/edit', [PilihMapelController::class, 'edit'])->name('pilihmapel.edit');
    Route::put('/pilihmapel/{id}', [PilihMapelController::class, 'update'])->name('pilihmapel.update');
});





    // // Payroll
    // Route::get('/payrolls', [PayrollController::class, 'index'])
    //     ->name('payrolls.index')
    //     ->middleware('role:super_admin,admin,payroll');

    // Route::post('/payrolls', [PayrollController::class, 'store'])
    //     ->name('payrolls.store')
    //     ->middleware('role:super_admin,admin,payroll');

    // Route::post('/payrolls/{id}/pay', [PayrollController::class, 'pay'])
    //     ->name('payrolls.pay')
    //     ->middleware('role:super_admin,admin,payroll');

    // SUPER ADMIN > data siswa 
    Route::prefix('roles/superadmin')->group(function () {

        // CRUD siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        //CRUD KOLOM
        Route::post('/siswa/add-column', [App\Http\Controllers\SiswaController::class, 'addColumn'])->name('siswa.addColumn');
        Route::delete('/roles/superadmin/siswa/delete-column/{column}', [SiswaController::class, 'deleteColumn'])->name('siswa.deleteColumn');

        // CRUD kelas
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update'); // <– tambahkan ini
        Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // CRUD status
        Route::post('/status', [StatusSiswaController::class, 'store'])->name('status.store');
        Route::put('/status/{id}', [StatusSiswaController::class, 'update'])->name('status.update'); // <– tambahkan ini
        Route::delete('/status/{status}', [StatusSiswaController::class, 'destroy'])->name('status.destroy');
    });


    //SUPER ADMIN > biaya spp
    Route::prefix('superadmin')->name('superadmin.')->middleware(['auth'])->group(function () {

        // CRUD Biaya SPP
        Route::get('biaya-spp', [BiayaSppController::class, 'index'])->name('biayaspp.index');
        Route::post('biaya-spp/store', [BiayaSppController::class, 'store'])->name('biayaspp.store');
        Route::put('biaya-spp/{spp}', [BiayaSppController::class, 'update'])->name('biayaspp.update');
        Route::delete('biaya-spp/{spp}', [BiayaSppController::class, 'destroy'])->name('biayaspp.destroy');

        // CRUD Tahun Ajaran 
        Route::post('biaya-spp/tahun-ajaran', [BiayaSppController::class, 'storeTahunAjaran'])->name('biayaspp.tahun-ajaran.store');
        Route::delete('biaya-spp/tahun-ajaran/{tahun}', [BiayaSppController::class, 'destroyTahunAjaran'])->name('biayaspp.tahun-ajaran.destroy');

        // CRUD Tingkat
        Route::post('biaya-spp/tingkat', [BiayaSppController::class, 'storeTingkat'])->name('biayaspp.tingkat.store');
        Route::delete('biaya-spp/tingkat/{tingkat}', [BiayaSppController::class, 'destroyTingkat'])->name('biayaspp.tingkat.destroy');

        // CRUD Status
        Route::post('biaya-spp/status', [BiayaSppController::class, 'storeStatus'])->name('biayaspp.status.store');
        Route::delete('biaya-spp/status/{status}', [BiayaSppController::class, 'destroyStatus'])->name('biayaspp.status.destroy');

        Route::put('biaya-spp/tahun-ajaran/{tahun}', [BiayaSppController::class, 'updateTahunAjaran'])->name('biayaspp.tahun-ajaran.update');
        Route::put('biaya-spp/tingkat/{tingkat}', [BiayaSppController::class, 'updateTingkat'])->name('biayaspp.tingkat.update');
        Route::put('biaya-spp/status/{status}', [BiayaSppController::class, 'updateStatus'])->name('biayaspp.status.update');
    });
});

// SUPER ADMIN > mata Pelajaran
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::resource('mata_pelajaran', MataPelajaranController::class);
});



//Profile 
Route::prefix('superadmin')->middleware(['auth'])->group(function () {
    Route::get('/profile', [SuperAdminProfileController::class, 'index'])->name('superadmin.profile');
    Route::post('/profile/update', [SuperAdminProfileController::class, 'update'])->name('superadmin.profile.update');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/profile', [SuperAdminProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profile/update', [SuperAdminProfileController::class, 'update'])->name('admin.profile.update');
});

Route::prefix('guru')->middleware(['auth'])->group(function () {
    Route::get('/profile', [SuperAdminProfileController::class, 'index'])->name('guru.profile');
    Route::post('/profile/update', [SuperAdminProfileController::class, 'update'])->name('guru.profile.update');
});

Route::prefix('tu')->middleware(['auth'])->group(function () {
    Route::get('/profile', [SuperAdminProfileController::class, 'index'])->name('tu.profile');
    Route::post('/profile/update', [SuperAdminProfileController::class, 'update'])->name('tu.profile.update');
});

Route::prefix('payroll')->middleware(['auth'])->group(function () {
    Route::get('/profile', [SuperAdminProfileController::class, 'index'])->name('payroll.profile');
    Route::post('/profile/update', [SuperAdminProfileController::class, 'update'])->name('payroll.profile.update');
});






// Route::prefix('finances')->group(function () {
//     Route::get('/', [FinanceController::class, 'index'])->name('finances.index'); // halaman Blade
//     Route::get('/list', [FinanceController::class, 'list'])->name('finances.list'); // DataTables JSON
//     Route::post('/', [FinanceController::class, 'store'])->name('finances.store');
//     Route::post('/{id}', [FinanceController::class, 'update'])->name('finances.update');
//     Route::delete('/{id}', [FinanceController::class, 'destroy'])->name('finances.destroy');
// });


// Route::get('/spp', function () {
//     return view('roles.superadmin.spp.index');
// })->name('superadmin.spp.index');
