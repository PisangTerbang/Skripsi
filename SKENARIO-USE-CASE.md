# Skenario Use Case (Use Case Scenario) — Bahan Bab Perancangan

> Pendamping [KONTEKS-PROYEK.md](KONTEKS-PROYEK.md) & [DIAGRAM-DAN-METODOLOGI.md](DIAGRAM-DAN-METODOLOGI.md).
> Deskripsi naratif tiap use case: aktor, deskripsi, prakondisi, pascakondisi,
> skenario utama (normal), dan skenario alternatif. Sesuai aturan validasi & alur
> nyata pada sistem. Salin ke claude.ai untuk dikembangkan menjadi paragraf/tabel.

Format tiap use case mengikuti pola: *Nama – Aktor – Deskripsi – Prakondisi –
Pascakondisi – Skenario Utama – Skenario Alternatif*.

---

## UC-00 — Login / Autentikasi
- **Aktor:** Semua peran (Mahasiswa, Dosen, Ka Lab, Prodi, Koordinator TA)
- **Deskripsi:** Pengguna masuk ke sistem sesuai perannya.
- **Prakondisi:** Akun terdaftar; pengguna belum login.
- **Pascakondisi:** Pengguna masuk & diarahkan ke dashboard sesuai role.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka halaman login | 2. Menampilkan form email & password |
| 3. Mengisi kredensial & menekan "Masuk" | 4. Memvalidasi kredensial |
| | 5. Mengarahkan ke dashboard sesuai role |

**Skenario Alternatif**
- 4a. Kredensial salah → sistem menampilkan pesan error & tetap di halaman login.

---

## UC-01 — Mahasiswa Mengajukan Judul TA  *(use case inti)*
- **Aktor:** Mahasiswa
- **Deskripsi:** Mahasiswa mengajukan judul TA dengan **wajib 3 pilihan judul** yang
  berbeda dan **opsional** menyertakan **usulan mandiri** (judul sendiri + dosen
  pembimbing).
- **Prakondisi:** Mahasiswa login; **terdapat periode aktif**; mahasiswa belum
  mengajukan pada periode tersebut; tersedia judul berstatus "ditawarkan".
- **Pascakondisi:** Pengajuan tersimpan dengan `status = pending`; menunggu review
  Ka Lab; hasil dirahasiakan sampai pengumuman.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Pengajuan | 2. Memeriksa periode aktif & menampilkan form + daftar judul "ditawarkan" |
| 3. Memilih Pilihan 1, 2, 3 (judul berbeda) & mengisi alasan | |
| 4. (Opsional) Mengisi usulan mandiri + memilih dosen pembimbing | |
| 5. Menekan "Ajukan" | 6. Memvalidasi: ada periode aktif, 3 pilihan terisi & berbeda, belum pernah mengajukan, dosen pembimbing terisi bila ada usulan mandiri |
| | 7. Menyimpan pengajuan (`jenis` = mandiri bila usulan diisi, selain itu pilih) |
| | 8. Mengirim notifikasi ke dosen terkait & menampilkan pesan sukses |

**Skenario Alternatif**
- 6a. **Tidak ada periode aktif** → ditolak: "Belum ada periode aktif".
- 6b. **Sudah mengajukan di periode ini** → ditolak: "Anda sudah mengajukan…".
- 6c. **Pilihan tidak lengkap / duplikat** → validasi gagal (pilihan wajib & harus beda).
- 6d. **Usulan mandiri diisi tetapi dosen pembimbing kosong** → validasi gagal.

---

## UC-02 — Mahasiswa Melihat Beranda / Progress
- **Aktor:** Mahasiswa
- **Deskripsi:** Memantau progres pengajuan pada periode aktif secara ringkas.
- **Prakondisi:** Mahasiswa login.
- **Pascakondisi:** Status progres ditampilkan (tanpa membocorkan hasil sebelum
  pengumuman).

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka beranda | 2. Mengambil pengajuan periode aktif |
| | 3. Menampilkan progres: "Sedang Diproses" / hasil bila sudah diumumkan |

**Skenario Alternatif**
- 3a. Belum ada pengajuan → menampilkan ajakan untuk mengajukan.
- 3b. Hasil sudah final tetapi **belum diumumkan** → tetap "Sedang Diproses" (anti-bocor).

---

## UC-03 — Mahasiswa Melihat Riwayat Pengajuan
- **Aktor:** Mahasiswa
- **Deskripsi:** Melihat seluruh riwayat pengajuan lintas periode beserta keterangan
  judul yang diterima (termasuk bila berasal dari usulan mandiri).
