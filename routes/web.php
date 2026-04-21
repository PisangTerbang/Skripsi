<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// MAHASISWA
use App\Http\Controllers\Mahasiswa\BerandaController;
use App\Http\Controllers\Mahasiswa\PengajuanController;
use App\Http\Controllers\Mahasiswa\NotifikasiController;

// DOSEN
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
| DASHBOARD REDIRECT (AUTO ROLE)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role === 'mahasiswa') {
        return redirect()->route('mahasiswa.beranda');
    }

    if ($user->role === 'dosen') {
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
    ->name('mahasiswa.') // 🔥 DIUBAH JADI LOWERCASE (STANDARD)
    ->group(function () {

        // BERANDA
        Route::get('/beranda', [BerandaController::class, 'index'])
            ->name('beranda');

        Route::get('/beranda/data', [\App\Http\Controllers\Mahasiswa\BerandaController::class, 'data'])
            ->name('beranda.data');

        // PENGAJUAN
        Route::get('/pengajuan', [PengajuanController::class, 'index'])
            ->name('pengajuan');

        Route::post('/pengajuan', [PengajuanController::class, 'store'])
            ->name('pengajuan.store');

        // RIWAYAT
        Route::get('/riwayat', [PengajuanController::class, 'riwayat'])
            ->name('riwayat');

        // NOTIFIKASI
        Route::get('/notifikasi-data', [NotifikasiController::class, 'data'])
            ->name('notifikasi.data');

        Route::post('/notifikasi-read', [NotifikasiController::class, 'readAll'])
            ->name('notifikasi.read');

        // PENGATURAN
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

        // DASHBOARD
        Route::get('/', [DosenDashboardController::class, 'index'])
            ->name('dashboard');

        // PENGAJUAN MAHASISWA
        Route::get('/pengajuan', [DosenPengajuanController::class, 'index'])
            ->name('pengajuan');

        Route::put('/pengajuan/{id}', [DosenPengajuanController::class, 'update'])
            ->name('pengajuan.update');

        // MANAJEMEN JUDUL
        Route::get('/judul', [DosenJudulController::class, 'index'])
            ->name('judul');

        Route::post('/judul', [DosenJudulController::class, 'store'])
            ->name('judul.store');

        Route::delete('/judul/{id}', [DosenJudulController::class, 'destroy'])
            ->name('judul.destroy');

    });

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';