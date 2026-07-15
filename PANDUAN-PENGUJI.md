# Panduan Pengguna — Sistem Otomatisasi Penawaran & Penetapan Judul TA

Sistem Otomatisasi Penawaran & Penetapan Judul Tugas Akhir adalah aplikasi web yang mendigitalkan proses pengajuan judul TA dari awal hingga pengumuman, dengan melibatkan lima peran secara berjenjang: **Dosen, Kepala Laboratorium, Mahasiswa, Program Studi,** dan **Koordinator TA**. Seluruh proses berlangsung dalam satu periode aktif dan hasilnya dirahasiakan hingga diumumkan, agar penetapan judul lebih cepat, adil, dan jelas statusnya.

Pada sistem ini, **setiap laboratorium memiliki Kepala Lab sendiri**, review pengajuan berjalan **berjenjang mengikuti prioritas judul mahasiswa**, dan **usulan mandiri dikonfirmasi dosen pembimbing terlebih dahulu**. Pada pengujian ini, yang dinilai terutama adalah kemudahan penggunaan dan kejelasan alur pada tiap peran.

---

## 1. Alur Kerja Sistem

Diagram berikut menggambarkan alur judul TA dari dosen hingga hasil resmi diumumkan kepada mahasiswa:

```
                          ┌─── MULAI ───┐
                                 │
1 · DOSEN            Membuat judul lalu mengajukan ke Kepala Lab
                    laboratoriumnya untuk divalidasi
                                 │
2 · KEPALA LAB      Validasi judul (setujui / tolak) — hanya judul
   (lab terkait)     lab-nya. Disetujui → judul "ditawarkan"
                                 │
3 · MAHASISWA       Lihat katalog & ajukan, dua cara:
                     • Pilih 3 judul (prioritas 1–3) + alasan
                     • Usulan mandiri (judul sendiri + pilih dosen)
                                 │
                     ┌───────────┴──────────────┐
              (pilih judul)               (usulan mandiri)
                     │                            │
                     │                   3b · DOSEN — konfirmasi
                     │                   & tentukan laboratorium
                     │                            │
                     └───────────┬────────────────┘
                                 ▼
4 · KEPALA LAB      Review pengajuan BERJENJANG (per lab, sesuai
   (berjenjang)      prioritas aktif):
                     • Setujui → lanjut ke Prodi
                     • Tolak   → diteruskan ke Ka Lab lab prioritas
                                 berikutnya (habis semua → ditolak final)
                                 │
5 · PRODI (KAPRODI) Keputusan final: menetapkan judul + dosen
                     pembimbing (atau tolak)
                                 │
6 · KOORDINATOR TA  Mengirim pengumuman hasil (broadcast)
                                 │
7 · MAHASISWA       Melihat hasil & judul yang ditetapkan pada
                     menu Riwayat
                                 │
                          └── SELESAI ──┘
```

**Prinsip penting:** Seluruh aktivitas terikat pada **satu periode aktif**. Saat Koordinator TA mengganti periode, data periode lama diarsipkan dan pengajuan yang masih menggantung otomatis difinalkan.

---

## 2. Daftar Akun Login (semua kata sandi: `password`)

### A. Mahasiswa

| Nama | NIM | Email (login) | Kondisi di periode aktif |
|---|---|---|---|
| Andi Pratama | 22523001 | mhs1@mail.com | Sudah mengajukan — menunggu review Ka Lab (SIRKEL) |
| Budi Setiawan | 22523002 | mhs2@mail.com | Sudah mengajukan — menunggu review Ka Lab (ITSC) |
| Citra Lestari | 22523003 | mhs3@mail.com | Sudah mengajukan — menunggu review Ka Lab (MVK) |
| Dani Ramadhan | 22523004 | mhs4@mail.com | Disetujui Ka Lab — menunggu keputusan Prodi |
| Eka Wijaya | 22523005 | mhs5@mail.com | Disetujui Ka Lab — menunggu keputusan Prodi |
| Fitri Handayani | 22523006 | mhs6@mail.com | Usulan mandiri — menunggu konfirmasi dosen (Sri Mulyati) |
| Gilang Saputra | 22523007 | mhs7@mail.com | Usulan mandiri — sudah dikonfirmasi dosen, antre di Ka Lab SISTEM SIBER |
| Hana Permata | 22523008 | mhs8@mail.com | Belum mengajukan (untuk demo ajukan langsung) |

### B. Dosen

