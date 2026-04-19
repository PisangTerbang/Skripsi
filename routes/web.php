<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Mahasiswa\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\NotifikasiController;
use App\Http\Controllers\Mahasiswa\PengajuanController;

use App\Http\Controllers\Dosen\DosenDashboardController;
use App\Http\Controllers\Dosen\DosenPengajuanController;
use App\Http\Controllers\Dosen\DosenJudulController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    if (auth()->user()->role == 'mahasiswa') {
        return redirect()->route('Mahasiswa.beranda');
    }

    if (auth()->user()->role == 'dosen') {
        return redirect()->route('dosen.dashboard');
    }

    return redirect()->route('login');

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| MAHASISWA AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('Mahasiswa.')
    ->group(function () {

        Route::get('/beranda', [MahasiswaDashboardController::class, 'index'])
            ->name('beranda');

        Route::get('/pengajuan', [PengajuanController::class, 'index'])
            ->name('pengajuan');

        /* ROUTE UNTUK MENYIMPAN PENGAJUAN */
        Route::post('/pengajuan', [PengajuanController::class, 'store'])
            ->name('pengajuan.store');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');

        Route::view('/pengaturan', 'Mahasiswa.pengaturan')
            ->name('pengaturan');

    });


/*
|--------------------------------------------------------------------------
| DOSEN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::get('/', [DosenDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/pengajuan', [DosenPengajuanController::class, 'index'])
            ->name('pengajuan');
        Route::put('/pengajuan/{id}', [DosenPengajuanController::class, 'update'])
            ->name('pengajuan.update');

        Route::get('/judul', [DosenJudulController::class, 'index'])
            ->name('judul');

        Route::post('/judul', [DosenJudulController::class, 'store'])
            ->name('judul.store');

        Route::delete('/judul/{id}', [DosenJudulController::class, 'destroy'])
            ->name('judul.destroy');

    });

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';