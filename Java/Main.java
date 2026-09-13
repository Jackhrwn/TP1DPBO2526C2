import java.util.ArrayList;                 // import library untuk ArrayList (array dinamis berupa list of object)
import java.util.Scanner;                   // import library untuk membaca input dari keyboard
import java.util.InputMismatchException;    // import library untuk menangkap exception saat input angka salah

//=== Class Main ===
public class Main {
    // list of object untuk menyimpan semua objek Film
    private static ArrayList<Film> daftarFilm = new ArrayList<>();
    // objek Scanner untuk membaca input dari keyboard
    private static Scanner scanner = new Scanner(System.in);

    // Kode warna ANSI untuk tampilan berwarna di terminal
    private static final String WARNA_BIRU   = "\033[34m";   // warna biru (untuk garis border menu)
    private static final String WARNA_HIJAU  = "\033[32m";   // warna hijau (untuk pesan berhasil)
    private static final String WARNA_MERAH  = "\033[31m";   // warna merah (untuk pesan gagal)
    private static final String WARNA_KUNING = "\033[33m";   // warna kuning (untuk judul menu, daftar menu & data film)
    private static final String WARNA_RESET  = "\033[0m";    // reset warna ke normal

    // Method teksHijau: membungkus teks dengan warna hijau (pesan berhasil)
    private static String teksHijau(String teks) {
        return WARNA_HIJAU + teks + WARNA_RESET;
    }

    // Method teksMerah: membungkus teks dengan warna merah (pesan gagal)
    private static String teksMerah(String teks) {
        return WARNA_MERAH + teks + WARNA_RESET;
    }

    // Method cetakGaris: mencetak satu garis border, misal +===+
    private static void cetakGaris(String kiri, String tengah, String kanan, int panjang) {
        System.out.print(kiri);
        for (int i = 0; i < panjang; i++) System.out.print(tengah);
        System.out.println(kanan);
    }

    // Method cetakJudul: mencetak judul di tengah kotak dengan border
    // (border "|" biru, judul kuning)
    private static void cetakJudul(String judul, int panjang) {
        int kiri = (panjang - judul.length()) / 2;             // hitung jarak spasi kiri
        System.out.print(WARNA_BIRU + "|" + WARNA_RESET);      // border kiri (biru)
        System.out.print(WARNA_KUNING);                        // judul (kuning)
        for (int i = 0; i < kiri; i++) System.out.print(" ");
        System.out.print(judul);
        for (int i = kiri + judul.length(); i < panjang; i++) System.out.print(" ");
        System.out.print(WARNA_RESET);
        System.out.println(WARNA_BIRU + "|" + WARNA_RESET);    // border kanan (biru)
    }

    // Method cetakBaris: mencetak satu baris isi dengan border kiri-kanan
    private static void cetakBaris(String isi, int panjang) {
        System.out.print(WARNA_BIRU + "|" + WARNA_RESET);      // border kiri (biru)
        System.out.print(WARNA_KUNING + isi + WARNA_RESET);    // isi (kuning)
        for (int i = isi.length(); i < panjang; i++) System.out.print(" ");  // spasi penggenap
        System.out.println(WARNA_BIRU + "|" + WARNA_RESET);    // border kanan (biru)
    }

    // Method inputAngka: membaca angka dengan ERROR HANDLING
    // (program tidak akan crash jika user menginput huruf/string)
    private static int inputAngka(String pesan) {
        while (true) {                              // ulangi terus sampai input valid
            System.out.print(pesan);                // tampilkan prompt (seperti input(pesan) di Python)
            String input = scanner.nextLine();      // baca satu baris penuh (seperti input() di Python)
            boolean valid = !input.isEmpty();       // asumsikan valid jika tidak kosong
            for (int i = 0; i < input.length(); i++) {  // periksa tiap karakter
                char c = input.charAt(i);           // ambil satu karakter
                if (!Character.isDigit(c)) {        // jika karakter bukan angka
                    valid = false;                  // baris ini bukan angka
                    break;                          // hentikan pemeriksaan
                }
            }
            if (valid) {                            // jika seluruh baris adalah angka
                int angka = 0;                      // hasil ubah teks menjadi bilangan bulat
                for (int i = 0; i < input.length(); i++) {  // susun angka dari tiap digit
                    angka = angka * 10 + (input.charAt(i) - '0');
                }
                return angka;                       // kembalikan angka yang valid
            }
            // pesan error lalu ulangi minta input (sama seperti Python)
            System.out.println(teksMerah("Input harus berupa angka! Silakan coba lagi.\n"));
        }
    }

