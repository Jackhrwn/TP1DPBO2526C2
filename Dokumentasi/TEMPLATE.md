# Template Dokumentasi — Manajemen Bioskop

Dokumen ini adalah **template** tempat mencatat/mengumpulkan bukti dokumentasi program untuk keempat bahasa.
Salin isi file ini untuk masing-masing bahasa, atau langsung gunakan daftar ini sebagai panduan saat mengambil screenshot/screenrecord.

---

## Letak File Bukti

Simpan screenshot/screenrecord sesuai folder bahasanya:

| Bahasa | Folder Tempat |
|--------|---------------|
| C++ | `Dokumentasi/CPP/` |
| Python | `Dokumentasi/Python/` |
| Java | `Dokumentasi/Java/` |
| PHP | `Dokumentasi/PHP/` |

### Aturan Penamaan File

Gunakan nama yang jelas dan seragam, contoh:

- `MENGGUNAKAN CPP.png`
- `MENGGUNAKAN Python.png`
- `MENGGUNAKAN Java.png`
- `MENGGUNAKAN PHP.png`

Atau gunakan penomoran skenario agar urutannya jelas:

- `01-tambah-sukses.png`, `02-error-id-tidak-valid.png`, dst.

Format yang dapat diterima: `.png`, `.jpg`, `.gif`, `.mp4`.

---

## Checklist Bukti — Bahasa CLI (C++, Python, Java)

Centang setiap skenario yang sudah didokumentasikan:

- [ ] Tampilan menu utama
- [ ] Tambah data berhasil
- [ ] Error: input bukan angka (menu / ID / harga / durasi)
- [ ] Error: ID bukan angka mulai dari 1
- [ ] Error: ID sudah digunakan
- [ ] Error: genre diawali huruf kecil
- [ ] Tampilkan semua data (format jam untuk durasi ≥ 60 menit)
- [ ] Update salah satu kolom (submenu pilihan kolom)
- [ ] Error: ID tidak ditemukan saat update
- [ ] Hapus data berhasil
- [ ] Cari data ditemukan
- [ ] Cari data tidak ditemukan (pesan `Film dengan ID 'X' tidak ditemukan!`)
- [ ] Tampilkan data saat list kosong
- [ ] Keluar program

## Checklist Bukti — Bahasa PHP (Web)

- [ ] Tampilan halaman utama
- [ ] Form tambah data + pesan sukses hijau
- [ ] Error: ID sudah digunakan
- [ ] Error: harga/durasi bukan angka
- [ ] Error: genre huruf kecil
- [ ] Error: gambar diisi URL (harus path lokal)
- [ ] Mode edit (form terisi data lama) + berhasil menyimpan + keluar dari mode edit
- [ ] Tombol "Batal / Keluar dari Mode Edit"
- [ ] Cari ID ditemukan (kotak hasil pencarian dengan gambar & info sejajar)
- [ ] Cari ID tidak ditemukan (pesan merah `ID X tidak ditemukan!`)
- [ ] Tampilan daftar film (kartu/grid, gambar menyesuaikan orientasi)
- [ ] Hapus data
- [ ] Reset semua data
- [ ] Tampilan saat daftar kosong

---

## Template Tabel Bukti

Isi tabel berikut untuk setiap bahasa.

### C++ — `Dokumentasi/CPP/`

| No | Skenario | Nama File | Hasil (✓/✗) |
|----|----------|-----------|-------------|
| 1  | Menu utama | | |
| 2  | Tambah data sukses | | |
| 3  | Error ID tidak valid | | |
| 4  | Error ID sudah digunakan | | |
| 5  | Update kolom | | |
| 6  | Error ID tidak ditemukan | | |
| 7  | Hapus data | | |
| 8  | Cari data ditemukan | | |
| 9  | Cari data tidak ditemukan | | |
| 10 | Keluar program | | |

### Python — `Dokumentasi/Python/`

| No | Skenario | Nama File | Hasil (✓/✗) |
|----|----------|-----------|-------------|
| 1  | Menu utama | | |
| 2  | Tambah data sukses | | |
| 3  | Error: input bukan angka | | |
| 4  | Error ID sudah digunakan | | |
| 5  | Error genre huruf kecil | | |
| 6  | Tampilkan semua data | | |
| 7  | Update kolom | | |
| 8  | Error ID tidak ditemukan | | |
| 9  | Hapus data | | |
| 10 | Cari data tidak ditemukan | | |

### Java — `Dokumentasi/Java/`

| No | Skenario | Nama File | Hasil (✓/✗) |
|----|----------|-----------|-------------|
| 1  | Menu utama | | |
| 2  | Tambah data sukses | | |
| 3  | Error: input bukan angka | | |
| 4  | Error ID sudah digunakan | | |
| 5  | Error genre huruf kecil | | |
| 6  | Tampilkan semua data | | |
| 7  | Update kolom | | |
| 8  | Error ID tidak ditemukan | | |
| 9  | Hapus data | | |
| 10 | Keluar program | | |

### PHP — `Dokumentasi/PHP/`

| No | Skenario | Nama File | Hasil (✓/✗) |
|----|----------|-----------|-------------|
| 1  | Halaman utama + daftar film | | |
| 2  | Tambah data sukses (pesan hijau) | | |
| 3  | Error ID sudah digunakan | | |
| 4  | Error harga/durasi bukan angka | | |
| 5  | Error gambar berupa URL | | |
| 6  | Cari ID ditemukan (kotak hasil) | | |
| 7  | Cari ID tidak ditemukan (pesan merah) | | |
| 8  | Mode edit + simpan + keluar mode edit | | |
| 9  | Hapus data | | |
| 10 | Reset semua data | | |