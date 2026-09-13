<?php
// File: index.php (berisi halaman web dan logika pengelolaan data)

require_once "Film.php";       // sertakan definisi class Film SEBELUM session_start (agar objek Film dari session bisa di-reload)
session_start();               // aktifkan session untuk menyimpan data antar halaman

// jika session daftar_film belum ada, buat dengan list (array) kosong
if (!isset($_SESSION['daftar_film'])) {
    $_SESSION['daftar_film'] = [];   // inisialisasi array kosong
}

$pesan = "";   // variabel untuk menampung pesan notifikasi ke user

// Fungsi setFlash: menyimpan pesan notifikasi sementara agar tampil SATU KALI setelah redirect.
// Dipakai untuk pola POST-Redirect-GET supaya pesan HILANG saat halaman di-reload.
function setFlash($teks) {
    $_SESSION['flash_pesan'] = $teks;
}

// ambil pesan sementara (flash) dari session jika ada, lalu hapus agar tidak tampil 2x
if (isset($_SESSION['flash_pesan'])) {
    $pesan = $_SESSION['flash_pesan'];
    unset($_SESSION['flash_pesan']);
}

// ambil data form sementara (hasil submit yang error) lalu hapus, untuk mengisi ulang form
$formData = null;
if (isset($_SESSION['form_backup'])) {
    $formData = $_SESSION['form_backup'];
    unset($_SESSION['form_backup']);
}

// ambil nilai pencarian sementara (hasil cari yang tidak ketemu) lalu hapus
$cariBackup = null;
if (isset($_SESSION['cari_backup'])) {
    $cariBackup = $_SESSION['cari_backup'];
    unset($_SESSION['cari_backup']);
}

// Fungsi cekIdValid: memeriksa apakah id berupa angka mulai dari 1 (1, 2, 3, ...)
function cekIdValid($id) {
    return is_numeric($id) && $id >= 1;   // valid jika angka lebih besar atau sama dengan 1
}

// Fungsi cekIdAda: memeriksa apakah sebuah id sudah ada di dalam daftar
function cekIdAda($id) {
    $ketemu = false;                         // penanda hasil pencarian
    foreach ($_SESSION['daftar_film'] as $film) {  // ulangi semua objek film
        if ($film->getId() == $id) {         // jika id objek sama dengan id cari
            $ketemu = true;                  // tandai ketemu (tanpa break)
        }
    }
    return $ketemu;                          // kembalikan hasil
}

// Fungsi formatDurasi: mengubah durasi (menit) menjadi format jam jika >= 1 jam
function formatDurasi($menit) {
    $jam = (int)floor($menit / 60);          // hitung jumlah jam
    $sisa = $menit % 60;                     // hitung sisa menit
    if ($jam > 0) {                          // jika durasi 60 menit atau lebih
        $hasil = $jam . " jam";              // tampilkan jumlah jam
        if ($sisa > 0) {                     // jika ada sisa menit
            $hasil .= " " . $sisa . " menit";  // tampilkan sisa menit
        }
        return $hasil;                       // kembalikan format jam
    }
    return $menit . " menit";                // kembali ke format menit
}

// Fungsi cekGambarValid: memeriksa apakah path gambar berupa file lokal (BUKAN URL),
// karena tugas mensyaratkan gambar disimpan di local (bukan disimpan sebagai url/url gambar online)
function cekGambarValid($path) {
    if (!isset($path) || trim($path) === '') {       // kosong => tidak valid
        return false;
    }
    if (preg_match('#^(https?://|data:|//)#i', trim($path))) {  // diawali URL => harus path lokal, tidak valid
        return false;
    }
    return true;                                     // path file lokal valid
}

