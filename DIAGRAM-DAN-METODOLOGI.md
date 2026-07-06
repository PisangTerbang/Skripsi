# Diagram & Metodologi (Prototyping) — Bahan Jurnal

> Pendamping [KONTEKS-PROYEK.md](KONTEKS-PROYEK.md). Berisi diagram siap-render
> (PlantUML) + uraian metode **Prototyping**. Salin ke claude.ai bersama konteks.
>
> **Cara render diagram:** tempel blok `@startuml … @enduml` ke
> https://www.plantuml.com/plantuml (atau ekstensi "PlantUML" di VS Code, atau
> minta claude.ai mengubahnya jadi gambar/Mermaid). Semua diagram di bawah sudah
> sesuai struktur kode & basis data nyata.

---

## A. Metodologi: Prototyping

### A.1 Alasan pemilihan
Metode **Prototyping** dipilih karena kebutuhan tiap peran (mahasiswa, dosen, Ka Lab,
Prodi, Koordinator TA) belum sepenuhnya terdefinisi di awal dan banyak melibatkan
**alur keputusan berjenjang** serta **aturan bisnis** yang baru jelas setelah pengguna
mencoba sistem. Prototyping memungkinkan kebutuhan disempurnakan secara iteratif
melalui purwarupa yang berulang kali dievaluasi.

### A.2 Tahapan Prototyping (model klasik)
1. **Pengumpulan kebutuhan (Listen to Customer)** — wawancara/observasi kebutuhan
   pengelolaan judul TA; identifikasi peran & alur.
2. **Perancangan & pembangunan purwarupa (Build/Revise Prototype)** — membangun
   purwarupa antarmuka + alur kerja (Laravel, Blade, PostgreSQL).
3. **Evaluasi purwarupa (Customer Test-Drive)** — pengguna mencoba; masukan dicatat;
   kembali ke tahap 2 sampai sesuai.
4. **Implementasi & pengujian akhir** — purwarupa yang disetujui dimatangkan dan diuji
   (black-box / UAT).

### A.3 Iterasi nyata yang terjadi (bukti penerapan prototyping)
Tabel ini memperlihatkan siklus purwarupa→evaluasi→perbaikan yang benar-benar
dilakukan — sangat kuat untuk bagian Metodologi/Pembahasan.

| Iterasi | Evaluasi pengguna (masukan) | Perbaikan purwarupa |
|---|---|---|
| 1 | Konsep "kuota" bimbingan ribet (perlu tambah/kurang manual) | Diganti **jumlah mahasiswa dibimbing** (dihitung otomatis) |
| 2 | Fitur chat konsultasi tak terpakai | Dihapus dari sistem |
| 3 | Aktivitas perlu terpusat per semester | **Fitur periode dimaksimalkan**: semua aktivitas dinaungi 1 periode aktif + arsip |
| 4 | Manajemen judul dosen terpecah & membingungkan | **Satu daftar** dengan penanda Tersedia/Terkunci |
| 5 | Hasil bocor sebelum pengumuman resmi | **Mekanisme anti-bocor**: hasil dirahasiakan s/d pengumuman |
| 6 | Saat ganti periode, data/kunci/notifikasi tidak konsisten | **Reset per-periode** (kunci judul dihitung ulang, notifikasi dibersihkan), riwayat tetap tersimpan |
| 7 | Dashboard kurang informatif | **Visualisasi data** (grafik) untuk dosen, Ka Lab, Prodi |
| 8 | Riwayat ambigu untuk usulan mandiri | Penanda **"judul diterima"** + sumbernya (pilihan/mandiri) |

> Narasi: tiap baris = satu siklus "purwarupa dievaluasi → diperbaiki", konsisten
> dengan sifat iteratif prototyping.

### A.4 Diagram alur metode (PlantUML)
```plantuml
@startuml
start
:Pengumpulan kebutuhan\n(identifikasi 5 peran & alur TA);
repeat
  :Bangun / perbaiki purwarupa\n(antarmuka + alur kerja);
  :Evaluasi purwarupa oleh pengguna;
repeat while (Sesuai kebutuhan?) is (Belum) not (Ya)
:Implementasi & pengujian akhir\n(black-box / UAT);
:Sistem siap digunakan;
stop
@enduml
```

---

## B. Use Case Diagram

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle
skinparam shadowing false
skinparam nodesep 8
skinparam ranksep 65

' ===== AKTOR SISI KIRI =====
actor "Mahasiswa" as MHS
actor "Dosen" as DSN

