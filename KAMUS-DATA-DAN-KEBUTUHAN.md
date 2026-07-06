# Kamus Data & Kebutuhan Sistem — Bahan Bab Analisis & Perancangan

> Pendamping [KONTEKS-PROYEK.md](KONTEKS-PROYEK.md). Berisi **kamus data** (struktur
> tabel field-demi-field) dan **kebutuhan fungsional & non-fungsional**. Disusun dari
> skema basis data PostgreSQL nyata.

---

## 1. Kamus Data (Data Dictionary)

Hanya kolom yang relevan dengan aplikasi yang dicantumkan. PK = Primary Key, FK =
Foreign Key.

### 1.1 Tabel `users` — pengguna
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas pengguna |
| name | varchar | NOT NULL | Nama lengkap |
| email | varchar | NOT NULL, unik | Email (login) |
| password | varchar | NOT NULL | Kata sandi (hash) |
| role | varchar | NOT NULL | `mahasiswa`/`dosen`/`ka_lab`/`prodi`/`koordinator_ta` |
| nim | varchar | null | NIM (untuk mahasiswa) |
| avatar | varchar | null | Path foto profil |
| laboratorium_id | bigint | FK→laboratorium | Lab terkait (dosen/ka_lab) |
| email_verified_at, remember_token, created_at, updated_at | — | — | Bawaan Laravel |

### 1.2 Tabel `laboratorium` — master laboratorium
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas lab |
| nama | varchar | NOT NULL | Nama lab |
| deskripsi | text | null | Deskripsi |

### 1.3 Tabel `periode` — periode/siklus semester
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas periode |
| nama | varchar | null | Nama periode (mis. "Semester Genap 2025/2026") |
| tanggal_buka | date | null | Tanggal mulai |
| tanggal_tutup | date | null | Tanggal selesai |
| is_active | boolean | NOT NULL | **Penanda periode aktif (hanya satu true)** |
| ditutup | boolean | NOT NULL | Penanda periode telah ditutup/arsip |

### 1.4 Tabel `judul` — judul/topik TA
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas judul |
| kode | varchar | null | Kode judul (mis. ITSC-74) |
| nama_judul | varchar | NOT NULL | Judul topik |
| deskripsi | text | null | Deskripsi |
| dosen_id | bigint | FK→users | Dosen pemilik/penawar |
| laboratorium_id | bigint | FK→laboratorium | Lab |
| status_judul | varchar | NOT NULL | `draft`/`pending_kalab`/`ditawarkan`/`ditolak_kalab` |
| is_locked | boolean | NOT NULL | Terkunci (sudah ditetapkan pada periode aktif) |
| catatan_kalab | text | null | Catatan validasi Ka Lab |
| reviewed_by_kalab | bigint | FK→users | Ka Lab yang memvalidasi |
| reviewed_at_kalab | timestamp | null | Waktu validasi |
| submitted_to_kalab_at / _by | — | — | Jejak pengajuan judul ke Ka Lab |

### 1.5 Tabel `pengajuan` — pengajuan TA mahasiswa (entitas inti)
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas pengajuan |
| mahasiswa_id | bigint | FK→users | Mahasiswa pengaju |
| periode_id | bigint | FK→periode | Periode pengajuan |
| jenis | varchar | NOT NULL | `pilih` / `mandiri` |
| status | varchar | NOT NULL | `pending`/`disetujui`/`ditolak` (ringkas) |
| status_kalab | varchar | null | `null`(menunggu)/`disetujui`/`ditolak` |
| status_kaprodi | varchar | null | `null`(menunggu)/`disetujui`/`ditolak` |
| pilihan_1_id | bigint | FK→judul | Pilihan judul ke-1 (wajib) |
| pilihan_2_id | bigint | FK→judul | Pilihan judul ke-2 (wajib) |
| pilihan_3_id | bigint | FK→judul | Pilihan judul ke-3 (wajib) |
| alasan_1/2/3 | text | null | Alasan tiap pilihan |
| judul_mandiri | varchar | null | Judul usulan mandiri (opsional) |
| deskripsi_mandiri | text | null | Deskripsi usulan mandiri |
| dosen_pembimbing_id | bigint | FK→users | Pembimbing usulan mandiri |
| judul_ditetapkan_id | bigint | FK→judul | Judul final yang ditetapkan Ka Lab |
| sumber_judul | varchar | null | `pilihan_1`/`pilihan_2`/`pilihan_3`/`mandiri` |
| reviewed_by_kalab / _kaprodi | bigint | FK→users | Reviewer |
| tanggal_review_kalab / _kaprodi | timestamp | null | Waktu review |
| catatan_kalab_pengajuan / catatan_kaprodi | text | null | Catatan reviewer |

