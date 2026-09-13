# Manajemen Bioskop

Program sederhana untuk mengelola data film di sebuah bioskop menggunakan konsep **Object-Oriented Programming (OOP)**. Program dibuat dalam **4 bahasa pemrograman**: **C++**, **Python**, **Java**, dan **PHP**.

---

## Janji

Saya Jaka Permana Herawan dengan NIM 2509371 mengerjakan Tugas Praktikum 1 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

---

## Desain dan Alur Kode

### Class: `Film`

Setiap bahasa menggunakan satu class bernama `Film`.

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
| `gambar` | string | Path file gambar poster lokal (contoh: `gambar/AOT.jpg`). **Khusus PHP** dan berupa path file lokal, **bukan URL internet** |

Seluruh atribut dibuat **private** dan dilengkapi **getter/setter** sehingga mengakses/mengubah nilai hanya lewat method.


## Struktur Folder

```
.
├── CPP/
│   ├── bioskop.cpp   (definisi class Film)
│   └── main.cpp      (menu utama + pengelolaan data + error handling)
├── Python/
│   ├── film.py       (definisi class Film)
│   └── main.py       (menu utama + pengelolaan data + error handling)
├── Java/
│   ├── Film.java     (definisi class Film)
│   └── Main.java     (menu utama + pengelolaan data + error handling)
├── PHP/
│   ├── Film.php      (definisi class Film, termasuk atribut gambar)
│   ├── index.php     (halaman web + pengelolaan data + error handling)
│   └── gambar/       (folder poster lokal)
│       ├── AOT.jpg   (contoh gambar poster1)
|       └── kimi no nawa.jpg (contoh gambar poster2)
├── Dokumentasi/
│   ├── CPP/          
│   ├── Python/       
│   ├── Java/         
│   └── PHP/          
└── Readme.md
```

### Penyimpanan Data

Data dikelola dalam **array/list of object** (tanpa database, data hanya di memori/run-time):

| Bahasa | Wadah Data | Tipe Elemen |
|--------|------------|-------------|
| C++ | `vector<Film>` | objek `Film` |
| Python | `list` | objek `Film` |
| Java | `ArrayList<Film>` | objek `Film` |
| PHP | `$_SESSION['daftar_film']` (session) | objek `Film` |


### Fungsi Utama

1. **Tambah data** — membaca input (ID, judul, genre, harga, durasi). Memeriksa ID berupa angka mulai dari 1 (`cekIdValid()`) dan ID belum dipakai (`cekIdAda()`), lalu `push_back` / `add` / `append` objek ke list.
2. **Tampilkan data** — jika list kosong tampilkan pesan; jika tidak, iterasi seluruh objek dan panggil `tampilkan()`. Durasi ≥ 60 menit ditampilkan dalam format jam (misal `135 menit` → `2 jam 15 menit`).
3. **Update data** — membaca ID sasaran, mencari objek di list, menampilkan **pilihan kolom** yang ingin diubah (judul, genre, harga, durasi) dan hanya memperbarui kolom yang dipilih lewat setter.
4. **Hapus data** — mencari objek berdasarkan ID, lalu menghapusnya dari list.
5. **Cari data** — mencari objek berdasarkan ID; jika ketemu tampilkan detailnya, jika tidak tampilkan pesan "tidak ditemukan".

---

## Error Handling semua bahasa

Pola error handling diterapkan di keempat bahasa:

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

1. Input bukan angka (menu, ID, harga, durasi, jumlah genre, pilihan kolom)
2. ID bukan angka mulai dari 1 (tambah/update/hapus/cari)
3. ID sudah dipakai saat tambah
4. Jumlah genre kurang dari 1
5. Genre diawali huruf kecil
6. ID tidak ditemukan saat update/hapus
7. ID tidak ditemukan saat cari
8. Pilihan kolom update bukan 1-5
9. Pilihan menu bukan 1-6
10. Daftar kosong saat menampilkan data


#### 2. Folder `Python/` (Python)

1. Input bukan angka (menu, ID, harga, durasi, jumlah genre, pilihan kolom)
2. ID bukan angka mulai dari 1 (tambah/update/hapus/cari)
3. ID sudah dipakai saat tambah
4. Jumlah genre kurang dari 1
5. Genre diawali huruf kecil
6. ID tidak ditemukan saat update/hapus
7. ID tidak ditemukan saat cari
8. Pilihan kolom/menu salah
9. Daftar kosong saat menampilkan data

#### 3. Folder `Java/` (Java)

1. Input bukan angka (menu, ID, harga, durasi, jumlah genre, pilihan kolom)
2. ID bukan angka mulai dari 1 (tambah/update/hapus/cari)
3. ID sudah dipakai saat tambah
4. Jumlah genre kurang dari 1
5. Genre diawali huruf kecil
6. ID tidak ditemukan saat update/hapus
7. ID tidak ditemukan saat cari
8. Pilihan kolom update bukan 1-5
9. Pilihan menu bukan 1-6
10. Daftar kosong saat menampilkan data


#### 4. Folder `PHP/` (PHP)

1. ID bukan angka mulai dari 1 (tambah/update)
2. ID sudah dipakai saat tambah
3. Harga/durasi bukan angka valid
4. Genre tidak diawali huruf besar / kosong
5. Gambar diisi URL (http/https/data:) atau kosong
6. Update tanpa mencetang kolom
7. ID tidak ditemukan saat update/hapus
8. ID tidak ditemukan saat cari (via `?cari=`)
9. Daftar kosong
10. File gambar tidak ada di path

---

## Cara Menjalankan

### 1. C++ 

Jalankan dari folder `CPP`:

```bash
g++ main.cpp -o film.exe     #langsung pakai main.cpp (meng-include bioskop.cpp)
film.exe
```

### 2. Python 

Jalankan dari folder `Python`:

```bash
python main.py
```

### 3. Java 

Jalankan dari folder `Java`:

```bash
javac Film.java Main.java
java Main
```

### 4. PHP 

Jalankan dari folder `PHP`:

```bash
php -S localhost:8000
```

Lalu buka `http://localhost:8000/` di browser.

---


## Dokumentasi

Bukti penggunaan program (screenshot) ditempatkan di folder `Dokumentasi` sesuai bahasanya:


