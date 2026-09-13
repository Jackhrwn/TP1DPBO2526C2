# Manajemen Bioskop

Program sederhana untuk mengelola data film di sebuah bioskop menggunakan konsep **Object-Oriented Programming (OOP)**. Program dibuat dalam **4 bahasa pemrograman**: **C++** (CLI), **Python** (CLI), **Java** (CLI), dan **PHP** (Web).

---

## Janji

Saya Jaka Permana Herawan dengan NIM 2509371 mengerjakan Tugas Praktikum 1 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

---

## Desain dan Alur Kode

### Class: `Film`

Setiap bahasa menggunakan satu class bernama `Film` (nama class bebas, di sini disesuaikan dengan tema bioskop).

| Bahasa | Atribut Class `Film` | Jumlah Atribut |
|--------|----------------------|----------------|
| C++ | `id`, `judul`, `genre`, `harga`, `durasi` | 5 |
| Python | `id`, `judul`, `genre`, `harga`, `durasi` | 5 |
| Java | `id`, `judul`, `genre`, `harga`, `durasi` | 5 |
| PHP | `id`, `judul`, `genre`, `harga`, `durasi`, `gambar` | 6 |

Keterangan atribut:

| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| `id` | int | Identifikasi unik film (angka mulai dari 1, contoh: `1`, `2`, `3`) |
| `judul` | string | Judul film |
| `genre` | array of string | Genre film (bisa lebih dari satu, contoh: `["Action", "Drama"]`) |
| `harga` | int | Harga tiket film dalam rupiah |
| `durasi` | int | Durasi film dalam menit (ditampilkan sebagai jam jika ≥ 60 menit) |
| `gambar` | string | Path file gambar poster lokal (contoh: `gambar/AOT.jpg`). **Khusus PHP wajib ada** dan harus path file lokal, **bukan URL internet** |

Seluruh atribut dibuat **private** dan dilengkapi **getter/setter** sehingga mengakses/mengubah nilai hanya lewat method (konsep **enkapsulasi**).

### Penyimpanan Data

Data dikelola dalam **array/list of object** (tanpa database, data hanya di memori/run-time):

| Bahasa | Wadah Data | Tipe Elemen |
|--------|------------|-------------|
| C++ | `vector<Film>` | objek `Film` |
| Python | `list` | objek `Film` |
| Java | `ArrayList<Film>` | objek `Film` |
| PHP | `$_SESSION['daftar_film']` (session, bertahan antar halaman) | objek `Film` |

### Alur Program

**CLI (C++, Python, Java):**

```
START
  v
TAMPILKAN MENU (1-6)
  v
PILIHAN ?--------+
  |              |
  1. Tambah  --> input data -> cek ID unik -> tambah objek Film baru ke list
  2. Tampil  --> iterasi list -> panggil method tampilkan() tiap objek
  3. Update  --> cari objek berdasar ID -> pilih kolom yang mau diubah -> ubah atribut via setter
  4. Hapus   --> cari objek berdasar ID -> hapus dari list
  5. Cari    --> cari objek berdasar ID -> tampilkan data objek
  6. Keluar  --> STOP
  |              |
  +--> kembali ke MENU
```

**Web (PHP):** alur ditangani lewat satu file `index.php`. Halaman mendeteksi request `POST` maupun `GET`:

```
BUKA index.php
  v
PARAMETER ?edit=  ?  ---------+  (masuk mode update, form terisi data lama)
            no               |
  v                            |
FORM TAMBAH / UPDATE ----------+  -> POST aksi = tambah / update / hapus / reset
  v                              -> validasi data; sukses/gagal tampil pesan
GET ?cari=ID  -> pencarian film, ketemu = kotak hasil, tidak ketemu = pesan error
  v
TABEL / KARTU DAFTAR FILM + tombol aksi (Edit, Hapus) + Reset
```

### Fungsi Utama