- **Prakondisi:** Mahasiswa login.
- **Pascakondisi:** Daftar riwayat ditampilkan; detail dapat dibuka.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Riwayat | 2. Menampilkan timeline pengajuan + label periode |
| 3. Membuka detail salah satu pengajuan | 4. Menampilkan 3 pilihan, usulan mandiri, progres review, & **judul diterima + sumbernya** (bila sudah diumumkan) |

**Skenario Alternatif**
- 4a. Periode pengajuan **belum diumumkan** → hasil/keputusan disembunyikan.

---

## UC-04 — Dosen Mengelola Judul
- **Aktor:** Dosen
- **Deskripsi:** Dosen membuat judul (topik) dan mengajukannya ke Ka Lab untuk
  divalidasi; dapat menarik judul yang belum tervalidasi.
- **Prakondisi:** Dosen login. (Untuk "ajukan" perlu periode aktif.)
- **Pascakondisi:** Judul tersimpan dengan status sesuai aksi (`draft` /
  `pending_kalab`).

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Kelola Judul | 2. Menampilkan daftar judul (penanda Tersedia/Terkunci) |
| 3. Menambah judul baru (judul, deskripsi, lab) | 4. Menyimpan sebagai `draft` |
| 5. Menekan "Ajukan" pada judul | 6. Mengubah status menjadi `pending_kalab` & menotifikasi Ka Lab |

**Skenario Alternatif**
- 6a. Belum ada periode aktif → judul tetap `draft` (tidak bisa diajukan).
- 5b. Menarik judul yang masih `pending_kalab` → kembali ke `draft`.

---

## UC-05 — Dosen Memantau Pengajuan ke Judulnya
- **Aktor:** Dosen
- **Deskripsi:** Memantau (view-only) mahasiswa yang memilih judulnya; dapat memfilter
  per periode (riwayat). Dosen **tidak** memutuskan acc/tolak.
- **Prakondisi:** Dosen login.
- **Pascakondisi:** Daftar pengajuan yang melibatkan judulnya ditampilkan.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Pengajuan | 2. Menampilkan pengajuan periode aktif yang memilih judul dosen tsb |
| 3. Memilih periode lain pada filter | 4. Menampilkan data periode terpilih (riwayat) |

---

## UC-06 — Ka Lab Memvalidasi Judul
- **Aktor:** Kepala Laboratorium
- **Deskripsi:** Menyetujui (judul jadi "ditawarkan") atau menolak judul yang diajukan
  dosen.
- **Prakondisi:** Ka Lab login; terdapat judul berstatus `pending_kalab`.
- **Pascakondisi:** `status_judul` menjadi `ditawarkan` atau `ditolak_kalab`; dosen
  dinotifikasi; aksi tercatat di log.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Validasi Judul | 2. Menampilkan judul menunggu validasi |
| 3. Menyetujui judul (opsional catatan) | 4. Mengubah `status_judul` = `ditawarkan`, mencatat log, menotifikasi dosen |

**Skenario Alternatif**
- 3a. **Menolak** (catatan wajib) → `status_judul` = `ditolak_kalab` + notifikasi.

---

## UC-07 — Ka Lab Mereview Pengajuan & Menetapkan Judul  *(use case inti)*
- **Aktor:** Kepala Laboratorium
- **Deskripsi:** Ka Lab meninjau pengajuan mahasiswa dan **memilih satu judul yang
  ditetapkan**: salah satu dari 3 pilihan atau usulan mandiri. Bila usulan mandiri
  dipilih, sistem membuat judul katalog baru.
- **Prakondisi:** Ka Lab login; pengajuan berstatus menunggu (`status_kalab` = null);
  pengajuan berada pada periode aktif.
- **Pascakondisi:** `status_kalab` = disetujui/ditolak; bila disetujui,
  `judul_ditetapkan_id` & `sumber_judul` terisi; diteruskan ke Prodi.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka detail pengajuan | 2. Menampilkan data mahasiswa, 3 pilihan, usulan mandiri, & status ketersediaan tiap pilihan |
| 3. Memilih judul yang ditetapkan (pilihan 1/2/3 atau mandiri) + catatan | 4. Memvalidasi: judul belum diambil mahasiswa lain pada periode ini; bila mandiri, lab wajib dipilih |
| | 5. Bila mandiri: **membuat judul katalog baru** dari usulan |
| | 6. Menetapkan `status_kalab` = disetujui, `judul_ditetapkan_id`, `sumber_judul`; menotifikasi Prodi |

**Skenario Alternatif**
- 4a. **Judul sudah diambil mahasiswa lain (periode sama)** → ditolak: pilih alternatif.
- 4b. **Sumber = mandiri tetapi lab kosong** → validasi gagal.
- 3c. **Ka Lab menolak pengajuan** (catatan wajib) → `status_kalab` = ditolak.
- 2d. Pengajuan berada di **periode arsip** → tidak dapat diproses.