rectangle "Sistem Pengelolaan Judul Tugas Akhir" {
  ' --- Dipakai semua peran ---
  usecase "Login / Autentikasi" as UC0
  usecase "Kelola Profil"        as UCP
  usecase "Notifikasi"           as UCN

  ' --- Mahasiswa ---
  usecase "Ajukan Judul TA\n(3 pilihan + usulan mandiri)" as UC1
  usecase "Lihat Beranda / Progress" as UC2
  usecase "Lihat Riwayat Pengajuan"  as UC3

  ' --- Dosen ---
  usecase "Kelola Judul\n(buat/ajukan/tarik)" as UC4
  usecase "Pantau Pengajuan ke Judulnya" as UC5

  ' --- Kepala Lab ---
  usecase "Validasi Judul Dosen" as UC6
  usecase "Review Pengajuan &\nTetapkan Judul" as UC7

  ' --- Prodi ---
  usecase "Keputusan Final Pengajuan" as UC8
  usecase "Monitoring & Riwayat" as UC9

  ' --- Koordinator TA ---
  usecase "Kelola Periode\n(buka/aktifkan/tutup)" as UC10
  usecase "Kelola Pengguna" as UC11
  usecase "Kirim Pengumuman\n(broadcast)" as UC12
  usecase "Monitoring Lintas Periode" as UC13
  usecase "Ekspor Data (Excel/PDF)" as UC14
}

' ===== AKTOR SISI KANAN =====
actor "Kepala Lab" as KALAB
actor "Prodi/Kaprodi" as PRODI
actor "Koordinator TA" as KOOR

' --- Asosiasi aktor KIRI: aktor -- usecase (digambar dari kiri) ---
MHS -- UC0
MHS -- UCP
MHS -- UCN
MHS -- UC1
MHS -- UC2
MHS -- UC3
DSN -- UC0
DSN -- UCP
DSN -- UCN
DSN -- UC4
DSN -- UC5

' --- Asosiasi aktor KANAN: usecase -- aktor (didorong ke kanan) ---
UC0 -- KALAB
UCP -- KALAB
UCN -- KALAB
UC6 -- KALAB
UC7 -- KALAB
UC0 -- PRODI
UCP -- PRODI
UCN -- PRODI
UC8 -- PRODI
UC9 -- PRODI
UC0 -- KOOR
UCP -- KOOR
UCN -- KOOR
UC10 -- KOOR
UC11 -- KOOR
UC12 -- KOOR
UC13 -- KOOR
UC14 -- KOOR
@enduml
```

**Catatan relasi penting (untuk narasi):** UC1 (ajukan) hanya bisa saat ada periode
aktif (UC10). UC7 (tetapkan judul) `<<include>>` pembuatan judul katalog bila yang
dipilih adalah usulan mandiri. Hasil baru terlihat mahasiswa setelah UC12 (pengumuman).

---

## C. Entity Relationship Diagram (ERD)

Sesuai foreign key nyata di basis data PostgreSQL. Disajikan dua tingkat:
**C.1 Konseptual** (gambar utama, bersih) dan **C.2 Fisik** (lampiran, lengkap atribut).

### C.1 ERD Konseptual (entitas + relasi)

Hanya menampilkan entitas dan relasinya — untuk gambaran besar yang mudah dibaca.

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam nodesep 40
skinparam ranksep 70
skinparam defaultFontName "Arial"
hide circle
hide empty members

entity "laboratorium" as lab {}
entity "users" as users {}
entity "periode" as periode {}
entity "judul" as judul {}
entity "pengajuan" as pengajuan {}
entity "pengumuman" as pengumuman {}
entity "judul_logs" as logs {}
entity "aktivitas" as aktivitas {}

lab     ||--o{ users      : menaungi
lab     ||--o{ judul      : memiliki
users   ||--o{ judul      : menawarkan
users   ||--o{ pengajuan  : mengajukan
periode ||--o{ pengajuan  : menaungi
periode ||--o{ pengumuman : memiliki
users   ||--o{ pengumuman : membuat
judul   ||--o{ pengajuan  : "dipilih / ditetapkan"
judul   ||--o{ logs       : dicatat
users   ||--o{ logs       : pelaku
users   ||--o{ aktivitas  : menerima
@enduml
```

### C.2 ERD Fisik (lengkap dengan atribut & tipe)

Versi rinci untuk lampiran. Kolom timestamp bawaan (`created_at`/`updated_at`) dan
kolom legacy (`judul_id`, `prioritas`, `alasan`) sengaja tidak digambar agar ringkas.

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam nodesep 28
skinparam ranksep 65
skinparam defaultFontName "Arial"
hide circle

