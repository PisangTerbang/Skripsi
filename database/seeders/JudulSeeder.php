<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JudulSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('judul')->truncate();

        $sriMulyati = DB::table('users')->where('email', 'srimulyati@dosen.com')->value('id');
        $fayruz = DB::table('users')->where('email', 'fayruz@dosen.com')->value('id');
        $erika = DB::table('users')->where('email', 'erika@dosen.com')->value('id');
        $yudi = DB::table('users')->where('email', 'yudi@dosen.com')->value('id');
        $kaLab = DB::table('users')->where('role', 'ka_lab')->value('id');

        $template = [
            'aktif' => DB::raw('true'),   // ✅ PostgreSQL boolean
            'is_available' => DB::raw('true'),
            'is_locked' => DB::raw('false'),
            'status' => 'available',
            'status_judul' => 'ditawarkan',
            'kuota_maksimal' => null,
            'catatan_kalab' => null,
            'catatan_penting' => null,
            'relevant_skills' => null,
            'tanggal_kalab' => now(),
            'reviewed_by_kalab' => $kaLab,
            'reviewed_at_kalab' => now(),
            'submitted_to_kalab_at' => now(),
            'submitted_to_kalab_by' => $kaLab,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $judul = [

            // ============ SIRKEL (7 judul) ============
            [
                'kode' => 'SIRKEL-6',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Sistem Informasi Manajemen Usaha Kos',
                'deskripsi' => 'Pengembangan sistem informasi untuk manajemen usaha kos berbasis web',
                'relevant_skills' => 'Web Based',
            ],
            [
                'kode' => 'SIRKEL-26',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Sistem Otomatisasi Penawaran Judul TA',
                'deskripsi' => 'Pengembangan sistem otomatisasi untuk penawaran judul tugas akhir',
                'relevant_skills' => 'Web Based',
            ],
            [
                'kode' => 'SIRKEL-70',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Pengembangan Sistem Informasi Manajemen Pelangan Waralaba',
                'deskripsi' => 'Topik terkait manajemen Franchisee Waralaba',
                'relevant_skills' => 'Web Programming',
                'catatan_penting' => 'Diskusi melalui Chat Email, bila di Jogja sangat disarankan diskusi di kampus',
            ],
            [
                'kode' => 'SIRKEL-23',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Sistem Informasi Manajemen Usaha Kos v2',
                'deskripsi' => 'Pengembangan sistem informasi manajemen usaha kos berbasis web',
                'relevant_skills' => 'Web Based',
            ],
            [
                'kode' => 'SIRKEL-49',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Aplikasi Mobile - Sahabat Nabi',
                'deskripsi' => 'Kisah Inspiratif: Profil sahabat Nabi dengan animasi sederhana dan permainan nilai',
                'relevant_skills' => 'Mobile Programming, Video Editing, Graphics Designer',
            ],
            [
                'kode' => 'SIRKEL-79',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Pengembangan Sistem Informasi Manajemen TK Sultan Agung YBW',
                'deskripsi' => 'Menganalisis kebutuhan, mendesain dan mengimplementasikan sistem informasi berbasis web untuk mengelola proses bisnis di TK Sultan Agung YBW',
                'relevant_skills' => 'Pemrograman web, Komunikasi efektif untuk menggali kebutuhan pengguna',
            ],
            [
                'kode' => 'SIRKEL-61',
                'laboratorium_id' => 1,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Implementasi Aplikasi Mobile Monitoring Kegiatan Keagamaan Madrasah Ibtidaiyah Nurohmah Bina Insani Berbasis Android',
                'deskripsi' => 'Pembuatan ulang dari sistem yang sudah ada yang berbasis web ke aplikasi mobile',
                'relevant_skills' => 'Mobile Application, Android, Kotlin',
                'catatan_penting' => 'Sudah menguasai bahasa pemrograman Kotlin',
            ],

            // ============ ITSC (7 judul) ============
            [
                'kode' => 'ITSC-67',
                'laboratorium_id' => 2,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Integrasi Clustering dan Content-Based Filtering dalam Aplikasi Pemantauan Konsumsi Gula Berbasis Android',
                'deskripsi' => 'Data Survei telah dilakukan',
                'relevant_skills' => 'Android, Machine Learning',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],
            [
                'kode' => 'ITSC-73',
                'laboratorium_id' => 2,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'SPK untuk Menentukan Lokasi Usaha (MADM)',
                'deskripsi' => 'Sistem Pendukung Keputusan untuk menentukan lokasi usaha menggunakan metode MADM',
                'relevant_skills' => 'SPK',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],
            [
                'kode' => 'ITSC-74',
                'laboratorium_id' => 2,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Integrasi Artificial Intelligence pada Video Digital Interaktif untuk Edukasi Franchise Berbasis Digital Storytelling',
                'deskripsi' => 'Pengembangan video digital interaktif dengan AI untuk edukasi franchise',
                'relevant_skills' => 'SPK, AI',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],
            [
                'kode' => 'ITSC-75',
                'laboratorium_id' => 2,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Perancangan Sistem Pendukung Keputusan Penentuan Lokasi Waralaba Menggunakan Spatial Analysis',
                'deskripsi' => 'SPK penentuan lokasi waralaba dengan analisis spasial',
                'relevant_skills' => 'SPK, Spatial Analysis',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],
            [
                'kode' => 'ITSC-95',
                'laboratorium_id' => 2,
                'dosen_id' => $erika,
                'nama_judul' => 'Sistem Pendukung Keputusan Deteksi Risiko Malnutrisi pada Lansia dengan Metode SAW',
                'deskripsi' => 'Data: berat badan, asupan makanan, kondisi kesehatan. Metode DS akan menyesuaikan dengan data kasus',
                'relevant_skills' => 'Web language, data analysis',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],
            [
                'kode' => 'ITSC-96',
                'laboratorium_id' => 2,
                'dosen_id' => $erika,
                'nama_judul' => 'Pengembangan Sistem Pakar untuk Deteksi Risiko Jatuh pada Lansia',
                'deskripsi' => 'Data: keseimbangan, kekuatan otot, riwayat jatuh. Metode DS akan menyesuaikan',
                'relevant_skills' => 'Web language, data analysis',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],
            [
                'kode' => 'ITSC-97',
                'laboratorium_id' => 2,
                'dosen_id' => $erika,
                'nama_judul' => 'Sistem Pendukung Keputusan untuk Deteksi Dini Preeklampsia',
                'deskripsi' => 'Data: tekanan darah, riwayat kehamilan, usia ibu, kadar Hb, proteinuria, riwayat hipertensi',
                'relevant_skills' => 'Web language, data analysis',
                'catatan_penting' => 'Wajib menghubungi dosen dan diskusi dulu',
            ],

            // ============ MVK (6 judul) ============
            [
                'kode' => 'MVK-3',
                'laboratorium_id' => 3,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Pengembangan Aplikasi Edukasi Interaktif untuk Mempelajari Bahasa Arab dan Kosakata Agama Islam',
                'deskripsi' => 'Development of an Interactive Educational Application for Learning Arabic Language and Islamic Vocabulary',
            ],
            [
                'kode' => 'MVK-7',
                'laboratorium_id' => 3,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Pengembangan Game Sekuel Edukasi Diabetes',
                'deskripsi' => 'Development of an Educational Diabetes Sequel Game',
            ],
            [
                'kode' => 'MVK-26',
                'laboratorium_id' => 3,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Gim Edukasi Pemilahan Sampah',
                'deskripsi' => 'Pengembangan game edukasi untuk pemilahan sampah',
            ],
            [
                'kode' => 'MVK-27',
                'laboratorium_id' => 3,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Gim Edukasi Matematika: Pecahan dan Desimal',
                'deskripsi' => 'Pengembangan game edukasi matematika fokus pada pecahan dan desimal',
            ],
            [
                'kode' => 'MVK-28',
                'laboratorium_id' => 3,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Gim Edukasi Bahasa Inggris: Grammar',
                'deskripsi' => 'Pengembangan game edukasi bahasa Inggris fokus pada grammar',
            ],
            [
                'kode' => 'MVK-29',
                'laboratorium_id' => 3,
                'dosen_id' => $sriMulyati,
                'nama_judul' => 'Gim Edukasi Sejarah',
                'deskripsi' => 'Pengembangan game edukasi sejarah',
            ],

            // ============ SISTEM SIBER (5 judul) ============
            [
                'kode' => 'SIBER-1',
                'laboratorium_id' => 4,
                'dosen_id' => $fayruz,
                'nama_judul' => 'Pemanfaatan Generative Adversarial Network (GAN) untuk Menghasilkan Sampel Serangan Jaringan',
                'deskripsi' => 'Dataset pada sistem deteksi intrusi biasanya memiliki jumlah sampel serangan yang lebih rendah. GAN adalah salah satu cara untuk mensintesis data sampel baru.',
                'relevant_skills' => 'Machine Learning, Python',
                'catatan_penting' => 'Weekly meeting via Zoom only. There will be a second supervisor for offline supervision',
            ],
            [
                'kode' => 'SIBER-6',
                'laboratorium_id' => 4,
                'dosen_id' => $erika,
                'nama_judul' => 'Sistem Generate Dokumen 5W1H untuk Proses Investigasi Forensika Digital',
                'deskripsi' => 'Implementasi framework investigasi forensika digital data breach ke dalam web aplikasi yang mampu memberikan output pemetaan 5W1H',
                'relevant_skills' => 'Pengembangan aplikasi web, forensika digital, teknik visualisasi, teknik ekstraksi informasi',
            ],
            [
                'kode' => 'SIBER-9',
                'laboratorium_id' => 4,
                'dosen_id' => $yudi,
                'nama_judul' => 'Pengembangan Metode Steganografi pada Citra Digital untuk Komunikasi yang Aman',
                'deskripsi' => 'Penelitian ini berfokus pada pengembangan metode steganografi yang memanfaatkan teknik tertentu untuk meningkatkan keamanan data',
                'relevant_skills' => 'Bahasa Pemrograman apapun',
            ],
            [
                'kode' => 'SIBER-10',
                'laboratorium_id' => 4,
                'dosen_id' => $yudi,
                'nama_judul' => 'OSINT Framework untuk Identifikasi dan Analisis Kebocoran Data Organisasi',
                'deskripsi' => 'Pengembangan kerangka kerja OSINT untuk mengidentifikasi dan menganalisis kebocoran data dari organisasi',
                'relevant_skills' => 'Aplikasi OSINT, Maltego',
            ],
            [
                'kode' => 'SIBER-21',
                'laboratorium_id' => 4,
                'dosen_id' => $erika,
                'nama_judul' => 'Pengembangan Aplikasi Deteksi Kemiripan Suara dengan Menggunakan Library Praat',
                'deskripsi' => 'Pengembangan aplikasi deteksi kemiripan suara menggunakan library Praat dan Python',
                'relevant_skills' => 'Bahasa pemrograman pengembangan aplikasi web, pemrograman Praat Python',
                'catatan_penting' => 'https://parselmouth.readthedocs.io/en/stable/',
            ],
        ];

        $rows = array_map(fn($item) => array_merge($template, $item), $judul);

        DB::table('judul')->insert($rows);
    }
}
