<?php

// Class Film 
class Film {
    // atribut dibuat private (enkapsulasi)
    private $id;      // atribut id unik film (angka mulai dari 1, contoh: 1, 2, 3)
    private $judul;   // atribut judul film
    private $genre;   // atribut genre film (array, contoh: ["Action", "Drama"])
    private $harga;   // atribut harga tiket film dalam rupiah
    private $durasi;  // atribut durasi film dalam menit
    private $gambar;  // atribut path file gambar lokal (bukan URL), contoh: gambar/mikasa.jpg

    // Constructor: method khusus yang dijalankan saat objek Film dibuat
    public function __construct($id, $judul, $genre, $harga, $durasi, $gambar = '') {
        $this->id = $id;              // isi atribut id dengan nilai parameter
        $this->judul = $judul;        // isi atribut judul dengan nilai parameter
        $this->genre = $genre;        // isi atribut genre dengan nilai parameter
        $this->harga = $harga;        // isi atribut harga dengan nilai parameter
        $this->durasi = $durasi;      // isi atribut durasi dengan nilai parameter
        $this->gambar = $gambar;      // isi atribut gambar (path file lokal)
    }

    // Getter id: mengambil (membaca) nilai atribut id
    public function getId() { return $this->id; }
    // Getter judul: mengambil nilai atribut judul
    public function getJudul() { return $this->judul; }
    // Getter genre: mengambil nilai atribut genre
    public function getGenre() { return $this->genre; }
    // Getter genreText: menggabungkan list genre menjadi satu string
    public function getGenreText() {
        return implode(", ", $this->genre);   // gabungkan genre dengan tanda koma
    }
    // Getter harga: mengambil nilai atribut harga
    public function getHarga() { return $this->harga; }
    // Getter durasi: mengambil nilai atribut durasi
    public function getDurasi() { return $this->durasi; }
    // Getter gambar: mengambil nilai atribut gambar (path file lokal)
    public function getGambar() { return $this->gambar; }

    // Setter judul: mengubah nilai atribut judul
    public function setJudul($judul) { $this->judul = $judul; }
    // Setter genre: mengubah nilai atribut genre
    public function setGenre($genre) { $this->genre = $genre; }
    // Setter harga: mengubah nilai atribut harga
    public function setHarga($harga) { $this->harga = $harga; }
    // Setter durasi: mengubah nilai atribut durasi
    public function setDurasi($durasi) { $this->durasi = $durasi; }
    // Setter gambar: mengubah nilai atribut gambar (path file lokal)
    public function setGambar($gambar) { $this->gambar = $gambar; }
}
?>