entity "laboratorium" as lab {
  * id : bigint <<PK>>
  --
  nama : varchar
  deskripsi : text
}

entity "users" as users {
  * id : bigint <<PK>>
  --
  name : varchar
  email : varchar
  password : varchar
  role : varchar
  nim : varchar
  avatar : varchar
  laboratorium_id : bigint <<FK>>
}

entity "periode" as periode {
  * id : bigint <<PK>>
  --
  nama : varchar
  tanggal_buka : date
  tanggal_tutup : date
  is_active : boolean
  ditutup : boolean
}

entity "judul" as judul {
  * id : bigint <<PK>>
  --
  kode : varchar
  nama_judul : varchar
  deskripsi : text
  status_judul : varchar
  is_locked : boolean
  aktif : boolean
  dosen_id : bigint <<FK>>
  laboratorium_id : bigint <<FK>>
  reviewed_by_kalab : bigint <<FK>>
}

entity "pengajuan" as pengajuan {
  * id : bigint <<PK>>
  --
  mahasiswa_id : bigint <<FK>>
  periode_id : bigint <<FK>>
  jenis : varchar
  status : varchar
  status_kalab : varchar
  status_kaprodi : varchar
  pilihan_1_id : bigint <<FK>>
  pilihan_2_id : bigint <<FK>>
  pilihan_3_id : bigint <<FK>>
  alasan_1 : text
  alasan_2 : text
  alasan_3 : text
  judul_mandiri : varchar
  deskripsi_mandiri : text
  dosen_pembimbing_id : bigint <<FK>>
  judul_ditetapkan_id : bigint <<FK>>
  sumber_judul : varchar
  reviewed_by_kalab : bigint <<FK>>
  reviewed_by_kaprodi : bigint <<FK>>
}

entity "pengumuman" as pengumuman {
  * id : bigint <<PK>>
  --
  periode_id : bigint <<FK>>
  dibuat_oleh : bigint <<FK>>
  judul : varchar
  isi : text
  dikirim_at : timestamp
}

entity "judul_logs" as logs {
  * id : bigint <<PK>>
  --
  judul_id : bigint <<FK>>
  user_id : bigint <<FK>>
  aksi : varchar
  dari_status : varchar
  ke_status : varchar
}

entity "aktivitas" as aktivitas {
  * id : bigint <<PK>>
  --
  user_id : bigint <<FK>>
  tipe : varchar
  pesan : text
  link : varchar
  is_read : boolean
}