| Nama | Email (login) |
|---|---|
| Sri Mulyati, S.Kom., M.Kom. | srimulyati@dosen.com |
| Fayruz Rahma, S.T., M.Eng. | fayruz@dosen.com |
| Erika Ramadhani, S.T., M.Eng. | erika@dosen.com |
| Dr. Yudi Prayudi, S.Si., M.Kom. | yudi@dosen.com |

### C. Kepala Lab (Ka Lab) — satu per laboratorium

| Laboratorium | Nama | Email (login) |
|---|---|---|
| SIRKEL | Dr. Novi Setiani, S.T., M.T. | novi@informatika.com |
| ITSC | Ir. Chandra Kusuma Dewa, S.Kom., M.Kom., Ph.D. | chandra@informatika.com |
| MVK | Ir. Izzati Muhimmah, S.T., M.Sc., Ph.D. | izzati@informatika.com |
| SISTEM SIBER | Dr. Syarif Hidayat, S.Kom., M.I.T. | syarif@informatika.com |

### D. Pengelola Lain

| Peran | Nama | Email (login) |
|---|---|---|
| Prodi (Kaprodi) | Chanifah Indah Ratnasari, S.Kom., M.Kom. | chanifah@informatika.com |
| Koordinator TA (Admin) | Admin Koordinator TA | koordinatorta@informatika.com |

---

## 3. Panduan Per Peran

> **Aturan baru (revisi terbaru):**
> - **Ka Lab per laboratorium** — tiap kepala lab hanya menangani judul & pengajuan lab-nya (validasi, review, dashboard, export semua di-scope per lab).
> - **Review berjenjang** — pengajuan mengikuti prioritas judul; ditolak di satu lab → diteruskan ke lab prioritas berikutnya; habis semua → ditolak final.
> - **Mandiri lewat dosen** — usulan mandiri dikonfirmasi dosen (+ pilih lab) dulu, baru muncul di antrean Ka Lab.
> - **Tombol wajib catatan** — tombol Validasi/Tolak (Ka Lab) & Setujui/Tolak (Prodi) nonaktif sampai catatan diisi.

### A. MAHASISWA
Menu: **Beranda · Pengajuan · Riwayat · Notifikasi · Pengaturan**

1. **Beranda** — melihat status pengajuan periode aktif & progres (belum mengajukan → diproses → diumumkan).
2. **Pengajuan** — mengajukan judul TA (hanya **1 pengajuan per periode**). Dua cara:
   - **Pilih judul dosen:** pilih **3 judul** dari katalog sebagai prioritas 1–3, isi **alasan** tiap pilihan.
   - **Usulan mandiri:** ajukan judul sendiri + deskripsi + pilih dosen pembimbing.
3. **Riwayat** — daftar pengajuan (periode aktif). Hasil (disetujui/ditolak/judul ditetapkan) baru muncul **setelah** Koordinator TA mengirim pengumuman.
4. **Notifikasi** — lonceng di kanan atas; klik untuk pratinjau, klik item untuk membuka.

*Coba dengan:* `mhs8@mail.com` (Hana) — belum mengajukan, cocok untuk demo membuat pengajuan baru.

### B. DOSEN
Menu: **Dashboard · Kelola Judul · Pengajuan · Notifikasi · Pengaturan**

1. **Dashboard** — statistik judul & pengajuan yang melibatkan judul milik dosen, grafik rasio lab.
2. **Kelola Judul** — buat judul baru, ajukan ke Ka Lab untuk validasi, tarik kembali, edit, atau hapus.
3. **Pengajuan** — melihat mahasiswa yang memilih judul dosen (default periode aktif, bisa filter periode). Di halaman ini juga muncul **"Usulan Mandiri Menunggu Konfirmasi Anda"**: dosen **konfirmasi + pilih laboratorium** (usulan diteruskan ke Ka Lab lab itu) atau **tolak**.

*Coba dengan:* `srimulyati@dosen.com` — punya judul terbanyak **dan** ada usulan mandiri Fitri menunggu konfirmasi.

### C. KEPALA LAB (KA LAB)
Menu: **Dashboard · Validasi Judul · Review Pengajuan · Kelola Judul · Export · Notifikasi · Pengaturan**

1. **Validasi Judul** — menyetujui/menolak judul dosen **di lab Anda** (disetujui → judul jadi "ditawarkan" & tampil ke mahasiswa). Tombol nonaktif sampai catatan diisi.
2. **Review Pengajuan** — hanya pengajuan yang **prioritas aktifnya = lab Anda**. Setujui judul prioritas itu, atau tolak (otomatis diteruskan ke Ka Lab lab prioritas berikutnya). Untuk usulan mandiri, muncul di sini setelah dikonfirmasi dosen.
3. **Dashboard & Export** — semua data & rekap **hanya laboratorium Anda**.