1. **Tambah data** — membaca input (ID, judul, genre, harga, durasi). Memeriksa ID berupa angka mulai dari 1 (`cekIdValid()`) dan ID belum dipakai (`cekIdAda()`), lalu `push_back` / `add` / `append` objek ke list.
2. **Tampilkan data** — jika list kosong tampilkan pesan; jika tidak, iterasi seluruh objek dan panggil `tampilkan()`. Durasi ≥ 60 menit ditampilkan dalam format jam (misal `135 menit` → `2 jam 15 menit`).
3. **Update data** — membaca ID sasaran, mencari objek di list, menampilkan **pilihan kolom** yang ingin diubah (judul, genre, harga, durasi) dan hanya memperbarui kolom yang dipilih lewat setter.
4. **Hapus data** — mencari objek berdasarkan ID, lalu menghapusnya dari list.
5. **Cari data** — mencari objek berdasarkan ID; jika ketemu tampilkan detailnya, jika tidak tampilkan pesan "tidak ditemukan".

> Pencarian/validasi ID dan pengubahan data dilakukan **tanpa memakai `break`** di dalam perulangan; perulangan dijalankan sampai selesai lalu hasilnya diproses setelahnya.

---

## Error Handling

### Umum (semua bahasa)

Program dirancang agar **tidak crash** saat pengguna salah input. Pola error handling yang sama diterapkan di keempat bahasa:

1. **Input bukan angka** → tidak langsung crash; program meminta ulang sampai input valid.
2. **ID bukan angka mulai dari 1** → pesan `"ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!"`.
3. **ID sudah dipakai** (tambah) → pesan `"ID sudah digunakan!"`.
4. **Jumlah genre kurang dari 1** → pesan `"Jumlah genre harus angka mulai dari 1!"`.
5. **Genre tidak diawali huruf besar** → pesan `"Genre harus diawali dengan huruf besar (contoh: Action)!"`.
6. **ID tidak ditemukan** (update/hapus/cari) → pesan `"ID tidak ditemukan!"`.
7. **Pilihan menu/kolom di luar jangkauan** → pesan `"Pilihan tidak valid!"`.
8. **Daftar kosong** saat tampil → pesan `"Belum ada data film."`.

### Error Handling per Folder

#### 1. Folder `CPP/` (C++)

File: `main.cpp` (pengelolaan data + menu), `bioskop.cpp` (class `Film`).

| Skenario | Cara Ditangani | Pesan yang Ditampilkan |
|----------|----------------|------------------------|
| Input bukan angka (menu, ID, harga, durasi, jumlah genre, pilihan kolom) | Fungsi `inputAngka()` (main.cpp:70) membaca baris utuh lalu memeriksa tiap karakter dengan `isdigit()`; perulangan `while(true)` meminta ulang sampai valid | `Input harus berupa angka! Silakan coba lagi.` (merah) |
| ID < 1 (tambah/update/hapus/cari) | `cekIdValid()` dalam perulangan `while(true)` | `ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!` |
| ID sudah dipakai (tambah) | `cekIdAda()` memeriksa seluruh `vector<Film>` | `ID sudah digunakan!` |
| Jumlah genre < 1 | validasi `while(true)` | `Jumlah genre harus angka mulai dari 1!` |
| Genre diawali huruf kecil | cek `isupper(g[0])` | `Genre harus diawali dengan huruf besar (contoh: Action)!` |
| ID tidak ditemukan (update/hapus) | `idxSasaran` / `idxHapus` tetap `-1` | `ID tidak ditemukan!` |
| ID tidak ditemukan (cari) | `idxCari` tetap `-1` | `Film dengan ID 'X' tidak ditemukan!` |
| Pilihan kolom update bukan 1-5 | `default` pada `switch` | `Pilihan tidak valid!` |
| Pilihan menu bukan 1-6 | `default` pada `switch` di `main()` | `Pilihan tidak valid!` |
| Daftar kosong saat tampil | `daftar.empty()` | `Belum ada data film.` |

#### 2. Folder `Python/` (Python)

File: `main.py` (pengelolaan data + menu), `film.py` (class `Film`).

