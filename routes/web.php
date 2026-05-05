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
use App\Http\Controllers\Dosen\DosenNotifikasiController;
use App\Http\Controllers\Dosen\DosenPengaturanController;

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

    return match ($user->role) {
        'mahasiswa' => redirect()->route('mahasiswa.beranda'),
        'dosen' => redirect()->route('dosen.dashboard'),
        'koor_lab' => redirect()->route('koor-lab.dashboard'),
        'kepala_lab' => redirect()->route('kepala-lab.dashboard'),
        'kaprodi' => redirect()->route('kaprodi.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

/*
|----------------------------------------------------------------
| PROFILE
|----------------------------------------------------------------
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
    ->name('mahasiswa.')
    ->group(function () {
        // BERANDA
        Route::get('/beranda', [BerandaController::class, 'index'])
            ->name('beranda');

        Route::get('/beranda/data', [BerandaController::class, 'data'])
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
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');

        Route::get('/notifikasi-data', [NotifikasiController::class, 'data'])
            ->name('notifikasi.data');

        Route::post('/notifikasi-read', [NotifikasiController::class, 'readAll'])
            ->name('notifikasi.read');

        Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])
            ->name('notifikasi.mark-read');

        // PENGATURAN
        Route::get('/pengaturan', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'index'])
            ->name('pengaturan');

        Route::put('/pengaturan/profile', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'updateProfile'])
            ->name('pengaturan.profile');

        Route::put('/pengaturan/password', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'updatePassword'])
            ->name('pengaturan.password');

        Route::delete('/pengaturan/avatar', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'removeAvatar'])
            ->name('pengaturan.avatar.remove');
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

        // JUDUL MANAGEMENT
        Route::get('/judul', [DosenJudulController::class, 'index'])
            ->name('judul.index');

        Route::post('/judul', [DosenJudulController::class, 'store'])
            ->name('judul.store');

        Route::put('/judul/{id}', [DosenJudulController::class, 'update'])
            ->name('judul.update');

        Route::patch('/judul/{id}/toggle', [DosenJudulController::class, 'toggleStatus'])
            ->name('judul.toggle');

        Route::delete('/judul/{id}', [DosenJudulController::class, 'destroy'])
            ->name('judul.destroy');

        // NOTIFIKASI DOSEN
        Route::get('/notifikasi', [DosenNotifikasiController::class, 'index'])
            ->name('notifikasi');

        Route::get('/notifikasi-data', [DosenNotifikasiController::class, 'data'])
            ->name('notifikasi.data');

        Route::post('/notifikasi-read', [DosenNotifikasiController::class, 'readAll'])
            ->name('notifikasi.read');

        Route::post('/notifikasi/{id}/read', [DosenNotifikasiController::class, 'markAsRead'])
            ->name('notifikasi.mark-read');

        // PENGATURAN DOSEN
        Route::get('/pengaturan', [DosenPengaturanController::class, 'index'])
            ->name('pengaturan');

        Route::put('/pengaturan/profile', [DosenPengaturanController::class, 'updateProfile'])
            ->name('pengaturan.profile');

        Route::put('/pengaturan/password', [DosenPengaturanController::class, 'updatePassword'])
            ->name('pengaturan.password');

        Route::delete('/pengaturan/avatar', [DosenPengaturanController::class, 'removeAvatar'])
            ->name('pengaturan.avatar.remove');
    });


/*
|--------------------------------------------------------------------------
| KOORDINATOR LAB AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:koor_lab'])
    ->prefix('koor-lab')
    ->name('koor-lab.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\KoorLab\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/judul', [\App\Http\Controllers\KoorLab\JudulController::class, 'index'])
            ->name('judul');

        Route::put('/judul/{id}/kelompokan', [\App\Http\Controllers\KoorLab\JudulController::class, 'kelompokkan'])
            ->name('judul.kelompokkan');
    });

/*
|--------------------------------------------------------------------------
| KEPALA LAB AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kepala_lab'])
    ->prefix('kepala-lab')
    ->name('kepala-lab.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\KepalaLab\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/validasi', [\App\Http\Controllers\KepalaLab\ValidasiController::class, 'index'])
            ->name('validasi');

        Route::put('/validasi/{id}/approve', [\App\Http\Controllers\KepalaLab\ValidasiController::class, 'approve'])
            ->name('validasi.approve');

        Route::put('/validasi/{id}/reject', [\App\Http\Controllers\KepalaLab\ValidasiController::class, 'reject'])
            ->name('validasi.reject');
    });

/*
|--------------------------------------------------------------------------
| KAPRODI AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Kaprodi\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/monitoring', [\App\Http\Controllers\Kaprodi\MonitoringController::class, 'index'])
            ->name('monitoring');

        Route::put('/monitoring/{id}/approve', [\App\Http\Controllers\Kaprodi\MonitoringController::class, 'approve'])
            ->name('monitoring.approve');

        Route::put('/monitoring/{id}/reject', [\App\Http\Controllers\Kaprodi\MonitoringController::class, 'reject'])
            ->name('monitoring.reject');
    });


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
