<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use App\Models\Pengajuan;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ================= LOGIN REDIRECT =================
        Event::listen(Login::class, function () {

            if (Auth::user()->role === 'dosen') {
                session(['redirect_after_login' => '/dosen']);
            } else {
                session(['redirect_after_login' => '/']);
            }

        });

        // ================= GLOBAL NAVBAR DATA =================
        View::composer('components.navbar', function ($view) {

            $jumlahPengajuan = 0;

            if (Auth::check()) {
                // Badge pengajuan = periode aktif saja (konsisten dgn halaman Pengajuan).
                $activePeriodeId = \App\Models\Periode::periodeAktif()?->id;
                $jumlahPengajuan = $activePeriodeId
                    ? Pengajuan::where('mahasiswa_id', Auth::id())
                        ->where('periode_id', $activePeriodeId)
                        ->count()
                    : 0;
            }

            $view->with('jumlahPengajuan', $jumlahPengajuan);
        });
    }
}