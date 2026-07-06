# Konteks Proyek — Sistem Informasi Pengelolaan Judul Tugas Akhir

> Dokumen ini ringkasan lengkap & terperinci proyek untuk keperluan penulisan
> paper/laporan/jurnal. Bisa disalin utuh ke chat (mis. claude.ai) sebagai konteks.

---

## 1. Ikhtisar Proyek

**Nama/tema:** Sistem Informasi Pengelolaan Pengajuan & Penetapan Judul Tugas Akhir (Skripsi) berbasis web.

**Masalah yang diselesaikan:** Proses pengajuan judul TA secara manual rawan tumpang tindih (judul yang sama diambil dua mahasiswa), tidak transparan, sulit dimonitor, dan keputusan tersebar di banyak pihak (dosen, kepala lab, kaprodi). Sistem ini mengelola seluruh siklus dari penawaran judul oleh dosen, pengajuan oleh mahasiswa, validasi berjenjang, hingga pengumuman resmi — semuanya dalam satu **periode aktif** yang dikontrol terpusat.

**Karakter utama:** alur kerja **berjenjang (multi-level approval)** dan **berbasis periode** (per semester), dengan kerahasiaan hasil sampai pengumuman resmi.

---

## 2. Teknologi (Tech Stack)

| Lapisan | Teknologi |
|---|---|
| Framework backend | **Laravel 12** (PHP) |
| Basis data | **PostgreSQL** (di-host via **Supabase**) |
| Frontend | **Blade** (templating) + **Alpine.js** (interaktivitas) + **Tailwind CSS** (styling) |
| Visualisasi data | **Chart.js** |
| Build tool | **Vite** |
| Autentikasi | Laravel Breeze (login, reset password, profil) |
| Version control | Git/GitHub |

Pola arsitektur: **MVC** (Model–View–Controller) klasik Laravel; tidak memakai SPA/REST terpisah — render server-side dengan sentuhan Alpine untuk interaktivitas (filter, modal, polling notifikasi).

---

## 3. Aktor / Peran Pengguna (5 peran)

1. **Mahasiswa** — mengajukan judul TA (wajib 3 pilihan + opsional usulan mandiri), memantau status & riwayat.
2. **Dosen** — menawarkan judul (topik) untuk dipilih mahasiswa; memantau (view-only) siapa yang memilih judulnya. Dosen TIDAK memutuskan acc/tolak pengajuan.
3. **Kepala Laboratorium (Ka Lab)** — memvalidasi judul yang ditawarkan dosen; mereview pengajuan mahasiswa dan **menetapkan judul mana** yang diberikan (acc tingkat pertama).
4. **Program Studi / Kaprodi (prodi)** — pengambil **keputusan final** atas pengajuan yang sudah di-acc Ka Lab.
5. **Koordinator TA** (`role = koordinator_ta`) — pengendali sistem: membuka/menutup & mengaktifkan **periode**, mengelola **pengguna**, mengirim **pengumuman** resmi, dan **monitoring** lintas periode.

> Catatan teknis: nilai role di DB = `mahasiswa`, `dosen`, `ka_lab`, `prodi`, `koordinator_ta`.

---

## 4. Entitas Data (Model & Tabel)

Model Eloquent: **User, Judul, Pengajuan, Periode, Laboratorium, Aktivitas**. Tabel pendukung: `pengumuman`, `judul_logs`.

### 4.1 User
Identitas pengguna (name, email, nim/nip, role, laboratorium_id, avatar). Bersifat **persisten** (tidak ikut reset antar periode). Method penting: `jumlahBimbingan()` — menghitung jumlah mahasiswa yang dibimbing dosen **pada periode aktif** (menggantikan konsep "kuota").

### 4.2 Laboratorium
Master data lab (mis. SIRKEL, ITSC). Judul & sebagian dosen terikat ke lab.