// Fungsi tampilGambar: menampilkan gambar lokal sambil MENYESUAIKAN orientasi asli gambar.
// Jika file portrait => tampil tinggi (potrait), jika landscape => tampil lebar (landscape).
// Memakai getimagesize() untuk membaca lebar & tinggi asli, lalu menskalanya agar muat
// dalam kotak selebar $maxW dan setinggi $maxH tanpa merubah proporsi.
function tampilGambar($path, $maxW = 80, $maxH = 110) {
    if ($path === '' || !file_exists($path)) {          // path kosong / file tidak ada
        return '<span style="color:#999;font-style:italic;">Tanpa gambar</span>';
    }
    $info = @getimagesize($path);                        // baca dimensi gambar asli
    if ($info === false) {                               // bukan file gambar yang valid
        return '<span style="color:#999;font-style:italic;">File bukan gambar valid</span>';
    }
    $w = $info[0];                                       // lebar asli gambar
    $h = $info[1];                                       // tinggi asli gambar
    // skala agar gambar muat dalam kotak maxW x maxH (pertahankan proporsi/orientasi)
    $skala = min($maxW / $w, $maxH / $h, 1);
    $lebar = (int)round($w * $skala);
    $tinggi = (int)round($h * $skala);
    return '<img src="' . htmlspecialchars($path) . '" width="' . $lebar . '" height="' . $tinggi . '" style="border-radius:6px;display:block;" alt="Poster">';
}

// Fungsi validasiAngka: memeriksa apakah input berupa angka (ERROR HANDLING)
// mengembalikan true jika angkanya valid, false jika berupa string
function validasiAngka($nilai) {
    if (!isset($nilai) || $nilai === '' || !is_numeric($nilai) || $nilai < 0) {
        return false;   // kosong, bukan angka, atau negatif => tidak valid
    }
    return true;        // angka valid
}

// Fungsi validasiGenre: memeriksa apakah semua genre diawali huruf besar
function validasiGenre($genre) {
    if (empty($genre)) {                    // jika tidak ada genre sama sekali
        return false;                       // tidak valid
    }
    foreach ($genre as $g) {                // ulangi setiap genre
        if ($g === '' || !preg_match('/^[A-Z]/', $g)) {  // jika kosong atau diawali huruf kecil
            return false;                   // genre tidak valid
        }
    }
    return true;                            // semua genre valid
}