---

## UC-08 — Prodi Memberi Keputusan Final
- **Aktor:** Program Studi / Kaprodi
- **Deskripsi:** Mengambil keputusan final atas pengajuan yang telah disetujui Ka Lab.
- **Prakondisi:** Prodi login; pengajuan `status_kalab` = disetujui & `status_kaprodi`
  = null; periode aktif.
- **Pascakondisi:** `status_kaprodi` = disetujui/ditolak; bila final disetujui, judul
  dikunci (`is_locked` = true).

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka daftar pengajuan menunggu keputusan | 2. Menampilkan pengajuan + **judul yang ditetapkan Ka Lab** |
| 3. Menyetujui (final) | 4. `status_kaprodi` = disetujui, mengunci judul |

**Skenario Alternatif**
- 3a. **Menolak** (catatan) → `status_kaprodi` = ditolak.
- 2b. Pengajuan periode arsip → tidak dapat diproses.

---

## UC-09 — Koordinator TA Mengelola Periode
- **Aktor:** Koordinator TA
- **Deskripsi:** Membuat, mengaktifkan, dan menutup periode. Hanya satu periode aktif.
  Mengaktifkan periode = memulai siklus & menyinkronkan ulang aktivitas.
- **Prakondisi:** Koordinator TA login.
- **Pascakondisi:** Satu periode aktif; kunci judul dihitung ulang sesuai periode itu;
  notifikasi dibersihkan; data periode lama tetap tersimpan sebagai riwayat.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Periode | 2. Menampilkan daftar periode |
| 3. Membuat / mengaktifkan sebuah periode | 4. Menonaktifkan periode lain |
| | 5. Menghitung ulang kunci judul untuk periode aktif |
| | 6. Membersihkan notifikasi (reset) |

**Skenario Alternatif**
- 3a. Mengaktifkan **periode lama** → judul yang dulu ditetapkan di periode itu terkunci
  kembali (riwayat penetapan tidak hilang).

---

## UC-10 — Koordinator TA Mengirim Pengumuman (Broadcast)
- **Aktor:** Koordinator TA
- **Deskripsi:** Mempublikasikan hasil penetapan per periode; menjadi gerbang
  transparansi (hasil baru terlihat mahasiswa setelah ini).
- **Prakondisi:** Koordinator TA login; ada pengumuman (draft) untuk periode terkait
  yang belum dikirim.
- **Pascakondisi:** `dikirim_at` terisi; semua pengguna dinotifikasi; mahasiswa dapat
  melihat hasil.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuat pengumuman (judul, isi, periode) | 2. Menyimpan sebagai draft (`dikirim_at` = null) |
| 3. Menekan "Kirim" (broadcast) | 4. Mengisi `dikirim_at`, menotifikasi semua pengguna |
| | 5. Membuka akses hasil bagi mahasiswa periode tsb |

**Skenario Alternatif**
- 3a. Pengumuman sudah pernah dikirim → tidak dapat dikirim ulang.
- 4b. Halaman detail pengumuman yang **belum dikirim** tidak dapat dibuka (anti-bocor).

---

## UC-11 — Koordinator TA Mengelola Pengguna
- **Aktor:** Koordinator TA
- **Deskripsi:** Mengedit data pengguna, mereset password, atau menghapus pengguna.
- **Prakondisi:** Koordinator TA login.
- **Pascakondisi:** Data pengguna diperbarui.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Pengguna | 2. Menampilkan daftar pengguna |
| 3. Mengedit / reset password / hapus | 4. Menyimpan perubahan & menampilkan konfirmasi |

---

## UC-12 — Monitoring & Ekspor (Koordinator TA / Prodi)
- **Aktor:** Koordinator TA (utama), Prodi
- **Deskripsi:** Memantau rekap pengajuan & judul lintas periode dan mengekspor data
  (Excel/PDF).
- **Prakondisi:** Aktor login.
- **Pascakondisi:** Data rekap ditampilkan / berkas ekspor terunduh.

**Skenario Utama**
| Aktor | Sistem |
|---|---|
| 1. Membuka menu Monitoring | 2. Menampilkan statistik + daftar (dengan filter periode) |
| 3. Memilih ekspor Excel/PDF | 4. Membangkitkan & mengunduh berkas |

---

## Ringkasan pemetaan use case ↔ diagram
| Use Case | Diagram pendukung |
|---|---|
| UC-01 | Sequence (E), Activity (D), State (F) |
| UC-07 | Activity (D) — cabang "sumber = mandiri" |
| UC-08 | State (F) — transisi DisetujuiFinal |
| UC-09 | Konsep periode scoping (Konteks §6) |
| UC-10 | State (F) — transisi "Diumumkan" |

---

*Akhir dokumen skenario use case.*
