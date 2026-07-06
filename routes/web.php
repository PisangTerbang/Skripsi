<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanDetailController;

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

// KA LAB
use App\Http\Controllers\KaLab\DashboardController as KaLabDashboardController;
use App\Http\Controllers\KaLab\JudulController as KaLabJudulController;
use App\Http\Controllers\KaLab\ValidasiController as KaLabValidasiController;
use App\Http\Controllers\KaLab\PengajuanController as KaLabPengajuanController;

// PRODI
use App\Http\Controllers\Prodi\DashboardController as ProdiDashboardController;
use App\Http\Controllers\Prodi\MonitoringController as ProdiMonitoringController;
use App\Http\Controllers\Prodi\PengajuanController as ProdiPengajuanController;

// KOORDINATOR TA
use App\Http\Controllers\KoorTA\DashboardController as KoorTADashboardController;
use App\Http\Controllers\KoorTA\UserController as KoorTAUserController;
use App\Http\Controllers\KoorTA\PeriodeController as KoorTAPeriodeController;
use App\Http\Controllers\KoorTA\PengumumanController as KoorTAPengumumanController;
use App\Http\Controllers\KoorTA\MonitoringController as KoorTAMonitoringController;

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
        'ka_lab' => redirect()->route('ka-lab.dashboard'),
        'prodi' => redirect()->route('prodi.dashboard'),
        'koordinator_ta' => redirect()->route('koor-ta.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| DETAIL PENGUMUMAN (semua role yang login)
|--------------------------------------------------------------------------
*/

Route::get('/pengumuman/{id}', [PengumumanDetailController::class, 'show'])
    ->middleware('auth')
    ->name('pengumuman.detail');

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
        Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
        Route::get('/beranda/data', [BerandaController::class, 'data'])->name('beranda.data');

        // PENGAJUAN
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

        // RIWAYAT
        Route::get('/riwayat', [PengajuanController::class, 'riwayat'])->name('riwayat');

        // NOTIFIKASI
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
        Route::get('/notifikasi-data', [NotifikasiController::class, 'data'])->name('notifikasi.data');
        Route::post('/notifikasi-read', [NotifikasiController::class, 'readAll'])->name('notifikasi.read');
        Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');

        // PENGATURAN
        Route::get('/pengaturan', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'index'])->name('pengaturan');
        Route::put('/pengaturan/profile', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
        Route::put('/pengaturan/password', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
        Route::delete('/pengaturan/avatar', [\App\Http\Controllers\Mahasiswa\PengaturanController::class, 'removeAvatar'])->name('pengaturan.avatar.remove');

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
        Route::get('/', [DosenDashboardController::class, 'index'])->name('dashboard');

        // PENGAJUAN MAHASISWA (view-only untuk pilih; mandiri butuh konfirmasi dosen)
        Route::get('/pengajuan', [DosenPengajuanController::class, 'index'])->name('pengajuan');
        // Usulan mandiri: dosen konfirmasi (pilih lab → teruskan ke Ka Lab) atau tolak.
        Route::post('/pengajuan/{id}/konfirmasi-mandiri', [DosenPengajuanController::class, 'konfirmasiMandiri'])->name('pengajuan.konfirmasi-mandiri');
        Route::post('/pengajuan/{id}/tolak-mandiri', [DosenPengajuanController::class, 'tolakMandiri'])->name('pengajuan.tolak-mandiri');

        // JUDUL MANAGEMENT
        Route::prefix('judul')->name('judul.')->group(function () {
            Route::get('/', [DosenJudulController::class, 'index'])->name('index');
            Route::post('/', [DosenJudulController::class, 'store'])->name('store');
            Route::post('/{id}/ajukan', [DosenJudulController::class, 'ajukan'])->name('ajukan');
            Route::post('/{id}/tarik', [DosenJudulController::class, 'tarik'])->name('tarik');
            Route::put('/{id}', [DosenJudulController::class, 'update'])->name('update');
            Route::delete('/{id}', [DosenJudulController::class, 'destroy'])->name('destroy');
        });

        // NOTIFIKASI
        Route::get('/notifikasi', [DosenNotifikasiController::class, 'index'])->name('notifikasi');
        Route::get('/notifikasi-data', [DosenNotifikasiController::class, 'data'])->name('notifikasi.data');
        Route::post('/notifikasi-read', [DosenNotifikasiController::class, 'readAll'])->name('notifikasi.read');
        Route::post('/notifikasi/{id}/read', [DosenNotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');

        // PENGATURAN
        Route::get('/pengaturan', [DosenPengaturanController::class, 'index'])->name('pengaturan');
        Route::put('/pengaturan/profile', [DosenPengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
        Route::put('/pengaturan/password', [DosenPengaturanController::class, 'updatePassword'])->name('pengaturan.password');
        Route::delete('/pengaturan/avatar', [DosenPengaturanController::class, 'removeAvatar'])->name('pengaturan.avatar.remove');

    });

/*
|--------------------------------------------------------------------------
| KEPALA LAB AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:ka_lab'])
    ->prefix('ka-lab')
    ->name('ka-lab.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [KaLabDashboardController::class, 'index'])->name('dashboard');

        // MONITORING JUDUL
        Route::get('/judul', [KaLabJudulController::class, 'index'])->name('judul.index');
        Route::get('/judul/{id}', [KaLabJudulController::class, 'show'])->name('judul.show');

        // VALIDASI JUDUL
        Route::prefix('validasi')->name('validasi.')->group(function () {
            Route::get('/', [KaLabValidasiController::class, 'index'])->name('index');
            Route::post('/{id}/approve', [KaLabValidasiController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [KaLabValidasiController::class, 'reject'])->name('reject');
        });

        // PENGAJUAN MAHASISWA
        Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
            Route::get('/', [KaLabPengajuanController::class, 'index'])->name('index');
            Route::post('/{id}/approve', [KaLabPengajuanController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [KaLabPengajuanController::class, 'reject'])->name('reject');
            Route::get('/{id}', [KaLabPengajuanController::class, 'show'])->name('show');
        });

        // EXPORT LAPORAN
        Route::get('/export', [\App\Http\Controllers\KaLab\ExportController::class, 'index'])->name('export.index');
        Route::post('/export/excel', [\App\Http\Controllers\KaLab\ExportController::class, 'exportExcel'])->name('export.excel');
        Route::post('/export/pdf', [\App\Http\Controllers\KaLab\ExportController::class, 'exportPdf'])->name('export.pdf');

        // NOTIFIKASI — tambah di dalam group ka-lab
        Route::get('/notifikasi', [\App\Http\Controllers\KaLab\NotifikasiController::class, 'index'])->name('notifikasi');
        Route::get('/notifikasi-data', [\App\Http\Controllers\KaLab\NotifikasiController::class, 'data'])->name('notifikasi.data');
        Route::post('/notifikasi-read', [\App\Http\Controllers\KaLab\NotifikasiController::class, 'readAll'])->name('notifikasi.read');
        Route::post('/notifikasi/{id}/read', [\App\Http\Controllers\KaLab\NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');

        //pengaturan
        Route::get('/pengaturan', [\App\Http\Controllers\KaLab\PengaturanController::class, 'index'])->name('pengaturan');
        Route::put('/pengaturan/profile', [\App\Http\Controllers\KaLab\PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
        Route::put('/pengaturan/password', [\App\Http\Controllers\KaLab\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
        Route::delete('/pengaturan/avatar', [\App\Http\Controllers\KaLab\PengaturanController::class, 'removeAvatar'])->name('pengaturan.avatar.remove');



    });

/*
|--------------------------------------------------------------------------
| PRODI AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:prodi'])
    ->prefix('prodi')
    ->name('prodi.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [ProdiDashboardController::class, 'index'])->name('dashboard');

        // MONITORING
        Route::get('/monitoring', [ProdiMonitoringController::class, 'index'])->name('monitoring');

        // REVIEW PENGAJUAN
        Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
            Route::get('/', [ProdiPengajuanController::class, 'index'])->name('index');
            Route::get('/riwayat', [ProdiPengajuanController::class, 'riwayat'])->name('riwayat');
            Route::get('/{id}', [ProdiPengajuanController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [ProdiPengajuanController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [ProdiPengajuanController::class, 'reject'])->name('reject');
        });

        // NOTIFIKASI — tambah di dalam group prodi
        Route::get('/notifikasi', [\App\Http\Controllers\Prodi\NotifikasiController::class, 'index'])->name('notifikasi');
        Route::get('/notifikasi-data', [\App\Http\Controllers\Prodi\NotifikasiController::class, 'data'])->name('notifikasi.data');
        Route::post('/notifikasi-read', [\App\Http\Controllers\Prodi\NotifikasiController::class, 'readAll'])->name('notifikasi.read');
        Route::post('/notifikasi/{id}/read', [\App\Http\Controllers\Prodi\NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');


        //pengaturan
        Route::get('/pengaturan', [\App\Http\Controllers\Prodi\PengaturanController::class, 'index'])->name('pengaturan');
        Route::put('/pengaturan/profile', [\App\Http\Controllers\Prodi\PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
        Route::put('/pengaturan/password', [\App\Http\Controllers\Prodi\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
        Route::delete('/pengaturan/avatar', [\App\Http\Controllers\Prodi\PengaturanController::class, 'removeAvatar'])->name('pengaturan.avatar.remove');


    });

/*
|--------------------------------------------------------------------------
| KOORDINATOR TA AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:koordinator_ta'])
    ->prefix('koor-ta')
    ->name('koor-ta.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [KoorTADashboardController::class, 'index'])
            ->name('dashboard');

        // USER MANAGEMENT
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [KoorTAUserController::class, 'index'])->name('index');
            Route::post('/{user}/reset-password', [KoorTAUserController::class, 'resetPassword'])->name('reset-password');
            Route::get('/{user}/edit', [KoorTAUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [KoorTAUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [KoorTAUserController::class, 'destroy'])->name('destroy');
        });

        // PERIODE
        Route::prefix('periode')->name('periode.')->group(function () {
            Route::get('/', [KoorTAPeriodeController::class, 'index'])->name('index');
            Route::get('/create', [KoorTAPeriodeController::class, 'create'])->name('create');
            Route::post('/', [KoorTAPeriodeController::class, 'store'])->name('store');
            Route::get('/{periode}/edit', [KoorTAPeriodeController::class, 'edit'])->name('edit');
            Route::put('/{periode}', [KoorTAPeriodeController::class, 'update'])->name('update');
            Route::delete('/{periode}', [KoorTAPeriodeController::class, 'destroy'])->name('destroy');
            Route::post('/{periode}/toggle-active', [KoorTAPeriodeController::class, 'toggleActive'])->name('toggle-active');
            Route::post('/sinkron-kunci', [KoorTAPeriodeController::class, 'sinkronKunci'])->name('sinkron-kunci');
        });

        // PENGUMUMAN
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [KoorTAPengumumanController::class, 'index'])->name('index');
            Route::get('/create', [KoorTAPengumumanController::class, 'create'])->name('create');
            Route::post('/', [KoorTAPengumumanController::class, 'store'])->name('store');
            Route::post('/{pengumuman}/broadcast', [KoorTAPengumumanController::class, 'broadcast'])->name('broadcast');
            Route::delete('/{pengumuman}', [KoorTAPengumumanController::class, 'destroy'])->name('destroy');
            Route::get('/{pengumuman}', [KoorTAPengumumanController::class, 'show'])->name('show');
        });

        // MONITORING
        Route::prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', [KoorTAMonitoringController::class, 'index'])->name('index');
            Route::get('/pengajuan', [KoorTAMonitoringController::class, 'pengajuan'])->name('pengajuan');
            Route::get('/judul', [KoorTAMonitoringController::class, 'judul'])->name('judul');
        });

        // NOTIFIKASI 
        Route::get('/notifikasi', [\App\Http\Controllers\KoorTA\NotifikasiController::class, 'index'])->name('notifikasi');
        Route::get('/notifikasi-data', [\App\Http\Controllers\KoorTA\NotifikasiController::class, 'data'])->name('notifikasi.data');
        Route::post('/notifikasi-read', [\App\Http\Controllers\KoorTA\NotifikasiController::class, 'readAll'])->name('notifikasi.read');
        Route::post('/notifikasi/{id}/read', [\App\Http\Controllers\KoorTA\NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');


        //penagturan
        Route::get('/pengaturan', [\App\Http\Controllers\KoorTA\PengaturanController::class, 'index'])->name('pengaturan');
        Route::put('/pengaturan/profile', [\App\Http\Controllers\KoorTA\PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
        Route::put('/pengaturan/password', [\App\Http\Controllers\KoorTA\PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
        Route::delete('/pengaturan/avatar', [\App\Http\Controllers\KoorTA\PengaturanController::class, 'removeAvatar'])->name('pengaturan.avatar.remove');

        // EXPORT
        Route::get('/export', [\App\Http\Controllers\KoorTA\ExportController::class, 'index'])->name('export.index');
        Route::post('/export/excel', [\App\Http\Controllers\KoorTA\ExportController::class, 'exportExcel'])->name('export.excel');
        Route::post('/export/pdf', [\App\Http\Controllers\KoorTA\ExportController::class, 'exportPdf'])->name('export.pdf');


    });

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