### 4.3 Judul (topik TA yang ditawarkan)
- Field inti: `nama_judul`, `deskripsi`, `dosen_id`, `laboratorium_id`, `kode`, `status_judul`, `is_locked`.
- **`status_judul`** (satu-satunya sumber kebenaran status judul): `draft` → `pending_kalab` → `ditawarkan` / `ditolak_kalab`.
- **`is_locked`** (boolean): judul "terkunci" bila sudah ditetapkan ke seorang mahasiswa **pada periode aktif**. Dihitung ulang tiap pergantian periode (lihat §6).
- Accessor `total_peminat` & `jumlah_ditetapkan` — dihitung **khusus periode aktif**.

### 4.4 Pengajuan (inti workflow)
Satu baris = satu pengajuan mahasiswa pada satu periode. Field kunci:
- `mahasiswa_id`, `periode_id`, `jenis` (`pilih` / `mandiri`).
- **3 pilihan wajib:** `pilihan_1_id`, `pilihan_2_id`, `pilihan_3_id` (harus berbeda) + `alasan_1/2/3`.
- **Usulan mandiri (opsional):** `judul_mandiri`, `deskripsi_mandiri`, `dosen_pembimbing_id`. Bila diisi → `jenis = mandiri`, tetapi 3 pilihan tetap wajib diisi.
- **Status berjenjang:**
  - `status` (ringkas): `pending` / `disetujui` / `ditolak`.
  - `status_kalab`: `null` (menunggu) / `disetujui` / `ditolak`.
  - `status_kaprodi`: `null` (menunggu) / `disetujui` / `ditolak`.
- **Hasil penetapan:** `judul_ditetapkan_id` (FK ke judul yang ditetapkan Ka Lab) + `sumber_judul` (`pilihan_1` / `pilihan_2` / `pilihan_3` / `mandiri`).
- Jejak reviewer: `reviewed_by_kalab`, `reviewed_by_kaprodi`, tanggal, catatan.

### 4.5 Periode
Satu periode = satu siklus (mis. "Semester Genap 2025/2026"). Field: `nama`, `tanggal_buka`, `tanggal_tutup`, `is_active` (boolean — **hanya satu yang aktif**), `ditutup`. Method `periodeAktif()` mengembalikan periode dengan `is_active = true`.

### 4.6 Pengumuman
Pengumuman hasil per periode: `periode_id`, `judul`, `isi`, `dibuat_oleh`, **`dikirim_at`** (null = draft; terisi = sudah di-broadcast). Pengumuman yang sudah dikirim = penanda hasil boleh dibuka mahasiswa.

### 4.7 Aktivitas (notifikasi) & judul_logs
- `aktivitas`: notifikasi in-app per user (`tipe`, `pesan`, `link`, `is_read`). **Dibersihkan saat pergantian periode** (lihat §6).
- `judul_logs`: audit trail perubahan status judul (persisten).

---

## 5. Alur Kerja Inti (Business Process)

```
[Koordinator TA] Buka & aktifkan PERIODE
        │
        ▼
[Dosen] Buat judul (draft) ──► ajukan ke Ka Lab
        │
        ▼
[Ka Lab] Validasi judul: draft → pending_kalab → ditawarkan / ditolak_kalab
        │   (judul "ditawarkan" tampil ke mahasiswa)
        ▼
[Mahasiswa] Ajukan TA: WAJIB 3 pilihan judul berbeda
        │   (+ OPSIONAL usulan mandiri dgn dosen pembimbing)
        │   (maks 1 pengajuan per periode)
        ▼
[Ka Lab] Review pengajuan → PILIH judul yang ditetapkan
        │   (salah satu dari 3 pilihan ATAU usulan mandiri).
        │   Bila mandiri di-acc → judul katalog baru dibuat otomatis.
        │   → set status_kalab = disetujui/ditolak, judul_ditetapkan_id, sumber_judul
        ▼
[Prodi/Kaprodi] Keputusan FINAL → status_kaprodi = disetujui/ditolak
        │   (saat disetujui & final, judul dikunci: is_locked = true)
        ▼
[Koordinator TA] Kirim PENGUMUMAN (broadcast) per periode (dikirim_at terisi)
        │
        ▼
[Mahasiswa] Hasil baru TERLIHAT (disetujui/ditolak + judul diterima)
```

