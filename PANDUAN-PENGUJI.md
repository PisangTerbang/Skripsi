# 📖 PANDUAN PENGUJI — Sistem Otomatisasi Penawaran & Penetapan Judul TA

> Dokumen ini berisi **akun login, alur sistem, panduan tiap peran, dan skenario pengujian siap pakai**.
> Data demo sudah disiapkan agar setiap fitur bisa langsung dicoba tanpa persiapan tambahan.

---

## 1. Informasi Awal

| Item | Keterangan |
|---|---|
| **Alamat sistem** | (isi sesuai link ngrok / lokal, mis. `http://localhost:8000` atau `https://xxxx.ngrok-free.app`) |
| **Halaman login** | Buka alamat sistem → otomatis ke halaman login |
| **Kata sandi semua akun** | `password` |
| **Periode aktif saat ini** | **Semester Genap 2025/2026** (dibuka, sedang berjalan) |

> ℹ️ **Jika tampilan terlihat rusak** (tanpa warna/tata letak berantakan), itu masalah aset, bukan sistem. Pastikan file `public/hot` sudah dihapus dan aset sudah di-build. Setelah itu muat ulang halaman.

---

## 2. Daftar Akun Login (semua kata sandi: `password`)

### 👨‍🎓 Mahasiswa
| Nama | NIM | Email (login) | Kondisi di periode aktif |
|---|---|---|---|
| Andi Pratama | 22523001 | `mhs1@mail.com` | Sudah mengajukan — **menunggu review Ka Lab** |
| Budi Setiawan | 22523002 | `mhs2@mail.com` | Sudah mengajukan — **menunggu review Ka Lab** |
| Citra Lestari | 22523003 | `mhs3@mail.com` | Sudah mengajukan — **menunggu review Ka Lab** |
| Dani Ramadhan | 22523004 | `mhs4@mail.com` | Sudah disetujui Ka Lab — **menunggu keputusan Prodi** |
| Eka Wijaya | 22523005 | `mhs5@mail.com` | Sudah disetujui Ka Lab — **menunggu keputusan Prodi** |
| Fitri Handayani | 22523006 | `mhs6@mail.com` | Usulan **mandiri menunggu konfirmasi dosen** (Sri Mulyati) |
| Gilang Saputra | 22523007 | `mhs7@mail.com` | Usulan mandiri **sudah dikonfirmasi dosen** — antre di Ka Lab SISTEM SIBER |
| Hana Permata | 22523008 | `mhs8@mail.com` | **Belum mengajukan** (untuk demo ajukan langsung) |

### 👨‍🏫 Dosen
| Nama | Email (login) |
|---|---|
| Sri Mulyati, S.Kom., M.Kom. | `srimulyati@dosen.com` |
| Fayruz Rahma, S.T., M.Eng. | `fayruz@dosen.com` |
| Erika Ramadhani, S.T., M.Eng. | `erika@dosen.com` |
| Dr. Yudi Prayudi, S.Si., M.Kom. | `yudi@dosen.com` |

### 🧑‍🔬 Kepala Lab (Ka Lab) — **satu per laboratorium**
Tiap Ka Lab **hanya** melihat & memutuskan judul/pengajuan laboratoriumnya sendiri.

| Laboratorium | Nama | Email (login) | Ada antrean pengajuan? |
|---|---|---|---|
| **SIRKEL** | Dr. Ahmad Fauzi, M.Kom. | `kalab.sirkel@informatika.com` | 1 pengajuan (prioritas-1) |
| **ITSC** | Dr. Rina Kartika, M.Cs. | `kalab.itsc@informatika.com` | 1 pengajuan (prioritas-1) |
| **MVK** | Dr. Bagus Nugroho, M.Kom. | `kalab.mvk@informatika.com` | 1 pengajuan (prioritas-1) |
| **SISTEM SIBER** | Dr. Sinta Maharani, M.T. | `kalab.siber@informatika.com` | 1 pengajuan + 1 mandiri (Gilang) |

### 🧑‍💼 Pengelola Lain
| Peran | Nama | Email (login) |
|---|---|---|
| **Prodi (Kaprodi)** | Dr. Budi Santoso, M.T. | `prodi@informatika.com` |
| **Koordinator TA (Admin)** | Admin Koordinator TA | `koordinatorta@informatika.com` |

---

## 3. Alur Sistem (Gambaran Besar)

```
 [1] DOSEN                [2] KA LAB              [3] MAHASISWA
 Buat judul     ──────▶   Validasi judul  ──────▶ Lihat katalog judul
 & ajukan ke              (setujui/tolak)         & ajukan (pilih 3
 Ka Lab                                           prioritas + alasan,
                                                  atau usulan mandiri)
                                                          │
                                                          ▼
 [6] MAHASISWA            [5] PRODI               [4] KA LAB
 Lihat hasil     ◀──────  Keputusan final ◀────── Review pengajuan
 di Riwayat               (tetapkan judul         (setujui/tolak)
 (setelah                 + dosen pembimbing)
 diumumkan)                     │
                                ▼
                    [KOORDINATOR TA] Kirim pengumuman
                    → hasil resmi dibuka ke mahasiswa
```

