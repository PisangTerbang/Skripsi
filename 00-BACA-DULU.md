# 📌 BACA DULU — Paket Konteks Proyek untuk Penulisan Jurnal

Berkas ini **pintu masuk** seluruh konteks proyek. Tujuannya: memudahkan menulis
jurnal/laporan di chat lain (mis. claude.ai) dengan menyalin konteks yang lengkap,
akurat, dan bermakna.

---

## 1. Apa sistem ini? (primer 1 paragraf — siap salin)

> Proyek ini adalah **Sistem Informasi Pengelolaan Pengajuan & Penetapan Judul Tugas
> Akhir (Skripsi) berbasis web**, dibangun dengan **Laravel 12, PostgreSQL (Supabase),
> Blade + Alpine.js + Tailwind CSS, dan Chart.js**, menggunakan metode pengembangan
> **Prototyping**. Sistem melibatkan **5 peran** (Mahasiswa, Dosen, Kepala Lab,
> Program Studi/Kaprodi, Koordinator TA) dengan **alur persetujuan berjenjang**:
> dosen menawarkan judul → Ka Lab memvalidasi → mahasiswa mengajukan (wajib 3 pilihan
> judul + opsional usulan mandiri) → Ka Lab menetapkan judul → Prodi memutuskan final
> → Koordinator TA mengumumkan hasil. Seluruh aktivitas **dinaungi satu periode aktif**
> per semester yang dikontrol Koordinator TA (aktivitas reset tiap ganti periode,
> riwayat tetap tersimpan), dan **hasil dirahasiakan dari mahasiswa hingga pengumuman
> resmi** (mekanisme anti-bocor).

## 2. Kebaruan / kontribusi (untuk ditonjolkan di jurnal)
Tiga hal yang membedakan dari sistem pengajuan TA umum:
1. **Scoping berbasis periode** — semua aktivitas dinaungi satu periode aktif & reset
   terkendali tiap siklus, tanpa kehilangan riwayat.
2. **Anti-bocor** — kerahasiaan hasil keputusan sampai pengumuman resmi.
3. **Penetapan judul berjenjang + usulan mandiri** — mahasiswa wajib 3 pilihan,
   opsional mengusulkan judul sendiri; Ka Lab menetapkan sumber judul; Prodi final.

---

## 3. Isi paket konteks (4 berkas)

| Berkas | Isi | Dipakai untuk bab |
|---|---|---|
| **[KONTEKS-PROYEK.md](KONTEKS-PROYEK.md)** | Ikhtisar, tech stack, peran, entitas, alur, prinsip arsitektur, fitur, glosarium, **+ kerangka jurnal** | Pendahuluan, Landasan teori, Pembahasan |
| **[DIAGRAM-DAN-METODOLOGI.md](DIAGRAM-DAN-METODOLOGI.md)** | **Metode Prototyping** + diagram PlantUML (Use Case, ERD, Activity, Sequence, State) + tabel uji | Metodologi, Perancangan, Pengujian |
| **[SKENARIO-USE-CASE.md](SKENARIO-USE-CASE.md)** | 13 skenario use case (aktor, prakondisi, alur utama & alternatif) | Perancangan |
| **[KAMUS-DATA-DAN-KEBUTUHAN.md](KAMUS-DATA-DAN-KEBUTUHAN.md)** | Kamus data per tabel + kebutuhan fungsional (FR) & non-fungsional (NFR) | Analisis kebutuhan, Perancangan basis data |

---

## 4. Cara pakai dengan claude.ai (langkah demi langkah)

1. **Buka berkas yang relevan** dengan bab yang sedang ditulis (lihat tabel §3),
   salin **seluruh isinya**.
2. **Mulai sesi** di claude.ai dengan kalimat pembuka, lalu tempel konteks. Contoh:
   > "Saya menulis jurnal informatika tentang proyek skripsi saya (metode Prototyping).
   > Berikut konteks lengkapnya: [tempel isi berkas]. Tolong bantu saya menulis
   > **[Abstrak / Bab I Pendahuluan / Bab III Metodologi / dst.]** dalam bahasa
   > akademik formal, gaya jurnal Indonesia."
3. **Kerjakan per bagian**, jangan sekaligus — hasil lebih fokus & mudah direvisi.
4. Untuk **diagram**: tempel blok `@startuml … @enduml` ke
   https://www.plantuml.com/plantuml (atau minta claude.ai mengubahnya ke Mermaid/gambar).
5. **Siapkan sendiri** (tidak ada di kode): 3–5 jurnal pembanding untuk Tinjauan
   Pustaka, dan screenshot antarmuka untuk Hasil & Pembahasan.

## 5. Urutan menulis yang disarankan
Metodologi (§A Prototyping) → Perancangan (Use Case, ERD, Activity) → Analisis
Kebutuhan (FR/NFR) → Hasil & Pembahasan (fitur kunci + screenshot + pengujian) →
Pendahuluan → Abstrak → Kesimpulan. (Pendahuluan & Abstrak ditulis belakangan agar
selaras dengan isi.)

---

## 6. Fakta cepat (untuk akurasi saat menulis)
- **Role di basis data:** `mahasiswa`, `dosen`, `ka_lab`, `prodi`, `koordinator_ta`.
- **Status pengajuan:** `status` (pending/disetujui/ditolak), `status_kalab`,
  `status_kaprodi` (null = menunggu).
- **Status judul:** `draft → pending_kalab → ditawarkan / ditolak_kalab`.
- **Sumber judul ditetapkan:** `pilihan_1` / `pilihan_2` / `pilihan_3` / `mandiri`.
- **Gerbang transparansi:** `pengumuman.dikirim_at` (terisi = hasil boleh dilihat).
- **8 tabel inti:** users, laboratorium, periode, judul, pengajuan, pengumuman,
  aktivitas, judul_logs.

---

*Mulai dari berkas yang sesuai babmu, atau salin seluruh paket bila ingin konteks penuh.*