// Cek apakah ada request POST (form dikirim)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'];   // ambil nilai aksi dari input hidden

    // jika aksinya tambah
    if ($aksi === 'tambah') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';   // ambil nilai id dari form
        // ubah input genre (pisahkan koma) menjadi array genre
        $genreInput = !empty($_POST['genre']) ? array_map('trim', explode(',', $_POST['genre'])) : [];
        // simpan sementara input user agar form dapat terisi ulang jika ada error
        $_SESSION['form_backup'] = $_POST;
        if (!cekIdValid($id)) {                    // jika id bukan angka mulai dari 1
            setFlash("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!");  // pesan error validasi id
        } elseif (cekIdAda((int)$id)) {              // jika id sudah dipakai
            setFlash("ID sudah digunakan!");          // tampilkan pesan error id
        } elseif (!validasiAngka($_POST['harga']) || !validasiAngka($_POST['durasi'])) {   // jika harga/durasi bukan angka
            setFlash("Harga dan durasi harus berupa angka yang valid!");  // tampilkan pesan error
        } elseif (!validasiGenre($genreInput)) {     // jika ada genre yang diawali huruf kecil
            setFlash("Genre harus diawali dengan huruf besar (contoh: Action)!");  // tampilkan pesan error
        } elseif (!cekGambarValid($_POST['gambar'])) { // jika gambar bukan path lokal
            setFlash("Gambar wajib berisi path file lokal (contoh: gambar/AOT.jpg), bukan URL!");  // pesan error gambar
        } else {
            // buat objek Film baru dengan data dari form
            $film = new Film(
                (int)$id,                  // isi id (dikonversi ke integer)
                $_POST['judul'],           // isi judul
                $genreInput,               // isi genre (array)
                (int)$_POST['harga'],      // isi harga (dikonversi ke integer)
                (int)$_POST['durasi'],     // isi durasi
                trim($_POST['gambar'])     // isi path gambar lokal
            );
            $_SESSION['daftar_film'][] = $film;   // simpan objek ke dalam session
            unset($_SESSION['form_backup']);      // data berhasil, bersihkan cadangan form
            setFlash("Data berhasil ditambahkan!");  // pesan sukses
        }
        // redirect agar saat halaman di-reload, pesan tidak muncul lagi dan data tidak terkirim ulang
        header("Location: index.php");
        exit;
    }
    // jika aksinya update
    elseif ($aksi === 'update') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';   // ambil id sasaran dari form
        // ambil daftar kolom yang dicentang user untuk diupdate
        $kolom = isset($_POST['kolom']) ? $_POST['kolom'] : [];
        // ubah input genre (pisahkan koma) menjadi array genre
        $genreInput = !empty($_POST['genre']) ? array_map('trim', explode(',', $_POST['genre'])) : [];

        // jika id bukan angka mulai dari 1
        if (!cekIdValid($id)) {
            setFlash("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!");  // pesan error validasi id
            header("Location: index.php");
            exit;
        }
        // jika tidak ada kolom yang dipilih
        if (empty($kolom)) {
            setFlash("Pilih minimal satu kolom yang ingin diupdate!");  // pesan error
            header("Location: index.php?edit=" . (int)$id);   // tetap di mode edit
            exit;
        }
        // validasi harga dan durasi hanya jika kolom tersebut dipilih
        if ((in_array('harga', $kolom) && !validasiAngka($_POST['harga']))
         || (in_array('durasi', $kolom) && !validasiAngka($_POST['durasi']))) {
            setFlash("Harga dan durasi harus berupa angka yang valid!");  // pesan error
            header("Location: index.php?edit=" . (int)$id);   // tetap di mode edit
            exit;
        }
        if (in_array('genre', $kolom) && !validasiGenre($genreInput)) {
            setFlash("Genre harus diawali dengan huruf besar (contoh: Action)!");  // pesan error
            header("Location: index.php?edit=" . (int)$id);
            exit;
        }
        if (in_array('gambar', $kolom) && !cekGambarValid($_POST['gambar'])) {
            setFlash("Gambar wajib berisi path file lokal (contoh: gambar/AOT.jpg), bukan URL!");  // pesan error gambar
            header("Location: index.php?edit=" . (int)$id);
            exit;
        }
        // jika validasi lolos, lakukan update pada kolom yang dicentang
        $ketemu = false;                                        // penanda data ditemukan
        $idSasaran = (int)$id;                                  // konversi id sasaran ke integer
        foreach ($_SESSION['daftar_film'] as $film) {           // cari objek di daftar
            if ($film->getId() == $idSasaran) {                 // jika id cocok dengan sasaran
                $ketemu = true;                                 // tandai data ditemukan
                // ubah hanya kolom yang dicentang user
                if (in_array('judul', $kolom)) {                // jika judul dicentang
                    $film->setJudul($_POST['judul']);           // ubah judul lewat setter
                }
                if (in_array('genre', $kolom)) {                // jika genre dicentang
                    $film->setGenre($genreInput);               // ubah genre lewat setter
                }
                if (in_array('harga', $kolom)) {                // jika harga dicentang
                    $film->setHarga((int)$_POST['harga']);      // ubah harga lewat setter
                }
                if (in_array('durasi', $kolom)) {               // jika durasi dicentang
                    $film->setDurasi((int)$_POST['durasi']);    // ubah durasi lewat setter
                }
                if (in_array('gambar', $kolom)) {               // jika gambar dicentang
                    $film->setGambar(trim($_POST['gambar']));   // ubah gambar lewat setter
                }
            }
        }
        // tampilkan pesan sesuai hasil pencarian
        if ($ketemu) {                                        // jika data ditemukan dan berhasil diupdate
            setFlash("Data berhasil diupdate!");              // simpan pesan lewat flash session
            header("Location: index.php");                    // redirect kembali ke halaman normal (keluar mode edit)
            exit;                                             // hentikan eksekusi setelah redirect
        }
        setFlash("ID tidak ditemukan!");                      // pesan error id tidak ada
        header("Location: index.php");
        exit;
    }
    // jika aksinya hapus
    elseif ($aksi === 'hapus') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';              // ambil id yang akan dihapus
        // jika id bukan angka mulai dari 1
        if (!cekIdValid($id)) {
            setFlash("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!");  // pesan error validasi id
        } else {
            $indexHapus = -1;                                       // index sasaran (default belum ketemu)
            $idSasaran = (int)$id;                                  // konversi id ke integer
            foreach ($_SESSION['daftar_film'] as $i => $film) {     // cari dengan index
                if ($film->getId() == $idSasaran) {                 // jika id cocok
                    $indexHapus = $i;                               // simpan index sasaran
                }
            }
            if ($indexHapus == -1) {                                // jika tidak ada yang cocok
                setFlash("ID tidak ditemukan!");                    // pesan id tidak ada
            } else {
                unset($_SESSION['daftar_film'][$indexHapus]);       // hapus objek pada index tersebut
                $_SESSION['daftar_film'] = array_values($_SESSION['daftar_film']);  // rapikan index array
                setFlash("Data berhasil dihapus!");                 // pesan sukses
            }
        }
        // redirect agar saat halaman di-reload, pesan tidak muncul lagi
        header("Location: index.php");
        exit;
    }
    // jika aksinya reset (hapus semua data)
    elseif ($aksi === 'reset') {
        $_SESSION['daftar_film'] = [];   // kosongkan seluruh data
        setFlash("Semua data dihapus!");  // pesan sukses
        // redirect agar saat halaman di-reload, pesan tidak muncul lagi
        header("Location: index.php");
        exit;
    }
}