**Prinsip penting:** Seluruh aktivitas terikat pada **satu periode aktif**. Saat Koordinator TA mengganti periode, data periode lama diarsipkan dan pengajuan yang masih menggantung otomatis difinalkan.

### ⭐ Aturan baru (revisi terbaru)

- **Ka Lab per laboratorium** — tiap kepala lab hanya menangani judul & pengajuan lab-nya sendiri (validasi judul, review pengajuan, dashboard semua di-scope per lab).
- **Review pengajuan BERJENJANG (prioritas)** — pengajuan masuk ke Ka Lab lab dari **judul prioritas-1**. Jika **ditolak**, otomatis diteruskan ke Ka Lab lab **prioritas-2**, lalu **prioritas-3**. Ditolak di semua prioritas = tertolak final. Ka Lab hanya bisa **menyetujui judul prioritas yang sedang giliran lab-nya**.
- **Usulan mandiri lewat dosen dulu** — usulan mandiri masuk ke **dosen pembimbing** untuk dikonfirmasi & ditentukan laboratoriumnya, **baru** muncul di antrean Ka Lab lab tersebut.
- **Tombol wajib catatan** — tombol Validasi/Tolak (Ka Lab) & Setujui/Tolak (Prodi) **nonaktif sampai catatan diisi**.

---

## 4. Panduan Per Peran

### 👨‍🎓 A. MAHASISWA
Menu: **Beranda · Pengajuan · Riwayat · Notifikasi · Pengaturan**

1. **Beranda** — melihat status pengajuan periode aktif & progres (belum mengajukan → diproses → diumumkan).
2. **Pengajuan** — mengajukan judul TA (hanya **1 pengajuan per periode**). Dua cara:
   - **Pilih judul dosen:** pilih **3 judul** dari katalog sebagai prioritas 1–3, isi **alasan** tiap pilihan.
   - **Usulan mandiri:** ajukan judul sendiri + deskripsi + pilih dosen.
3. **Riwayat** — daftar pengajuan (periode aktif). Hasil (disetujui/ditolak/judul ditetapkan) baru muncul **setelah** Koordinator TA mengirim pengumuman.
4. **Notifikasi** — lonceng di kanan atas; klik untuk pratinjau, klik item untuk membuka.

> 🧪 **Coba dengan:** `mhs6@mail.com` (Fitri) — belum mengajukan, cocok untuk demo membuat pengajuan baru.

### 👨‍🏫 B. DOSEN
Menu: **Dashboard · Kelola Judul · Pengajuan · Notifikasi · Pengaturan**

1. **Dashboard** — statistik judul & pengajuan yang melibatkan judul milik dosen, grafik rasio lab.
2. **Kelola Judul** — **buat judul** baru, **ajukan ke Ka Lab** untuk validasi, **tarik** kembali, **edit**, atau **hapus**.
3. **Pengajuan** — melihat mahasiswa yang memilih judul dosen (default periode aktif, bisa filter periode). Di halaman ini juga muncul **"Usulan Mandiri Menunggu Konfirmasi Anda"**: dosen **konfirmasi + pilih laboratorium** (usulan diteruskan ke Ka Lab lab itu) atau **tolak**.

> 🧪 **Coba dengan:** `srimulyati@dosen.com` — punya judul terbanyak **dan** ada 1 usulan mandiri (Fitri) menunggu konfirmasi + pilih lab.

### 🧑‍🔬 C. KEPALA LAB (KA LAB)
Menu: **Dashboard · Validasi Judul · Review Pengajuan · Kelola Judul · Export · Notifikasi · Pengaturan**

1. **Validasi Judul** — menyetujui/menolak judul dosen **di lab Anda** (disetujui → judul jadi **"ditawarkan"**). Tombol nonaktif s/d catatan diisi.
2. **Review Pengajuan** — hanya pengajuan yang **prioritas aktifnya = lab Anda**. Setujui judul prioritas itu, atau tolak (otomatis diteruskan ke Ka Lab lab prioritas berikutnya).
3. **Dashboard/Export** — semua data & rekap **hanya lab Anda**.

> 🧪 **Coba dengan:** login **tiap** akun `kalab.<lab>@informatika.com` — masing-masing punya 1 antrean.
> **Demo cascade:** login `kalab.sirkel@` → **tolak** pengajuan Andi (prioritas-1 SIRKEL) → login `kalab.itsc@` → pengajuan Andi kini muncul di sana (prioritas-2). Login `kalab.siber@` → ada usulan mandiri Gilang (sudah dikonfirmasi dosen) siap direview.