**Aturan penting di alur:**
- Mahasiswa **wajib mengisi 3 pilihan judul** yang berbeda; **usulan mandiri opsional**.
- Ka Lab-lah yang **menentukan judul mana** yang diberikan (bisa salah satu dari 3 pilihan atau usulan mandiri). Bila usulan mandiri yang dipilih, sistem **membuat judul katalog baru** dari usulan tersebut.
- Dosen **tidak** memutuskan acc/tolak — perannya menawarkan judul & memantau.

---

## 6. Prinsip Arsitektur & Keputusan Desain (paling penting untuk paper)

### 6.1 Periode sebagai naungan seluruh aktivitas ("periode scoping")
**Prinsip inti:** *Semua aktivitas dinaungi satu periode aktif; saat ganti periode, semuanya "reset"; hanya identitas/katalog yang persisten.*
- Yang **ter-scope ke periode aktif** (ikut reset, tapi data lama tetap tersimpan sebagai riwayat): pengajuan, kunci judul, jumlah peminat, jumlah bimbingan dosen, seluruh statistik dashboard, pengumuman, **notifikasi**.
- Yang **persisten** (seperti identitas): data user, katalog judul + `status_judul`, audit log (`judul_logs`).
- "Reset" ≠ hapus: data pengajuan periode lama tetap ada untuk riwayat; hanya tampilan "berjalan" yang difokuskan ke periode aktif. Data lama dapat ditelusuri lewat filter periode (di dosen & koordinator TA).

### 6.2 Kerahasiaan hasil sampai pengumuman ("anti-bocor")
Mahasiswa **tidak boleh** melihat keputusan (disetujui/ditolak, judul ditetapkan) sebelum Koordinator TA mengirim pengumuman resmi. Implementasi: hasil di beranda/pengajuan/riwayat mahasiswa di-mask menjadi "sedang diproses" hingga `pengumuman.dikirim_at` untuk periode tersebut terisi. Pengambil keputusan (Ka Lab, Prodi) tetap melihat status apa adanya; dosen (staf internal) juga boleh melihat.

### 6.3 Penguncian judul per periode ("is_locked" dihitung ulang)
`is_locked` global per judul tetapi maknanya "judul ini sudah ditetapkan pada periode aktif". Saat periode aktif berganti, kunci **dihitung ulang** dari data periode yang diaktifkan: semua judul dibuka, lalu judul yang sudah final-disetujui pada periode itu dikunci kembali. Efeknya: judul yang terpakai di periode lalu otomatis **terbuka lagi** di periode baru, tetapi mengaktifkan kembali periode lama tetap mempertahankan kunci historisnya.

### 6.4 Reset notifikasi antar periode
Notifikasi bersifat sementara; saat periode diaktifkan, lonceng semua pengguna dibersihkan agar mulai bersih. Riwayat resmi tetap aman di pengajuan/pengumuman/audit log.

### 6.5 Satu sumber kebenaran status judul
Status judul hanya dari `status_judul` (+ `is_locked`). Kolom legacy `status`/`is_available` sudah dihapus agar tak ada "status bayangan".

---

## 7. Fitur per Peran (ringkas, berbasis route nyata)

- **Mahasiswa:** beranda (progress real-time), form pengajuan (3 pilihan + mandiri), riwayat pengajuan (timeline + modal detail, penanda "judul diterima" termasuk dari usulan mandiri), notifikasi, pengaturan profil.
- **Dosen:** dashboard (statistik + grafik tren), kelola judul (buat/ajukan/tarik, satu daftar dengan penanda Tersedia/Terkunci), pantau pengajuan ke judulnya (dengan filter periode/riwayat), notifikasi, pengaturan.
- **Ka Lab:** dashboard (statistik + grafik), validasi judul (acc/tolak), review pengajuan + penetapan judul (acc/tolak), notifikasi, pengaturan.
- **Prodi/Kaprodi:** dashboard (statistik + grafik), review pengajuan untuk keputusan final (acc/tolak), riwayat keputusan, monitoring, notifikasi, pengaturan.
- **Koordinator TA:** dashboard (ringkasan + chart per-periode), kelola **periode** (buat/aktifkan/tutup), kelola **pengguna** (edit/reset password/hapus), **pengumuman** (buat & broadcast), **monitoring** lintas periode (pengajuan & judul, dengan filter periode), **ekspor** data (Excel/PDF), notifikasi, pengaturan.