*Coba dengan:* login **tiap** akun Ka Lab (novi/chandra/izzati/syarif@informatika.com). **Demo cascade:** login `novi@informatika.com` (SIRKEL) → **tolak** pengajuan Andi (prioritas-1 SIRKEL) → login `chandra@informatika.com` (ITSC) → pengajuan Andi kini muncul di sana (prioritas-2).

### D. PRODI (KAPRODI)
Menu: **Dashboard · Monitoring · Pengajuan · Riwayat Review · Notifikasi · Pengaturan**

1. **Pengajuan** — **keputusan final**: menyetujui (menetapkan judul + dosen pembimbing) atau menolak pengajuan yang sudah lolos Ka Lab. Catatan keputusan **wajib diisi** sebelum tombol aktif.
2. **Riwayat Review** — daftar keputusan (default periode aktif, bisa filter periode lama).
3. **Monitoring** — statistik & grafik keputusan.

*Coba dengan:* `chanifah@informatika.com` — ada 2 pengajuan (Dani & Eka) siap diputuskan final.

### E. KOORDINATOR TA (ADMIN)
Menu: **Dashboard · Kelola User · Kelola Periode · Pengumuman · Monitoring · Export · Notifikasi · Pengaturan**

1. **Kelola Periode** — buat periode, aktifkan (toggle) periode, edit/hapus. Mengaktifkan periode = mengarsipkan periode lama & mereset aktivitas.
2. **Pengumuman** — buat & kirim (broadcast) pengumuman hasil. Setelah dikirim, hasil resmi dibuka ke mahasiswa.
3. **Monitoring** — pantauan lintas periode (pengajuan & judul), dengan filter periode.
4. **Kelola User** — reset password, edit, hapus akun.
5. **Export** — rekap keseluruhan (Excel/PDF).

*Coba dengan:* `koordinatorta@informatika.com` — lihat grafik tren 6 periode & coba kirim pengumuman untuk periode aktif.

---

## 4. Saran Pengujian End-to-End

Jalankan berurutan untuk menguji **satu siklus penuh** dengan data yang sudah ada:

| No | Peran | Login | Langkah | Hasil yang diharapkan |
|---|---|---|---|---|
| 1 | Mahasiswa | mhs8@mail.com (Hana) | Buka **Pengajuan** → pilih 3 judul + alasan → kirim | Status "sedang diproses"; masuk antrean Ka Lab lab prioritas-1 |
| 2 | Ka Lab | novi@informatika.com (SIRKEL) | **Review Pengajuan** → **tolak** pengajuan Andi (prioritas-1 SIRKEL) | Diteruskan ke Ka Lab ITSC (prioritas-2) |
| 3 | Ka Lab | chandra@informatika.com (ITSC) | **Review Pengajuan** → Andi muncul → **setujui** | Status Ka Lab "disetujui" → lanjut ke Prodi |
| 4 | Dosen | srimulyati@dosen.com | **Pengajuan** → konfirmasi usulan mandiri Fitri + pilih lab | Usulan Fitri masuk antrean Ka Lab lab yang dipilih |
| 5 | Prodi | chanifah@informatika.com | **Pengajuan** → putuskan final Dani & Eka (catatan wajib) | Pengajuan jadi final "disetujui" |
| 6 | Koordinator TA | koordinatorta@informatika.com | **Pengumuman** → buat & kirim untuk periode aktif | Pengumuman terkirim |
| 7 | Mahasiswa | mhs4@mail.com (Dani) | Buka **Riwayat** | Hasil & judul yang ditetapkan kini terlihat |

---

## 5. Kondisi Data Demo

- **6 periode**: 5 arsip (sudah diumumkan, mengisi grafik tren) + 1 aktif (Genap 2025/2026, dibuka).
- **25 judul** katalog tersebar di 4 lab (SIRKEL, ITSC, MVK, SISTEM SIBER); tiap lab punya Ka Lab sendiri.
- **Periode aktif**: 3 pengajuan menunggu review Ka Lab (masing-masing di lab berbeda), 2 menunggu keputusan Prodi, 1 usulan mandiri menunggu dosen (Fitri), 1 mandiri sudah dikonfirmasi (Gilang, antre Ka Lab SIBER), 1 belum mengajukan (Hana).
- **Riwayat & grafik** semua panel sudah terisi dari 5 periode sebelumnya.
- **Reset data**: bila perlu dikembalikan ke kondisi awal, jalankan `php artisan db:seed --force`.

---

*Selamat menguji. Semua fitur dapat diakses melalui menu di sisi kiri setiap panel sesuai peran.*