| Skenario | Cara Ditangani | Pesan yang Ditampilkan |
|----------|----------------|------------------------|
| Input bukan angka | Fungsi `input_angka()` (main.py:50) membungkus `int(input(...))` dengan `try/except ValueError`; diulangi sampai benar | `Input harus berupa angka! Silakan coba lagi.` (merah) |
| ID < 1 | `cek_id_valid()` dalam perulangan | `ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!` |
| ID sudah dipakai | `cek_id_ada()` | `ID sudah digunakan!` |
| Jumlah genre < 1 | validasi `while` | `Jumlah genre harus angka mulai dari 1!` |
| Genre diawali huruf kecil | cek `g[0].isupper()` | `Genre harus diawali dengan huruf besar (contoh: Action)!` |
| ID tidak ditemukan (update/hapus) | objek/index sasaran `None` / `-1` | `ID tidak ditemukan!` |
| ID tidak ditemukan (cari) | objek hasil `None` | `Film dengan ID 'X' tidak ditemukan!` |
| Pilihan kolom/menu salah | cabang `else` | `Pilihan tidak valid!` |
| Daftar kosong saat tampil | `if not daftar` | `Belum ada data film.` |

#### 3. Folder `Java/` (Java)

File: `Main.java` (pengelolaan data + menu), `Film.java` (class `Film`).

| Skenario | Cara Ditangani | Pesan yang Ditampilkan |
|----------|----------------|------------------------|
| Input bukan angka | Fungsi `inputAngka()` (Main.java:59) membaca baris utuh dan memeriksa tiap karakter dengan `Character.isDigit()`; `class InputMismatchException` diimport untuk menangkap input angka yang salah (metode alternatif `try/catch`); perulangan meminta ulang | `Input harus berupa angka! Silakan coba lagi.` (merah) |
| ID < 1 | `cekIdValid()` dalam perulangan | `ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!` |
| ID sudah dipakai | `cekIdAda()` | `ID sudah digunakan!` |
| Jumlah genre < 1 | validasi `while` | `Jumlah genre harus angka mulai dari 1!` |
| Genre diawali huruf kecil | cek `Character.isUpperCase(g.charAt(0))` | `Genre harus diawali dengan huruf besar (contoh: Action)!` |
| ID tidak ditemukan (update/hapus) | objek/index sasaran `null` / `-1` | `ID tidak ditemukan!` |
| ID tidak ditemukan (cari) | objek hasil `null` | `Film dengan ID 'X' tidak ditemukan!` |
| Pilihan kolom update bukan 1-5 | `default` pada `switch` | `Pilihan tidak valid!` |
| Pilihan menu bukan 1-6 | `default` pada `switch` | `Pilihan tidak valid!` |
| Daftar kosong saat tampil | `daftarFilm.isEmpty()` | `Belum ada data film.` |

#### 4. Folder `PHP/` (PHP — Web)

File: `index.php` (halaman web + logika pengelolaan), `Film.php` (class `Film`), folder `gambar/` (poster lokal, contoh `AOT.jpg`).

| Skenario | Cara Ditangani | Pesan yang Ditampilkan |
|----------|----------------|------------------------|
| ID bukan angka mulai dari 1 (tambah/update) | Fungsi `cekIdValid()` | `ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!` |
| ID sudah dipakai (tambah) | Fungsi `cekIdAda()` | `ID sudah digunakan!` |
| Harga/durasi bukan angka valid | Fungsi `validasiAngka()` | `Harga dan durasi harus berupa angka yang valid!` |
| Genre tidak diawali huruf besar / kosong | Fungsi `validasiGenre()` (regex `^[A-Z]`) | `Genre harus diawali dengan huruf besar (contoh: Action)!` |
| Gambar diisi URL (http/https/data:) atau kosong | Fungsi `cekGambarValid()` menolak string berawalan `http://`, `https://`, `data:`, `//` | `Gambar wajib berisi path file lokal (contoh: gambar/AOT.jpg), bukan URL!` |
| Update tanpa mencetang kolom | `empty($kolom)` | `Pilih minimal satu kolom yang ingin diupdate!` |
| ID tidak ditemukan (update/hapus) | hasil pencarian `false` / index `-1` | `ID tidak ditemukan!` |
| ID tidak ditemukan (cari via `?cari=`) | `$filmCari === null` setelah perulangan | `ID X tidak ditemukan!` |
| Daftar kosong | `count($_SESSION['daftar_film']) === 0` | kartu kosong: `Belum ada data film. Silakan tambahkan terlebih dahulu.` |
| File gambar tidak ada di path | `file_exists()` di fungsi `tampilGambar()`; orientasi gambar dideteksi dari `getimagesize()` | teks `Tanpa gambar` |
| Pesan sukses vs error | deteksi otomatis via regex `/(tidak\|harus\|sudah digunakan\|minimal)/` → tampil hijau (sukses) / merah (error) | - |

