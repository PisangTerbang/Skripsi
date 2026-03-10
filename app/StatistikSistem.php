<?php

namespace App;

use App\Models\Pengajuan;

class StatistikSistem
{
    public static function totalPengajuan()
    {
        return Pengajuan::count();
    }

    public static function totalDisetujui()
    {
        return Pengajuan::where('status', 'disetujui')->count();
    }

    public static function totalDitolak()
    {
        return Pengajuan::where('status', 'ditolak')->count();
    }

    public static function totalPeminat()
    {
        return Pengajuan::where('jenis', 'pilih')->count();
    }

    public static function totalPengajuanPeriode($periodeId)
    {
        return \App\Models\Pengajuan::where('periode_id', $periodeId)->count();
    }

    public static function disetujuiPeriode($periodeId)
    {
        return \App\Models\Pengajuan::where('periode_id', $periodeId)
            ->where('status', 'disetujui')
            ->count();
    }

    public static function ditolakPeriode($periodeId)
    {
        return \App\Models\Pengajuan::where('periode_id', $periodeId)
            ->where('status', 'ditolak')
            ->count();
    }

    public static function totalPeminatPeriode($periodeId)
    {
        return \App\Models\Pengajuan::where('periode_id', $periodeId)
            ->where('jenis', 'pilih')
            ->count();
    }

    public static function totalDisetujuiPeriode($periodeId)
    {
        return \App\Models\Pengajuan::where('periode_id', $periodeId)
            ->where('status', 'disetujui')
            ->count();
    }

    public static function totalDitolakPeriode($periodeId)
    {
        return \App\Models\Pengajuan::where('periode_id', $periodeId)
            ->where('status', 'ditolak')
            ->count();
    }

    public static function peminatLabPeriode($periodeId)
    {
        return \App\Models\Pengajuan::selectRaw('judul.laboratorium_id, COUNT(*) as total')
            ->join('judul', 'pengajuan.judul_id', '=', 'judul.id')
            ->where('pengajuan.periode_id', $periodeId)
            ->where('pengajuan.jenis', 'pilih')
            ->groupBy('judul.laboratorium_id')
            ->get();
    }
}