$filmEdit = null;                                   // variabel untuk objek film yang sedang diedit
// jika ada parameter edit di URL
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];                   // konversi id edit ke integer
    foreach ($_SESSION['daftar_film'] as $film) {   // cari objek di daftar
        if ($film->getId() == $editId) {            // jika id cocok
            $filmEdit = $film;                      // simpan ke variabel filmEdit
        }
    }
}

$filmCari = null;                       // variabel untuk hasil pencarian film
// jika ada parameter cari di URL
if (isset($_GET['cari'])) {
    $idCari = $_GET['cari'];            // ambil id yang dicari dari URL
    // simpan sementara input pencarian agar kotak cari tetap terisi setelah redirect
    $_SESSION['cari_backup'] = $idCari;
    // jika id bukan angka mulai dari 1
    if (!cekIdValid($idCari)) {
        setFlash("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!");  // pesan error validasi id
        header("Location: index.php");   // redirect agar pesan hilang saat reload
        exit;
    }
    $idCariInt = (int)$idCari;                      // konversi id cari ke integer
    foreach ($_SESSION['daftar_film'] as $film) {   // cari objek di daftar
        if ($film->getId() == $idCariInt) {         // jika id cocok
            $filmCari = $film;                      // simpan ke variabel filmCari
        }
    }
    if ($filmCari === null) {                       // jika pencarian tidak menemukan apa pun
        setFlash("ID $idCariInt tidak ditemukan!"); // pesan error id tidak ada
        header("Location: index.php");              // redirect agar pesan hilang saat reload
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Bioskop</title>
    <!-- style CSS agar tampilan halaman modern dan menarik -->
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            padding: 30px 15px;
        }
        .container { max-width: 1100px; margin: auto; }
        .header {
            text-align: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 28px 20px;
            margin-bottom: 25px;
            backdrop-filter: blur(6px);
        }
        .header h1 {
            color: #fff;
            font-size: 30px;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }
        .header h1 .icon-film { color: #f6c453; }
        .header p { color: #b8c4d6; margin-top: 6px; font-size: 14px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 800px) { .grid { grid-template-columns: 1fr; } }
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            margin-bottom: 25px;
        }
        .card h2 {
            color: #16213e;
            font-size: 19px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #e94560;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .label-field {
            display: block;
            font-weight: 600;
            color: #333;
            margin: 12px 0 5px;
            font-size: 14px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #e0e4ee;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #e94560;
            box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.15);
        }
        input:disabled, input[readonly] {
            background: #f3f4f8;
            color: #888;
        }
        .btn {
            display: inline-block;
            padding: 11px 22px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .15s, box-shadow .2s, background .2s;
            text-decoration: none;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.2); }
        .btn-tambah, .btn-simpan { background: linear-gradient(135deg, #e94560, #c2203c); color: #fff; width: 100%; margin-top: 18px; }
        .btn-cari { background: linear-gradient(135deg, #0f3460, #16213e); color: #fff; width: 100%; margin-top: 12px; }
        .btn-edit { background: linear-gradient(135deg, #0f3460, #16213e); color: #fff; font-size: 13px; padding: 10px 14px; border-radius: 10px; box-shadow: 0 4px 10px rgba(15, 52, 96, .3); }
        .btn-hapus { background: linear-gradient(135deg, #e94560, #c2203c); color: #fff; font-size: 13px; padding: 10px 14px; border-radius: 10px; box-shadow: 0 4px 10px rgba(233, 69, 96, .3); }
        .btn-reset { background: #f6c453; color: #333; }
        .btn-batal { background: #6c757d; color: #fff; width: 100%; margin-top: 10px; }
        .kolom-update {
            margin-top: 14px;
            padding: 14px;
            background: #f7f8fc;
            border: 2px dashed #cbd0e0;
            border-radius: 10px;
        }
        .kolom-update .judul-kolom { font-weight: 700; color: #16213e; font-size: 14px; margin-bottom: 8px; }
        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #fff;
            border: 1px solid #dde2ee;
            border-radius: 8px;
            padding: 7px 12px;
            margin: 4px 6px 4px 0;
            font-size: 14px;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }
        .checkbox-label:hover { border-color: #e94560; }
        .checkbox-label input { width: 16px; height: 16px; accent-color: #e94560; cursor: pointer; }
        .pesan {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 15px;
            font-weight: 600;
            border-left: 5px solid;
        }
        .pesan-sukses { background: #d4edda; color: #155724; border-color: #28a745; }
        .pesan-error { background: #f8d7da; color: #721c24; border-color: #dc3545; }
        .hasil-cari {
            background: linear-gradient(135deg, #fff8e1, #ffefc2);
            color: #6b5b00;
            padding: 20px 22px;
            border-radius: 14px;
            border: 1px solid #f0dfa0;
            border-left: 5px solid #f6c453;
            margin-bottom: 20px;
            font-size: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
        }
        .hasil-cari b { color: #16213e; }
        .hc-judul { font-size: 16px; font-weight: 700; color: #6b5b00; margin-bottom: 14px; }
        .hc-body { display: flex; gap: 22px; align-items: flex-start; }
        .hc-gambar {
            flex-shrink: 0;
            align-self: flex-start;
            display: inline-block;
            padding: 6px;
            background: #fff;
            border: 2px dashed #e0c86e;
            border-radius: 10px;
            line-height: 0;
        }
        .hc-gambar img { display: block; border-radius: 6px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); }
        .hc-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            border: 2px solid #e0c86e;
            background: #fff;
            border-radius: 10px;
            padding: 16px 18px;
        }
        .hc-item { display: flex; align-items: baseline; padding: 8px 0; }
        .hc-item:not(:last-child) { border-bottom: 1px dashed #ecd69a; }
        .hc-label { flex: 0 0 110px; font-weight: 600; color: #16213e; white-space: nowrap; }
        .hc-colon { flex: 0 0 16px; color: #16213e; font-weight: 700; text-align: center; }
        .hc-value { color: #333; font-weight: 600; }
        @media (max-width: 600px) {
            .hc-body { flex-direction: column; }
        }
        .film-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 22px;
        }
        .film-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            transition: transform .2s, box-shadow .2s;
            display: flex;
            flex-direction: column;
        }
        .film-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35); }
        .film-poster {
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #16213e, #0f3460);
        }
        .film-poster img {
            width: 100%;
            height: auto;
            display: block;
        }
        .film-poster span { color: #b8c4d6; font-style: italic; padding: 0 14px; text-align: center; font-size: 14px; }
        .film-info { padding: 16px 18px 4px; flex: 1; }
        .film-id {
            display: inline-block;
            background: rgba(233, 69, 96, 0.12);
            color: #c2203c;
            font-size: 12px;
            font-weight: 700;
            border-radius: 20px;
            padding: 4px 12px;
            margin-bottom: 8px;
        }
        .film-judul { font-size: 19px; font-weight: 700; color: #16213e; margin-bottom: 10px; }
        .film-genre { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
        .film-genre .tag {
            background: #eef1f8;
            color: #0f3460;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .film-harga { font-size: 16px; font-weight: 700; color: #e94560; margin-bottom: 4px; }
        .film-durasi { font-size: 13px; color: #777; margin-bottom: 14px; }
        .film-aksi {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 0 18px 18px;
        }
        .film-aksi .btn { text-align: center; width: 100%; }
        .film-aksi form { margin: 0; }
        .film-kosong {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.08);
            border: 2px dashed rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 60px 20px;
            text-align: center;
            color: #b8c4d6;
            font-size: 16px;
        }
        .list-head {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            margin: 10px 0 20px;
            font-size: 20px;
        }
        .list-head .count {
            background: rgba(246, 196, 83, 0.2);
            color: #f6c453;
            font-size: 13px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
        }
        .reset-wrap { text-align: right; margin-top: 18px; }
    </style>
</head>
<body>
<div class="container">   <!-- pembungkus halaman utama -->

    <!-- header halaman dengan judul menarik -->
    <div class="header">
        <h1><span class="icon-film">🎬</span> MANAJEMEN DATA BIOSKOP</h1>
        <p>Kelola data film favoritmu dengan fitur: tambah, tampilkan, update, hapus, dan cari.</p>
    </div>

    <?php if ($pesan): ?>   <!-- jika ada pesan -->
        <?php $isError = preg_match('/(tidak|harus|sudah digunakan|minimal)/', $pesan); ?>
        <!-- tampilkan pesan notifikasi (sukses/error) -->
        <div class="pesan <?php echo $isError ? 'pesan-error' : 'pesan-sukses'; ?>"><?php echo $pesan; ?></div>
    <?php endif; ?>

<?php if ($filmCari): ?>   <!-- jika ada hasil pencarian -->
        <div class="hasil-cari">   <!-- kotak hasil pencarian -->
            <div class="hc-judul">🔎 Hasil Pencarian ID <b>"<?php echo $filmCari->getId(); ?>"</b></div>
            <div class="hc-body">
                <div class="hc-gambar">
                    <?php echo tampilGambar($filmCari->getGambar(), 200, 260); ?>
                </div>
                <div class="hc-info">
                    <div class="hc-item"><span class="hc-label">Judul</span><span class="hc-colon">:</span><span class="hc-value"><?php echo $filmCari->getJudul(); ?></span></div>
                    <div class="hc-item"><span class="hc-label">Genre</span><span class="hc-colon">:</span><span class="hc-value"><?php echo $filmCari->getGenreText(); ?></span></div>
                    <div class="hc-item"><span class="hc-label">Harga Tiket</span><span class="hc-colon">:</span><span class="hc-value">Rp <?php echo number_format($filmCari->getHarga(), 0, ',', '.'); ?></span></div>
                    <div class="hc-item"><span class="hc-label">Durasi Film</span><span class="hc-colon">:</span><span class="hc-value"><?php echo formatDurasi($filmCari->getDurasi()); ?></span></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid">   <!-- grid dua kolom untuk form input + pencarian -->

        <!-- form input / update data film -->
        <div class="card">
            <h2><?php echo $filmEdit ? '✏️ Update Data Film' : '➕ Tambah Data Film'; ?></h2>

            <?php if ($filmEdit): ?>   <!-- jika sedang mode update -->
                <!-- info data film saat ini -->
                <p style="font-size:14px;color:#666;margin-bottom:10px;">
                    Saat ini anda mengedit film <b style="color:#e94560;"><?php echo $filmEdit->getId(); ?></b>.
                    Centang kolom yang ingin diubah.
                </p>
            <?php endif; ?>

            <form method="POST">   <!-- form input data film -->
                <input type="hidden" name="aksi" value="<?php echo $filmEdit ? 'update' : 'tambah'; ?>">  <!-- aksi form -->
                <label class="label-field">ID</label>
                <input type="number" name="id" value="<?php echo $filmEdit ? $filmEdit->getId() : (isset($formData['id']) ? (int)$formData['id'] : ''); ?>" placeholder="1" min="1" <?php echo $filmEdit ? 'readonly' : ''; ?> required>
                <label class="label-field">Judul</label>
                <input type="text" name="judul" value="<?php echo $filmEdit ? htmlspecialchars($filmEdit->getJudul()) : (isset($formData['judul']) ? htmlspecialchars($formData['judul']) : ''); ?>" placeholder="Judul Film" <?php echo $filmEdit ? '' : 'required'; ?>>
                <label class="label-field">Genre</label>
                <input type="text" name="genre" value="<?php echo $filmEdit ? htmlspecialchars($filmEdit->getGenreText()) : (isset($formData['genre']) ? htmlspecialchars($formData['genre']) : ''); ?>" placeholder="Action, Drama, ..." <?php echo $filmEdit ? '' : 'required'; ?>>
                <label class="label-field">Harga</label>
                <input type="number" name="harga" value="<?php echo $filmEdit ? $filmEdit->getHarga() : (isset($formData['harga']) ? (int)$formData['harga'] : ''); ?>" placeholder="50000" <?php echo $filmEdit ? '' : 'required'; ?>>
                <label class="label-field">Durasi (Menit)</label>
                <input type="number" name="durasi" value="<?php echo $filmEdit ? $filmEdit->getDurasi() : (isset($formData['durasi']) ? (int)$formData['durasi'] : ''); ?>" placeholder="Contoh: 120" <?php echo $filmEdit ? '' : 'required'; ?>>
                <label class="label-field">Gambar (Path Lokal)</label>
                <input type="text" name="gambar" value="<?php echo $filmEdit ? htmlspecialchars($filmEdit->getGambar()) : (isset($formData['gambar']) ? htmlspecialchars($formData['gambar']) : ''); ?>" placeholder="Contoh: gambar/AOT.jpg" <?php echo $filmEdit ? '' : 'required'; ?>>
                <p style="font-size:12px;color:#888;margin-top:4px;">Masukkan path file gambar lokal (contoh: <b>gambar/AOT.jpg</b>), bukan URL internet.</p>

                <?php if ($filmEdit): ?>   <!-- jika sedang mode update -->
                    <!-- pilihan kolom yang mau diupdate (centang sesuai yang diinginkan) -->
                    <div class="kolom-update">
                        <div class="judul-kolom">Pilih kolom yang ingin diupdate:</div>
                        <label class="checkbox-label"><input type="checkbox" name="kolom[]" value="judul" checked> Judul</label>
                        <label class="checkbox-label"><input type="checkbox" name="kolom[]" value="genre" checked> Genre</label>
                        <label class="checkbox-label"><input type="checkbox" name="kolom[]" value="harga" checked> Harga</label>
                        <label class="checkbox-label"><input type="checkbox" name="kolom[]" value="durasi" checked> Durasi</label>
                        <label class="checkbox-label"><input type="checkbox" name="kolom[]" value="gambar" checked> Gambar</label>
                    </div>
                <?php endif; ?>

                <!-- tombol submit -->
                <button type="submit" class="btn <?php echo $filmEdit ? 'btn-simpan' : 'btn-tambah'; ?>">
                    <?php echo $filmEdit ? '💾 Simpan Perubahan' : '🚀 Tambah Film'; ?>
                </button>
                <?php if ($filmEdit): ?>   <!-- saat mode edit, sediakan tombol keluar dari mode mengedit -->
                    <!-- link keluar mode edit (tanpa menyimpan perubahan) -->
                    <a href="index.php" class="btn btn-batal">❌ Batal / Keluar dari Mode Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- form pencarian data film -->
        <div class="card">
            <h2>🔍 Cari Data Film</h2>
            <form method="GET">   <!-- form pencarian film -->
                <label class="label-field">ID</label>
                <input type="number" name="cari" value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : (isset($cariBackup) ? htmlspecialchars($cariBackup) : ''); ?>" placeholder="Masukkan ID film (1, 2, 3, ...)" min="1">
                <button type="submit" class="btn btn-cari">Cari Film</button>
            </form>
        </div>
    </div>

    <!-- daftar film dalam bentuk kartu -->
    <div class="list-head">
        <h2 style="font-size:20px;font-weight:700;">🗂️ Daftar Film</h2>
        <span class="count"><?php echo count($_SESSION['daftar_film']); ?> film</span>
    </div>

    <div class="film-grid">
        <?php if (count($_SESSION['daftar_film']) === 0): ?>   <!-- jika daftar kosong -->
            <div class="film-kosong">🎬 Belum ada data film. Silakan tambahkan terlebih dahulu.</div>
        <?php else: ?>
            <?php // urutkan daftar berdasarkan id (ascending) sebelum ditampilkan
            usort($_SESSION['daftar_film'], function ($a, $b) { return $a->getId() <=> $b->getId(); });
            foreach ($_SESSION['daftar_film'] as $film): ?>
            <div class="film-card">
                <div class="film-poster">
                    <?php echo tampilGambar($film->getGambar(), 220, 260); ?>
                </div>
                <div class="film-info">
                    <div class="film-id">ID ~ <?php echo $film->getId(); ?></div>
                    <div class="film-judul"><?php echo htmlspecialchars($film->getJudul()); ?></div>
                    <div class="film-genre">
                        <?php foreach ($film->getGenre() as $g): ?>
                            <span class="tag"><?php echo htmlspecialchars($g); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="film-harga">Rp <?php echo number_format($film->getHarga(), 0, ',', '.'); ?></div>
                    <div class="film-durasi">⏱ <?php echo formatDurasi($film->getDurasi()); ?></div>
                </div>
                <div class="film-aksi">
                    <a href="?edit=<?php echo $film->getId(); ?>" class="btn btn-edit">✏️ Edit</a>
                    <form method="POST" onsubmit="return confirm('Hapus film ini?');">
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id" value="<?php echo $film->getId(); ?>">
                        <button type="submit" class="btn btn-hapus">🗑️ Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="reset-wrap">
        <!-- form untuk menghapus semua data -->
        <form method="POST" onsubmit="return confirm('Hapus semua data?');">
            <input type="hidden" name="aksi" value="reset">   <!-- aksi reset -->
            <button type="submit" class="btn btn-reset">🗑️ Reset Semua Data</button>  <!-- tombol reset -->
        </form>
    </div>
</div>
</body>
</html>