> Khusus PHP: tidak ada `try/catch` karena tidak membaca input dari konsol; semua input lewat form. Validasi dilakukan berlapis dengan `isset()`, `is_numeric()`, `preg_match()`, dan `file_exists()` sehingga program tidak pernah error fatal akibat input user.

---

## Cara Menjalankan

### 1. C++ (CLI)

Jalankan dari folder `CPP`:

```bash
g++ main.cpp -o film.exe     # atau langsung pakai main.cpp (meng-include bioskop.cpp)
film.exe
```

### 2. Python (CLI)

Jalankan dari folder `Python`:

```bash
python main.py
```

### 3. Java (CLI)

Jalankan dari folder `Java`:

```bash
javac Film.java Main.java
java Main
```

### 4. PHP (Web)

Jalankan dari folder `PHP`:

```bash
php -S localhost:8000
```

Lalu buka `http://localhost:8000/` di browser.

---

## Struktur Folder

```
.
├── CPP/
│   ├── bioskop.cpp   (definisi class Film)
│   └── main.cpp      (menu utama + pengelolaan data + error handling)
│   └── film.exe      (hasil kompilasi, opsional)
├── Python/
│   ├── film.py       (definisi class Film)
│   └── main.py       (menu utama + pengelolaan data + error handling)
├── Java/
│   ├── Film.java     (definisi class Film)
│   └── Main.java     (menu utama + pengelolaan data + error handling)
│   └── Film.class / Main.class  (hasil kompilasi, opsional)
├── PHP/
│   ├── Film.php      (definisi class Film, termasuk atribut gambar)
│   ├── index.php     (halaman web + pengelolaan data + error handling)
│   └── gambar/       (folder poster lokal)
│       └── AOT.jpg   (contoh gambar poster)
├── Dokumentasi/
│   ├── TEMPLATE.md   (template dokumentasi / bukti)
│   ├── CPP/          (buat bukti C++)
│   ├── Python/       (buat bukti Python)
│   ├── Java/         (buat bukti Java)
│   └── PHP/          (buat bukti PHP)
└── Readme.md
```

> Catatan: file `film.exe`, `.class`, `__pycache__` adalah hasil/artefak kompilasi dan tidak wajib di-commit.

---

## Dokumentasi

Bukti penggunaan program (screenshot/screenrecord) ditempatkan di folder `Dokumentasi` sesuai bahasanya:

| Bahasa | Folder Tempat | Contoh Nama File |
|--------|---------------|------------------|
| C++ | `Dokumentasi/CPP/` | `MENGGUNAKAN CPP.png` |
| Python | `Dokumentasi/Python/` | `MENGGUNAKAN Python.png` |
| Java | `Dokumentasi/Java/` | `MENGGUNAKAN Java.png` |
| PHP | `Dokumentasi/PHP/` | `MENGGUNAKAN PHP.png` |

- File dapat berupa gambar (`.png`, `.jpg`) maupun video screenrecord (`.mp4`, `.gif`).
- **Template dokumentasi lengkap** (daftar skenario yang wajib dibuktikan + format tabel bukti) sudah disediakan di **`Dokumentasi/TEMPLATE.md`** — salin isinya lalu isi sesuai hasil uji coba.