### 🎓 D. PRODI (KAPRODI)
Menu: **Dashboard · Monitoring · Pengajuan · Riwayat Review · Notifikasi · Pengaturan**

1. **Pengajuan** — **keputusan final**: menyetujui (menetapkan judul + dosen pembimbing) atau menolak pengajuan yang sudah lolos Ka Lab.
2. **Riwayat Review** — daftar keputusan (default periode aktif, bisa filter periode lama).
3. **Monitoring** — statistik & grafik keputusan.

> 🧪 **Coba dengan:** `prodi@informatika.com` — di periode aktif ada **2 pengajuan** (Dani, Eka) yang sudah disetujui Ka Lab, siap diputuskan final.

### 🗂️ E. KOORDINATOR TA (ADMIN)
Menu: **Dashboard · Kelola User · Kelola Periode · Pengumuman · Monitoring · Export · Notifikasi · Pengaturan**

1. **Kelola Periode** — **buat periode**, **aktifkan** (toggle) periode, edit/hapus. Mengaktifkan periode = mengarsipkan periode lama & mereset aktivitas.
2. **Pengumuman** — **buat & kirim (broadcast)** pengumuman hasil. Setelah dikirim, hasil resmi dibuka ke mahasiswa.
3. **Monitoring** — pantauan lintas periode (pengajuan & judul), dengan filter periode.
4. **Kelola User** — reset password, edit, hapus akun.
5. **Export** — rekap keseluruhan (Excel/PDF).

> 🧪 **Coba dengan:** `koordinatorta@informatika.com` — lihat grafik tren 6 periode & coba kirim pengumuman untuk periode aktif.

---

## 5. Skenario Pengujian End-to-End (Siap Pakai)

Jalankan berurutan untuk menguji **satu siklus penuh** dengan data yang sudah ada:

| No | Peran | Login | Langkah | Hasil yang diharapkan |
|---|---|---|---|---|
| 1 | Mahasiswa | `mhs8@mail.com` (Hana) | Buka **Pengajuan** → pilih 3 judul + alasan → kirim | Status jadi "sedang diproses"; masuk antrean Ka Lab lab prioritas-1 |
| 2 | Ka Lab | `kalab.sirkel@informatika.com` | **Review Pengajuan** → **tolak** pengajuan Andi (prioritas-1 SIRKEL) | Pengajuan diteruskan ke Ka Lab ITSC (prioritas-2) |
| 3 | Ka Lab | `kalab.itsc@informatika.com` | **Review Pengajuan** → Andi kini muncul → **setujui** | Status Ka Lab "disetujui" → lanjut ke Prodi |
| 4 | Dosen | `srimulyati@dosen.com` | **Pengajuan** → konfirmasi usulan mandiri Fitri + pilih lab | Usulan Fitri masuk antrean Ka Lab lab yang dipilih |
| 5 | Prodi | `prodi@informatika.com` | **Pengajuan** → putuskan final Dani & Eka (catatan wajib) | Pengajuan jadi final "disetujui" |
| 6 | Koordinator TA | `koordinatorta@informatika.com` | **Pengumuman** → buat & kirim untuk periode aktif | Pengumuman terkirim |
| 7 | Mahasiswa | `mhs4@mail.com` (Dani) | Buka **Riwayat** | Hasil & judul yang ditetapkan kini terlihat |

---

## 6. Kondisi Data Demo (Ringkas)

- **6 periode**: 5 arsip (sudah diumumkan, mengisi grafik tren) + 1 aktif (Genap 2025/2026, dibuka).
- **25 judul** katalog tersebar di 4 lab (SIRKEL, ITSC, MVK, SISTEM SIBER); tiap lab punya Ka Lab sendiri.
- **Periode aktif**: 3 pengajuan menunggu review Ka Lab (masing-masing di lab berbeda), 2 menunggu keputusan Prodi, 1 usulan mandiri menunggu dosen (Fitri), 1 mandiri sudah dikonfirmasi (Gilang, antre di Ka Lab SIBER), 1 belum mengajukan (Hana).
- **Riwayat & grafik** semua panel sudah terisi dari 5 periode sebelumnya.

---

## 7. Tips Selama Pengujian

- **Notifikasi**: ikon lonceng menampilkan pratinjau saat diklik; klik item untuk membuka detail.
- **Keluar akun**: tombol logout memunculkan konfirmasi lebih dulu.
- **Ganti peran**: logout, lalu login dengan akun peran lain (tabel di bagian 2).
- **Reset data**: bila data perlu dikembalikan ke kondisi bersih, jalankan `php artisan db:seed --force` (menimpa semua data uji ke keadaan awal panduan ini).

---

*Selamat menguji. Semua fitur dapat diakses melalui menu di sisi kiri setiap panel sesuai peran.*