lab     ||--o{ users
lab     ||--o{ judul
users   ||--o{ judul
periode ||--o{ pengajuan
periode ||--o{ pengumuman
users   ||--o{ pengajuan
users   ||--o{ pengumuman
judul   ||--o{ pengajuan
judul   ||--o{ logs
users   ||--o{ logs
users   ||--o{ aktivitas
@enduml
```

> **Nilai enumerasi (untuk keterangan di bawah gambar):**
> `users.role` = mahasiswa | dosen | ka_lab | prodi | koordinator_ta ·
> `judul.status_judul` = draft | pending_kalab | ditawarkan | ditolak_kalab ·
> `pengajuan.status` = pending | disetujui | ditolak ·
> `pengajuan.status_kalab` / `status_kaprodi` = null (menunggu) | disetujui | ditolak ·
> `pengajuan.jenis` = pilih | mandiri ·
> `pengajuan.sumber_judul` = pilihan_1 | pilihan_2 | pilihan_3 | mandiri.
>
> **Kardinalitas:** semua relasi **1 : N**. Entitas `pengajuan` mengacu ke `judul`
> melalui 4 FK (pilihan_1/2/3 + judul_ditetapkan) — di C.1 diringkas jadi satu garis
> "dipilih / ditetapkan".

---

## D. Activity Diagram — Alur Utama Pengajuan s/d Pengumuman

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam defaultFontName "Arial"

|Koordinator TA|
start
:Buka & aktifkan Periode;

|Dosen|
:Buat judul (status = draft);
:Ajukan judul ke Ka Lab\n(status = pending_kalab);

|Kepala Lab|
:Tinjau judul dari dosen;
if (Judul layak ditawarkan?) then (ya)
  :status_judul = ditawarkan;
else (tidak)
  :status_judul = ditolak_kalab;
endif

|Mahasiswa|
:Lihat daftar judul "ditawarkan";
:Isi pengajuan:\n3 pilihan judul (wajib, berbeda)\n+ usulan mandiri (opsional);
if (Pengajuan valid?\n(periode aktif, 3 pilihan beda,\nbelum pernah mengajukan)) then (tidak)
  :Tampilkan pesan kesalahan;
  stop
else (ya)
  :Simpan pengajuan (status = pending);
endif

|Kepala Lab|
:Review pengajuan mahasiswa;
if (Disetujui Ka Lab?) then (ya)
  :Tetapkan judul\n(pilihan 1 / 2 / 3 atau mandiri);
  if (Sumber = usulan mandiri?) then (ya)
    :Buat judul katalog baru\ndari usulan mandiri;
  else (tidak — dari pilihan)
  endif
  :status_kalab = disetujui\n(set judul_ditetapkan_id, sumber_judul);
else (tidak)
  :status_kalab = ditolak;
endif

|Prodi / Kaprodi|
if (status_kalab = disetujui?) then (ya)
  :Tinjau untuk keputusan akhir;
  if (Disetujui final?) then (ya)
    :status_kaprodi = disetujui;
    :Kunci judul (is_locked = true);
  else (tidak)
    :status_kaprodi = ditolak;
  endif
else (tidak / ditolak Ka Lab)
endif

|Koordinator TA|
:Kirim Pengumuman (broadcast)\nper periode;

|Mahasiswa|
:Lihat hasil resmi\n(disetujui / ditolak + judul diterima);
stop
@enduml
```

---

## E. Sequence Diagram — Mahasiswa Mengajukan Judul

```plantuml
@startuml
actor Mahasiswa
participant "Antarmuka\n(Blade/Alpine)" as UI
participant "PengajuanController" as CTRL
participant "Model Periode" as PER
participant "Model Pengajuan" as PNG
database "PostgreSQL" as DB

Mahasiswa -> UI : Buka form pengajuan
UI -> CTRL : GET pengajuan
CTRL -> PER : periodeAktif()
PER -> DB : SELECT periode WHERE is_active
DB --> PER : periode aktif
CTRL --> UI : Tampilkan judul "ditawarkan" + form
Mahasiswa -> UI : Isi 3 pilihan (+ mandiri), submit
UI -> CTRL : POST pengajuan.store
CTRL -> CTRL : Validasi (3 pilihan beda, gate periode,\n1x per periode)
CTRL -> PNG : create(pengajuan)
PNG -> DB : INSERT pengajuan (status=pending)
DB --> PNG : ok
CTRL --> UI : Notifikasi sukses\n(hasil dirahasiakan s/d pengumuman)
UI --> Mahasiswa : "Pengajuan terkirim"
@enduml
```

---

## F. Diagram State — Status Pengajuan

```plantuml
@startuml
[*] --> Pending : Mahasiswa submit
Pending --> DitolakKaLab : Ka Lab menolak
Pending --> DisetujuiKaLab : Ka Lab acc + tetapkan judul
DisetujuiKaLab --> DitolakKaprodi : Prodi menolak
DisetujuiKaLab --> DisetujuiFinal : Prodi setujui (judul dikunci)
DitolakKaLab --> [*]
DitolakKaprodi --> Diumumkan : Koordinator broadcast
DisetujuiFinal --> Diumumkan : Koordinator broadcast
Diumumkan --> [*]

note right of Pending
  Hasil dirahasiakan dari
  mahasiswa s/d "Diumumkan"
end note
@enduml
```

---

## G. Saran isi bagian "Pengujian" (Black-box)

Contoh tabel uji yang bisa langsung dipakai (lengkapi kolom Hasil):

| No | Skenario | Masukan | Hasil yang diharapkan |
|---|---|---|---|
| 1 | Pengajuan tanpa periode aktif | submit pengajuan | Ditolak: "Belum ada periode aktif" |
| 2 | Pengajuan < 3 pilihan | 2 pilihan | Validasi gagal: pilihan 3 wajib |
| 3 | Pilihan duplikat | pilihan_1 = pilihan_2 | Validasi gagal: harus berbeda |
| 4 | Pengajuan kedua di periode sama | submit lagi | Ditolak: sudah mengajukan |
| 5 | Mahasiswa lihat hasil sebelum pengumuman | buka beranda | Status "sedang diproses" (anti-bocor) |
| 6 | Ka Lab acc usulan mandiri | pilih sumber=mandiri | Judul katalog baru dibuat & ditetapkan |
| 7 | Judul sudah diambil mahasiswa lain | Ka Lab pilih judul terkunci | Ditolak: judul sudah diambil |
| 8 | Ganti periode aktif | aktifkan periode lain | Aktivitas reset, riwayat tetap ada |
| 9 | Pengumuman dikirim | broadcast | Hasil terlihat mahasiswa |

---

*Akhir dokumen diagram & metodologi.*
