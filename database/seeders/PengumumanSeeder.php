<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        // Pembersihan tabel terpusat di DatabaseSeeder (TRUNCATE ... CASCADE).
        $koor = DB::table('users')->where('role', 'koordinator_ta')->value('id');

        // Hanya periode ARSIP yang punya pengumuman TERKIRIM (hasil resmi dibuka).
        // Periode AKTIF sengaja belum ada pengumuman → hasil dirahasiakan sampai diumumkan.
        $arsip = DB::table('periode')->whereRaw('is_active = false')
            ->orderBy('tanggal_buka')->get()->all();

        $rows = [];
        foreach ($arsip as $periode) {
            $tutup = Carbon::parse($periode->tanggal_tutup);
            $dikirim = $tutup->copy()->subDays(3)->setTime(10, 0);

            $rows[] = [
                'periode_id' => $periode->id,
                'dibuat_oleh' => $koor,
                'judul' => 'Pengumuman Hasil Penetapan Judul TA — ' . $periode->nama,
                'isi' => "Diberitahukan kepada seluruh mahasiswa bahwa hasil penetapan judul Tugas Akhir "
                    . "untuk {$periode->nama} telah diumumkan. Silakan cek status pengajuan Anda pada "
                    . "halaman Riwayat Pengajuan. Bagi yang telah disetujui, harap segera menghubungi "
                    . "dosen pembimbing untuk memulai proses bimbingan.",
                'dikirim_at' => $dikirim->format('Y-m-d H:i:s'),
                'tampilkan_hasil' => DB::raw('true'),
                'created_at' => $dikirim->format('Y-m-d H:i:s'),
                'updated_at' => $dikirim->format('Y-m-d H:i:s'),
            ];
        }

        if (!empty($rows)) {
            DB::table('pengumuman')->insert($rows);
        }
    }
}
