# Checklist Testing per Role — Sistem Skripsi

> Ikuti urutan ini (beberapa langkah saling bergantung). Centang `[x]` jika lolos.
> **Password semua akun:** `password`

## 0. Persiapan
- [ ] Jalankan app (mis. `php artisan serve` → http://127.0.0.1:8000, atau `skripsi1.test`)
- [ ] Pastikan periode aktif ada → **Semester Genap 2025/2026** ✅ (sudah aktif)

### Akun login
| Role | Email | Nama |
|------|-------|------|
| Koordinator TA | `koordinatorta@informatika.com` | Admin Koordinator TA |
| Ka Lab | `kalab@informatika.com` | Dr. Ahmad Fauzi |
| Prodi | `prodi@informatika.com` | Dr. Budi Santoso |
| Dosen | `srimulyati@dosen.com` | Sri Mulyati |
| Mahasiswa | `mhs1@mail.com` (Andi) | NIM 22523001 |

---

## 1. 🔴 Alur Judul Dosen (BARU — prioritas utama)
Login **Dosen** (`srimulyati@dosen.com`) → menu **Manajemen Judul**
- [ ] **Tambah Judul** → pilih **"Draft"** → simpan → kartu muncul badge **Draft** (abu-abu)
- [ ] **Tambah Judul** → pilih **"Ajukan ke Ka Lab"** → simpan → badge **Menunggu Validasi** (kuning)
- [ ] Pada judul **Draft** → klik **Ajukan** → badge berubah jadi **Menunggu Validasi**
- [ ] Pada judul **Menunggu Validasi** → klik **Tarik** → kembali jadi **Draft**
- [ ] Kartu statistik atas: angka **Draft / Menunggu Validasi / Ditawarkan** sesuai
- [ ] Filter pills (Draft/Menunggu/Ditawarkan/Ditolak) menyaring dengan benar

Login **Ka Lab** (`kalab@informatika.com`) → menu **Validasi Judul**
- [ ] Judul yang tadi diajukan **muncul** di antrian (sebelumnya selalu kosong)
- [ ] Klik **Setujui/Validasi** → status jadi **Ditawarkan**
- [ ] (ulangi 1 judul) Klik **Tolak** + isi catatan → judul jadi **Ditolak**

Login **Dosen** lagi
- [ ] Judul yang divalidasi → badge **Ditawarkan** (hijau)
- [ ] Judul yang ditolak → badge **Ditolak Ka Lab** (merah) + tombol **Ajukan Ulang**

Login **Mahasiswa** (`mhs1@mail.com`) → menu **Pengajuan**
- [ ] Judul baru yang **Ditawarkan** tadi **muncul** di daftar pilihan judul

---

## 2. 🔵 Alur Pengajuan Mahasiswa (end-to-end)
Login **Mahasiswa** (`mhs2@mail.com` / Budi) → **Pengajuan**
- [ ] Pilih **3 judul** (pilihan 1/2/3 berbeda) + isi alasan → **Kirim**
- [ ] Kartu pengajuan muncul status **"Menunggu Review"** — **hanya satu** indikator status (tidak ada badge ganda)

Login **Dosen** pemilik salah satu judul → **Pengajuan Mahasiswa**
- [ ] Pengajuan Budi **terlihat** (karena memilih judul dosen ini)
- [ ] Halaman bersifat **view-only**: **tidak ada** tombol Setujui/Tolak; ada teks "Menunggu keputusan Ka Lab & Prodi"
- [ ] Judul & badge "Pilihan ke-N" tampil benar

Login **Ka Lab** → **Pengajuan**
- [ ] Pengajuan Budi muncul (Belum Review) → buka detail → **tetapkan judul** (pilih salah satu) → **Setujui**

Login **Prodi** (`prodi@informatika.com`) → **Review Pengajuan**
- [ ] Pengajuan Budi **muncul** di daftar (dulu kosong karena bug Koor Lab — pastikan ada)
- [ ] Tabel **tidak ada** kolom "Status Koor"
- [ ] Buka detail → **Setujui** (atau Tolak + catatan)

Login **Koordinator TA** (`koordinatorta@informatika.com`) → **Pengumuman**
- [ ] Buat pengumuman (pilih periode) → **Broadcast/Kirim**
- [ ] Muncul info "berhasil dikirim ke N mahasiswa"

Login **Mahasiswa** (Budi) → **Notifikasi** / **Riwayat**
- [ ] Ada notifikasi hasil (disetujui/ditolak) setelah pengumuman
- [ ] Riwayat menampilkan progress + judul ditetapkan

---

## 3. 🟣 Judul Mandiri + Dosen Pembimbing (Gap A)
Login **Mahasiswa** (`mhs3@mail.com` / Citra) → **Pengajuan**
- [ ] Isi **judul mandiri** + deskripsi → **wajib pilih Dosen Pembimbing** (mis. Sri Mulyati) → tetap pilih 3 judul → Kirim

Login **Ka Lab** → **Pengajuan**
- [ ] Buka pengajuan Citra → setujui dengan sumber **"mandiri"** → **pilih Laboratorium**

Verifikasi atribusi dosen:
- [ ] Login **Dosen** (Sri Mulyati) → **Manajemen Judul** → judul mandiri Citra muncul dengan **dosen = Sri Mulyati** (bukan kosong)
- [ ] Atau cek di Prodi/Ka Lab detail: dosen judul mandiri **terisi**, bukan "-"

---

## 4. 🟢 Kuota Bimbingan Dosen
Login **Dosen** (`srimulyati@dosen.com`) → **Pengaturan**
- [ ] Ada field **"Kuota Mahasiswa Bimbingan"** + info "saat ini membimbing N mahasiswa"
- [ ] Isi angka (mis. 8) → **Simpan** → reload → nilai tersimpan

Login **Ka Lab** → **Pengajuan** → buka detail pengajuan yang memilih judul Sri Mulyati
- [ ] Tampil **"Kuota: x/8"** di bawah nama dosen

Login **Prodi** → detail pengajuan dengan judul ditetapkan milik Sri Mulyati
- [ ] Tampil **"Kuota bimbingan: x/8"**

---

## 5. 💬 Konsultasi (Chat)
Login **Mahasiswa** → **Konsultasi**
- [ ] Pilih seorang dosen → kirim pesan teks → muncul di chat
- [ ] Kirim **kartu judul** (judul dosen tsb) → muncul sebagai kartu

Login **Dosen** → **Konsultasi**
- [ ] Percakapan dari mahasiswa muncul → balas pesan → mahasiswa menerima (polling)

---

## 6. 🎨 Cek Tampilan (UI)
- [ ] Sidebar **setiap role** menampilkan brand **"Sistem Skripsi"** + subtitle **"Panel (Role)"**
  - Mahasiswa: Panel Mahasiswa · Dosen: Panel Dosen · Ka Lab: Panel Kepala Lab · Prodi: Panel Program Studi · Koor TA: Panel Koordinator TA
- [ ] Tab browser (title): "... - Sistem Skripsi" di semua role

---

## Catatan
- Jika ada langkah gagal/aneh, catat **role + halaman + langkah**-nya, lalu beri tahu untuk diperbaiki.
- Belum diuji = perilaku yang sengaja ditunda: penguncian judul mandiri & gating periode by-tanggal.