    // Method cekIdValid: memeriksa apakah id berupa angka mulai dari 1 (1, 2, 3, ...)
    private static boolean cekIdValid(int id) {
        return id >= 1;                            // valid jika id lebih besar atau sama dengan 1
    }

    // Method cekIdAda: memeriksa apakah sebuah id sudah ada di dalam daftar
    private static boolean cekIdAda(int id) {
        boolean ketemu = false;                    // penanda hasil pencarian
        for (Film film : daftarFilm) {             // ulangi semua objek dalam daftar
            if (film.getId() == id) {              // jika id objek sama dengan id cari
                ketemu = true;                     // tandai ketemu 
            }
        }
        return ketemu;                             // kembalikan hasil
    }

    // Method tambahData: menambahkan objek Film baru ke dalam daftar
    private static void tambahData() {
        int id;                                        // variabel id (angka mulai dari 1)
        while (true) {                                 // ulangi sampai id valid
            id = inputAngka("Masukkan ID      : ");    // baca id (angka) dengan error handling
            if (cekIdValid(id)) {                      // jika id valid (mulai dari 1)
                break;                                 // lanjut ke langkah berikutnya
            }
            System.out.println(teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"));  // pesan error
        }
        if (cekIdAda(id)) {                        // jika id sudah dipakai
            System.out.println(teksMerah("ID sudah digunakan!"));  // beri tahu user
            return;                                // kembali ke menu utama
        }
        System.out.print("Masukkan Judul   : ");   // minta input judul
        String judul = scanner.nextLine();         // baca judul
        int jumlahGenre;                           // banyaknya genre yang ingin dimasukkan
        while (true) {                             // ulangi sampai jumlah benar
            jumlahGenre = inputAngka("Jumlah Genre     : ");  // baca jumlah genre (angka)
            if (jumlahGenre >= 1) break;           // jumlah benar (mulai dari 1)
            System.out.println(teksMerah("Jumlah genre harus angka mulai dari 1!\n"));  // pesan error
        }
        ArrayList<String> genre = new ArrayList<>();  // list genre
        for (int i = 0; i < jumlahGenre; i++) {  // minta genre satu per satu
            while (true) {                       // ulangi sampai genre ini valid
                System.out.print("Genre ke-" + (i + 1) + "       : ");  // minta genre ke-i
                String g = scanner.nextLine().trim();  // baca genre tanpa spasi
                if (!g.isEmpty() && Character.isUpperCase(g.charAt(0))) {  // jika awal huruf besar
                    genre.add(g);                // simpan genre
                    break;                       // lanjut ke genre berikutnya
                }
                System.out.println(teksMerah("Genre harus diawali dengan huruf besar (contoh: Action)!\n"));  // pesan error
            }
        }
        int harga = inputAngka("Harga Tiket      : ");  // baca harga dengan error handling
        int durasi = inputAngka("Durasi (menit)   : "); // baca durasi dengan error handling

        Film baru = new Film(id, judul, genre, harga, durasi); // buat objek Film baru
        daftarFilm.add(baru);                                  // simpan ke dalam list
        System.out.println(teksHijau("Data berhasil ditambahkan!"));      // konfirmasi sukses
    }

    // Method tampilkanData: menampilkan semua objek Film dalam daftar
    private static void tampilkanData() {
        if (daftarFilm.isEmpty()) {            // jika daftar masih kosong
            System.out.println("Belum ada data film.");  // tampilkan pesan kosong
            return;                            // hentikan proses
        }
        System.out.println();                  // cetak baris kosong
        System.out.print(WARNA_BIRU);          // border atas daftar (biru)
        cetakGaris("+", "=", "+", 42);
        System.out.print(WARNA_KUNING);        // judul daftar (kuning)
        cetakJudul("DAFTAR FILM", 42);
        System.out.print(WARNA_BIRU);          // border bawah daftar (biru)
        cetakGaris("+", "=", "+", 42);
        System.out.print(WARNA_RESET);         // reset warna
        System.out.println();                  // cetak baris kosong
        // urutkan daftar berdasarkan id (ascending) sebelum ditampilkan
        daftarFilm.sort((a, b) -> Integer.compare(a.getId(), b.getId()));
        int no = 1;                            // nomor urut film
        for (Film film : daftarFilm) {         // ulangi semua objek dalam daftar
            System.out.println(WARNA_KUNING + "Film ke-" + no + ":" + WARNA_RESET);  // nomor urut film (kuning)
            film.tampilkan();                  // panggil method tampilkan
            no++;                              // naikkan nomor urut
        }
    }

    // Method tampilkanMenuUpdate: menampilkan submenu pilihan kolom yang diupdate
    private static void tampilkanMenuUpdate() {
        System.out.print(WARNA_BIRU);           // border atas submenu (biru)
        cetakGaris("+", "=", "+", 38);
        System.out.print(WARNA_KUNING);         // judul submenu (kuning)
        cetakJudul("PILIH KOLOM UNTUK UPDATE", 38);
        System.out.print(WARNA_BIRU);           // garis pemisah (biru)
        cetakGaris("+", "=", "+", 38);
        System.out.print(WARNA_KUNING);         // pilihan kolom (kuning)
        cetakBaris("  [1] Ubah Judul", 38);     // pilihan 1
        cetakBaris("  [2] Ubah Genre", 38);     // pilihan 2
        cetakBaris("  [3] Ubah Harga", 38);     // pilihan 3
        cetakBaris("  [4] Ubah Durasi", 38);    // pilihan 4
        cetakBaris("  [5] Selesai Update", 38); // pilihan 5
        System.out.print(WARNA_BIRU);           // border bawah submenu (biru)
        cetakGaris("+", "=", "+", 38);
        System.out.print(WARNA_RESET);          // reset warna
    }

    // Method updateData: mengubah data objek Film berdasarkan id dengan pilihan kolom
    private static void updateData() {
        int id;                                        // variabel id sasaran (angka mulai dari 1)
        while (true) {                             // ulangi sampai id valid
            id = inputAngka("Masukkan ID yang akan diupdate : ");  // baca id sasaran (angka) dengan error handling
            if (cekIdValid(id)) {                  // jika id valid (mulai dari 1)
                break;                             // lanjut ke langkah berikutnya
            }
            System.out.println(teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"));  // pesan error
        }

        // cari objek sasaran 
        Film filmTarget = null;                    // objek sasaran (default belum ketemu)
        for (Film film : daftarFilm) {             // ulangi semua objek dalam daftar
            if (film.getId() == id) {              // jika id objek cocok dengan sasaran
                filmTarget = film;                 // simpan objek sasaran
            }
        }

        if (filmTarget == null) {                  // jika tidak ada yang cocok
            System.out.println(teksMerah("ID tidak ditemukan!"));  // beri tahu user
            return;                                // kembali ke menu utama
        }

        int pilihan;                                // variabel pilihan kolom
        do {                                        // ulangi sampai user memilih selesai
            // tampilkan data film saat ini
            System.out.println("\nData film saat ini:");
            System.out.println();
            filmTarget.tampilkan();

            // tampilkan submenu pilihan kolom
            tampilkanMenuUpdate();
            pilihan = inputAngka("Pilih kolom (1-5) : ");    // baca pilihan dengan error handling

            switch (pilihan) {                                  // jalankan sesuai pilihan kolom
                case 1:                                         // ubah judul
                    System.out.print("Judul baru        : ");   // minta judul baru
                    String judulBaru = scanner.nextLine();      // baca judul baru
                    filmTarget.setJudul(judulBaru);             // ubah judul lewat setter
                    System.out.println(teksHijau("Judul berhasil diubah!"));  // konfirmasi
                    break;                                      // keluar dari switch
                case 2:                                         // ubah genre
                    int jumlahGenreBaru;                        // banyaknya genre baru
                    while (true) {                              // ulangi sampai jumlah benar
                        jumlahGenreBaru = inputAngka("Jumlah Genre      : ");  // baca jumlah genre (angka)
                        if (jumlahGenreBaru >= 1) break;        // jumlah benar (mulai dari 1)
                        System.out.println(teksMerah("Jumlah genre harus angka mulai dari 1!\n"));  // pesan error
                    }
                    ArrayList<String> genreBaru = new ArrayList<>();  // list genre
                    for (int i = 0; i < jumlahGenreBaru; i++) {  // minta genre satu per satu
                        while (true) {                       // ulangi sampai genre ini valid
                            System.out.print("Genre ke-" + (i + 1) + "        : ");  // minta genre ke-i
                            String g = scanner.nextLine().trim();  // baca genre tanpa spasi
                            if (!g.isEmpty() && Character.isUpperCase(g.charAt(0))) {  // jika awal huruf besar
                                genreBaru.add(g);            // simpan genre
                                break;                       // lanjut ke genre berikutnya
                            }
                            System.out.println(teksMerah("Genre harus diawali dengan huruf besar (contoh: Action)!\n"));  // pesan error
                        }
                    }
                    filmTarget.setGenre(genreBaru);             // ubah genre lewat setter
                    System.out.println(teksHijau("Genre berhasil diubah!"));  // konfirmasi
                    break;                                      // keluar dari switch
                case 3:                                         // ubah harga
                    int hargaBaru = inputAngka("Harga baru        : ");   // baca harga baru
                    filmTarget.setHarga(hargaBaru);             // ubah harga lewat setter
                    System.out.println(teksHijau("Harga berhasil diubah!"));  // konfirmasi
                    break;                                      // keluar dari switch
                case 4:                                         // ubah durasi
                    int durasiBaru = inputAngka("Durasi baru(menit): ");  // baca durasi baru
                    filmTarget.setDurasi(durasiBaru);           // ubah durasi lewat setter
                    System.out.println(teksHijau("Durasi berhasil diubah!"));  // konfirmasi
                    break;                                      // keluar dari switch
                case 5:                                         // selesai update
                    System.out.println(teksHijau("Update selesai."));      // pesan selesai
                    break;                                      // keluar dari switch
                default:                                        // pilihan tidak valid
                    System.out.println(teksMerah("Pilihan tidak valid!")); // pesan salah
            }
        } while (pilihan != 5);                                 // ulangi selama belum memilih selesai
    }

    // Method hapusData: menghapus objek Film dari daftar berdasarkan id
    private static void hapusData() {
        int id;                                    // variabel id sasaran (angka mulai dari 1)
        while (true) {                             // ulangi sampai id valid
            id = inputAngka("Masukkan ID yang akan dihapus : ");  // baca id sasaran (angka) dengan error handling
            if (cekIdValid(id)) {                  // jika id valid (mulai dari 1)
                break;                             // lanjut ke langkah berikutnya
            }
            System.out.println(teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"));  // pesan error
        }

        // cari index yang akan dihapus 
        int indexHapus = -1;                       // index sasaran (default belum ketemu)
        for (int i = 0; i < daftarFilm.size(); i++) {  // ulangi dengan index
            if (daftarFilm.get(i).getId() == id) { // jika id cocok
                indexHapus = i;                    // simpan index sasaran
            }
        }

        if (indexHapus == -1) {                    // jika tidak ada yang cocok
            System.out.println(teksMerah("ID tidak ditemukan!"));  // id tidak ada di daftar
            return;                                // kembali ke menu utama
        }

        daftarFilm.remove(indexHapus);             // hapus objek pada index tersebut
        System.out.println(teksHijau("Data berhasil dihapus!"));  // konfirmasi sukses
    }

    // Method cariData: mencari satu objek Film berdasarkan id
    private static void cariData() {
        int id;                                        // variabel id pencarian (angka mulai dari 1)
        while (true) {                             // ulangi sampai id valid
            id = inputAngka("Masukkan ID yang dicari : ");  // baca id pencarian (angka) dengan error handling
            if (cekIdValid(id)) {                  // jika id valid (mulai dari 1)
                break;                             // lanjut ke langkah berikutnya
            }
            System.out.println(teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"));  // pesan error
        }

        // cari objek yang cocok 
        Film filmCari = null;                      // objek hasil (default belum ketemu)
        for (Film film : daftarFilm) {             // ulangi semua objek dalam daftar
            if (film.getId() == id) {              // jika id cocok dengan pencarian
                filmCari = film;                   // simpan objek hasil
            }
        }

        if (filmCari != null) {                    // jika ditemukan
            System.out.println(teksHijau("Film ditemukan:")); // tampilkan pesan ketemu
            filmCari.tampilkan();                  // tampilkan detail film
        } else {                                   // jika tidak ditemukan
            System.out.println(teksMerah("Film dengan ID '" + id + "' tidak ditemukan!"));  // pesan tidak ketemu
        }
    }

    // Method tampilkanMenu: menampilkan daftar menu utama 
    private static void tampilkanMenu() {
        System.out.println();                             // cetak baris kosong
        System.out.print(WARNA_BIRU);                     // border atas menu (biru)
        cetakGaris("+", "=", "+", 36);
        System.out.print(WARNA_KUNING);                   // judul menu (kuning)
        cetakJudul("MENU BIOSKOP", 36);
        System.out.print(WARNA_BIRU);                     // garis pemisah (biru)
        cetakGaris("+", "=", "+", 36);
        System.out.print(WARNA_KUNING);                   // daftar menu (kuning)
        cetakBaris("  [1] Tambah Data Film", 36);         // menu tambah
        cetakBaris("  [2] Tampilkan Data Film", 36);      // menu tampil
        cetakBaris("  [3] Update Data Film", 36);         // menu update
        cetakBaris("  [4] Hapus Data Film", 36);          // menu hapus
        cetakBaris("  [5] Cari Data Film", 36);           // menu cari
        cetakBaris("  [6] Keluar", 36);                   // menu keluar
        System.out.print(WARNA_BIRU);                     // border bawah menu (biru)
        cetakGaris("+", "=", "+", 36);
        System.out.print(WARNA_RESET);                    // reset warna
    }

    // Method main
    public static void main(String[] args) {
        int pilihan;                       // variabel pilihan menu
        do {                               // ulangi tampilan menu
            tampilkanMenu();               // tampilkan daftar menu
            pilihan = inputAngka("Pilih menu (1-6) : ");   // baca pilihan dengan error handling

            switch (pilihan) {             // pilih aksi berdasarkan angka pilihan
                case 1:                    // jika memilih 1
                    tambahData();          // panggil method tambahData
                    break;                 // keluar dari switch
                case 2:                    // jika memilih 2
                    tampilkanData();       // panggil method tampilkanData
                    break;                 // keluar dari switch
                case 3:                    // jika memilih 3
                    updateData();          // panggil method updateData
                    break;                 // keluar dari switch
                case 4:                    // jika memilih 4
                    hapusData();           // panggil method hapusData
                    break;                 // keluar dari switch
                case 5:                    // jika memilih 5
                    cariData();            // panggil method cariData
                    break;                 // keluar dari switch
                case 6:                    // jika memilih 6
                    System.out.println(teksHijau("Program selesai. Terima kasih!"));  // pesan keluar
                    break;                 // keluar dari switch
                default:                   // jika pilihan bukan 1-6
                    System.out.println(teksMerah("Pilihan tidak valid!"));  // pesan pilihan salah
            }
        } while (pilihan != 6);            // ulangi selama belum memilih 6 (keluar)
    }
}