### 1.6 Tabel `pengumuman` — pengumuman hasil per periode
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas pengumuman |
| periode_id | bigint | FK→periode | Periode terkait |
| dibuat_oleh | bigint | FK→users | Koordinator TA pembuat |
| judul | varchar | NOT NULL | Judul pengumuman |
| isi | text | NOT NULL | Isi pengumuman |
| dikirim_at | timestamp | null | **null=draft; terisi=sudah dikirim (gerbang hasil)** |

### 1.7 Tabel `aktivitas` — notifikasi in-app
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas notifikasi |
| user_id | bigint | FK→users | Penerima |
| tipe | varchar | NOT NULL | Jenis notifikasi |
| pesan | text | NOT NULL | Isi pesan |
| link | varchar | null | Tautan tujuan |
| is_read | boolean | NOT NULL | Status terbaca |

### 1.8 Tabel `judul_logs` — audit trail status judul
| Kolom | Tipe | Ket. | Keterangan |
|---|---|---|---|
| id | bigint | PK | Identitas log |
| judul_id | bigint | FK→judul | Judul terkait |
| user_id | bigint | FK→users | Pelaku aksi |
| aksi | varchar | NOT NULL | Aksi (mis. divalidasi_kalab) |
| dari_status / ke_status | varchar | null | Perubahan status |
| catatan | text | null | Catatan |

---

## 2. Kebutuhan Fungsional (Functional Requirements)

Kode FR dapat langsung dipakai di bab Analisis Kebutuhan.

| Kode | Kebutuhan | Aktor |
|---|---|---|
| FR-01 | Sistem menyediakan autentikasi (login/logout) per peran | Semua |
| FR-02 | Mahasiswa dapat mengajukan TA dengan 3 pilihan judul wajib (berbeda) | Mahasiswa |
| FR-03 | Mahasiswa dapat menambahkan usulan mandiri + dosen pembimbing (opsional) | Mahasiswa |
| FR-04 | Sistem membatasi 1 pengajuan per mahasiswa per periode | Sistem |
| FR-05 | Mahasiswa dapat melihat progres & riwayat pengajuan | Mahasiswa |
| FR-06 | Dosen dapat membuat, mengajukan, & menarik judul | Dosen |
| FR-07 | Dosen dapat memantau pengajuan ke judulnya (filter periode) | Dosen |
| FR-08 | Ka Lab dapat memvalidasi (acc/tolak) judul dosen | Ka Lab |
| FR-09 | Ka Lab dapat mereview pengajuan & menetapkan judul (pilihan/mandiri) | Ka Lab |
| FR-10 | Sistem mencegah penetapan judul yang sudah diambil pada periode sama | Sistem |
| FR-11 | Prodi dapat memberi keputusan final (acc/tolak) | Prodi |
| FR-12 | Koordinator TA dapat mengelola periode (buka/aktifkan/tutup) | Koord. TA |
| FR-13 | Sistem hanya mengizinkan satu periode aktif & me-reset aktivitas saat berganti | Sistem |
| FR-14 | Koordinator TA dapat mengirim pengumuman per periode | Koord. TA |
| FR-15 | Sistem merahasiakan hasil dari mahasiswa sampai pengumuman dikirim | Sistem |
| FR-16 | Koordinator TA dapat mengelola pengguna (edit/reset/hapus) | Koord. TA |
| FR-17 | Sistem menyediakan monitoring & ekspor data (Excel/PDF) | Koord. TA/Prodi |
| FR-18 | Sistem menampilkan statistik & grafik pada dashboard tiap peran | Dosen/Ka Lab/Prodi/Koord. TA |
| FR-19 | Sistem mengirim notifikasi in-app atas peristiwa penting | Semua |

---

## 3. Kebutuhan Non-Fungsional (Non-Functional Requirements)

| Kode | Aspek | Kebutuhan |
|---|---|---|
| NFR-01 | Keamanan | Otorisasi berbasis peran; kata sandi di-hash; hasil dirahasiakan s/d pengumuman (anti-bocor) |
| NFR-02 | Integritas data | Aturan bisnis ditegakkan (gate periode, 3 pilihan beda, cegah tabrakan judul); relasi FK & batasan (constraint) di DB |
| NFR-03 | Usability | Antarmuka konsisten & responsif (Tailwind), umpan balik jelas, navigasi per peran |
| NFR-04 | Ketertelusuran | Audit trail status judul (`judul_logs`) & jejak reviewer pada pengajuan |
| NFR-05 | Konsistensi siklus | Seluruh aktivitas ter-scope ke periode aktif; reset rapi tanpa menghapus riwayat |
| NFR-06 | Ketersediaan | Berbasis web, dapat diakses melalui peramban; basis data ter-host (Supabase) |
| NFR-07 | Pemeliharaan | Pola MVC; satu sumber kebenaran status; kode legacy dibersihkan |

---

*Akhir dokumen kamus data & kebutuhan.*
