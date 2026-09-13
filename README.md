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
│   ├── bioskop.cpp   (class Film)
│   └── main.cpp      (menu utama + pengelolaan data + error handling)
├── Python/
│   ├── film.py       (class Film)
│   └── main.py       (menu utama + pengelolaan data + error handling)
├── Java/
│   ├── Film.java     (class Film)
│   └── Main.java     (menu utama + pengelolaan data + error handling)
├── PHP/
│   ├── Film.php      (class Film, termasuk atribut gambar)
│   ├── index.php     (halaman web + pengelolaan data + error handling)
│   └── gambar/       (folder poster lokal)
│       ├── AOT.jpg   (contoh gambar poster1)
│       └── kimi no nawa.jpg (contoh gambar poster2)
├── Dokumentasi (Untuk error handling di README.md)/
│   ├── CPP/          (screenshot output C++)
│   ├── Python/       (screenshot output Python)
│   ├── Java/         (screenshot output Java)
│   └── PHP/          (screenshot output PHP)
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

1. **Tambah data** — membaca input (ID, judul, genre, harga, durasi). Memeriksa ID berupa angka mulai dari 1 dan ID belum dipakai, lalu `push_back` / `add` / `append` objek ke list.
2. **Tampilkan data** — jika list kosong tampilkan pesan; jika tidak, iterasi seluruh objek dimulai degan ID 1. Durasi ≥ 60 menit ditampilkan dalam format jam (misal `135 menit` → `2 jam 15 menit`).
3. **Update data** — membaca ID sasaran, mencari objek di list, menampilkan **pilihan kolom** yang ingin diubah (judul, genre, harga, durasi) dan hanya memperbarui kolom yang dipilih lewat setter.
4. **Hapus data** — mencari objek berdasarkan ID, lalu menghapusnya dari list.
5. **Cari data** — mencari objek berdasarkan ID; jika ketemu tampilkan detailnya, jika tidak tampilkan pesan "tidak ditemukan".

---