---

## 8. Visualisasi Data & Dashboard

Tiap peran pengambil keputusan memiliki kartu statistik + grafik (Chart.js), di-scope ke periode aktif (kecuali tren yang lintas periode):
- **Dosen:** donut Distribusi Keputusan; tren Pengajuan (line) & tren Keputusan (bar) lintas periode; statistik per-lab.
- **Ka Lab:** donut Distribusi Status Judul; tren Pengajuan Masuk; tren Keputusan Validasi (berdasarkan `status_kalab`).
- **Prodi:** donut Keputusan Final (periode aktif, `status_kaprodi`); tren Pengajuan; tren Keputusan Final.
- **Koordinator TA:** ringkasan global (jumlah mahasiswa/dosen/judul) + statistik pengajuan periode aktif + distribusi per-periode.

Label tren memakai nama periode; setiap chart punya *empty-state* saat belum ada data.

---

## 9. Aturan Bisnis Penting (untuk dibahas di paper)

1. **1 pengajuan per mahasiswa per periode** (hasil dirahasiakan s/d pengumuman, jadi tak ada re-submit di periode sama; periode baru = boleh mengajukan lagi).
2. **3 pilihan judul wajib & berbeda**; usulan mandiri opsional (butuh dosen pembimbing).
3. **Pengajuan hanya saat ada periode aktif** (gate periode).
4. **Pencegahan tabrakan judul:** judul yang sudah ditetapkan ke mahasiswa lain pada periode yang sama tidak bisa dipilih lagi (cek `is_locked`/penetapan).
5. **Penetapan judul oleh Ka Lab** menentukan `judul_ditetapkan_id` + `sumber_judul`; bila usulan mandiri yang dipilih, dibuatkan judul katalog baru.
6. **Keputusan final di Prodi**; setelah final-disetujui, judul dikunci.
7. **Pengumuman per periode** sebagai gerbang transparansi hasil.

---

## 10. Glosarium Istilah

- **Periode aktif** — satu siklus semester yang sedang berjalan; pusat seluruh aktivitas.
- **Usulan mandiri** — judul yang diusulkan sendiri oleh mahasiswa (di luar katalog dosen), butuh dosen pembimbing.
- **Judul ditetapkan (`judul_ditetapkan_id`)** — judul final yang dipilih Ka Lab untuk mahasiswa.
- **Sumber judul (`sumber_judul`)** — asal judul yang ditetapkan: pilihan ke-1/2/3 atau usulan mandiri.
- **Pengumuman (broadcast)** — publikasi resmi hasil per periode oleh Koordinator TA; gerbang kerahasiaan.
- **Kunci judul (`is_locked`)** — penanda judul sudah terpakai pada periode aktif.

---

## 11. Catatan Implementasi (opsional, untuk bagian "Implementasi")

- PostgreSQL: kolom boolean ditulis eksplisit lewat `DB::raw('true'/'false')` (konvensi untuk menghindari error tipe).
- Pemisahan tampilan "aktivitas berjalan" (periode aktif) vs "riwayat" (lintas periode) konsisten di semua peran.
- Audit & log: perubahan status judul tercatat di `judul_logs`.
- Seeder demo tersedia untuk membangkitkan beberapa periode arsip + 1 periode berjalan dengan statistik bervariasi (untuk demonstrasi grafik).

---

## 12. Kerangka & Panduan Penulisan Jurnal (mulai dari nol)

Struktur umum jurnal informatika Indonesia (gaya SINTA/IMRaD). Tiap bagian sudah
dipetakan ke fakta proyek di atas — tinggal dikembangkan jadi paragraf.

### Judul (saran)
Contoh pola: *"Rancang Bangun Sistem Informasi Pengelolaan Pengajuan Judul Tugas
Akhir Berbasis Web dengan Pendekatan Berjenjang dan Penjadwalan Periode"* (sesuaikan).

### Abstrak (150–250 kata) + Kata Kunci
Rumus 5 kalimat: (1) latar/masalah → (2) tujuan → (3) metode (mis. Waterfall/
prototyping; Laravel, PostgreSQL) → (4) hasil utama (sistem 5 peran, alur berjenjang,
periode, anti-bocor, pengujian) → (5) kesimpulan/manfaat.
Kata kunci: sistem informasi, tugas akhir, validasi berjenjang, periode, Laravel.

### I. Pendahuluan
- **Latar belakang:** masalah pengelolaan judul TA manual (tabrakan judul, tidak
  transparan, sulit dimonitor, keputusan tersebar). Lihat §1.
- **Rumusan masalah:** bagaimana merancang sistem yang mengelola alur berjenjang +
  menjaga kerahasiaan hasil + mendukung siklus per-periode?
- **Tujuan & manfaat:** membangun sistem; mempermudah tiap peran; transparansi.
- **Batasan:** lingkup 5 peran, berbasis web, satu prodi/lab, dsb.

### II. Tinjauan Pustaka / Landasan Teori
- Penelitian terdahulu: bandingkan dengan sistem pengajuan TA/skripsi lain (cari 3–5
  jurnal pembanding, tonjolkan kebaruan: **scoping periode + anti-bocor + penetapan
  judul berjenjang dengan usulan mandiri**).
- Teori pendukung: Sistem Informasi, MVC, Laravel, PostgreSQL, pengujian black-box/UAT.

### III. Metodologi Penelitian
- **Metode pengembangan:** pilih & jelaskan satu — *Waterfall* (analisis → desain →
  implementasi → pengujian → pemeliharaan) atau *Prototyping/RAD* (cocok karena
  pengembangan iteratif). 
- **Analisis kebutuhan:** fungsional (per peran, §7) & non-fungsional.
- **Perancangan:** sertakan diagram — **Use Case** (5 aktor), **Activity/Flowchart**
  (alur §5), **ERD** (relasi §4), rancangan antarmuka.
- **Alat & bahan:** §2.

### IV. Hasil dan Pembahasan
- **Implementasi antarmuka:** screenshot tiap peran (login, dashboard+grafik, form
  pengajuan 3 pilihan + mandiri, validasi Ka Lab, keputusan Prodi, pengumuman).
- **Pembahasan fitur kunci** (nilai jual untuk pembahasan): periode scoping (§6.1),
  anti-bocor (§6.2), penguncian judul per-periode (§6.3), penetapan judul + usulan
  mandiri (§5), visualisasi data (§8).
- **Pengujian:** tabel **Black-box testing** (skenario per fitur → harapan → hasil)
  dan/atau **UAT** (kuesioner ke pengguna tiap peran). Bisa tambah pengujian aturan
  bisnis (§9): 1 pengajuan/periode, 3 pilihan wajib, cegah tabrakan judul.

### V. Kesimpulan dan Saran
- Kesimpulan: sistem berhasil dibangun & memenuhi kebutuhan; tegaskan kontribusi.
- Saran: integrasi bimbingan/sidang, notifikasi email, multi-prodi, dsb.

### Daftar Pustaka
Format sesuai jurnal target (IEEE/APA). Sertakan referensi Laravel, metode SDLC,
jurnal pembanding.

### Tabel pemetaan cepat (fakta proyek → bagian jurnal)
| Bagian | Ambil dari |
|---|---|
| Pendahuluan | §1 |
| Landasan teori | §2 + literatur eksternal |
| Use case / kebutuhan | §3, §7 |
| Flowchart proses | §5 |
| ERD / struktur data | §4 |
| Hasil & pembahasan | §6, §8, screenshot |
| Pengujian | §9 (jadikan skenario uji) |
| Kesimpulan | §1 + §6 (kontribusi) |

---

*Akhir dokumen konteks.*
