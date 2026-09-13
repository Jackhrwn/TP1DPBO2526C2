#include <bits/stdc++.h>
#include "bioskop.cpp"            

using namespace std;    // agar tidak perlu menulis std:: berulang kali            

// Fungsi teksHijau: membungkus teks dengan warna hijau (pesan berhasil)
string teksHijau(string teks) {
    return string(WARNA_HIJAU) + teks + WARNA_RESET;
}

// Fungsi teksMerah: membungkus teks dengan warna merah (pesan gagal)
string teksMerah(string teks) {
    return string(WARNA_MERAH) + teks + WARNA_RESET;
}

// ====== FUNGSI TAMPILAN GARIS ======
// Fungsi cetakGaris: mencetak satu garis border, misal +===+
void cetakGaris(string kiri, string tengah, string kanan, int panjang) {
    cout << kiri;                                   // cetak karakter kiri
    for (int i = 0; i < panjang; i++) {             // ulangi sampai panjang
        cout << tengah;                             // cetak karakter tengah
    }
    cout << kanan << endl;                          // cetak karakter kanan
}

// Fungsi cetakBaris: mencetak satu baris isi dengan border kiri kanan
void cetakBaris(string isi, int panjang) {
    cout << WARNA_BIRU << "|" << WARNA_RESET;           // border kiri (biru)
    cout << WARNA_KUNING << isi << WARNA_RESET;         // isi (kuning)
    for (int i = (int)isi.size(); i < panjang; i++) {   // rapikan dengan spasi
        cout << " ";                                    // spasi penggenap
    }
    cout << WARNA_BIRU << "|" << WARNA_RESET << endl;   // border kanan (biru)
}

// Fungsi cetakJudul: mencetak judul di tengah kotak dengan border
void cetakJudul(string judul, int panjang) {
    int kiri = (panjang - (int)judul.size()) / 2;       // hitung jarak spasi kiri
    cout << WARNA_BIRU << "|" << WARNA_RESET;           // border kiri (biru)
    cout << WARNA_KUNING;                               // judul (kuning)
    for (int i = 0; i < kiri; i++) {                    // spasi kiri
        cout << " ";
    }
    cout << judul;                                      // cetak judul
    for (int i = kiri + (int)judul.size(); i < panjang; i++) {  // spasi kanan
        cout << " ";
    }
    cout << WARNA_RESET;
    cout << WARNA_BIRU << "|" << WARNA_RESET << endl;   // border kanan (biru)
}

// Fungsi cekIdValid: memeriksa apakah id berupa angka mulai dari 1 (1, 2, 3, ...)
bool cekIdValid(int id) {
    return id >= 1;                                       // valid jika id lebih besar atau sama dengan 1
}

// Fungsi cekIdAda: memeriksa apakah sebuah id sudah ada di dalam daftar
bool cekIdAda(vector<Film>& daftar, int id) {
    bool ketemu = false;                                  // penanda hasil pencarian
    for (int i = 0; i < daftar.size(); i++) {             // ulangi semua elemen daftar
        if (daftar[i].getId() == id) {                    // jika id elemen sama dengan id cari
            ketemu = true;                                // tandai ketemu (tanpa break)
        }
    }
    return ketemu;                                        // kembalikan hasil
}

// Fungsi inputAngka: membaca angka dari keyboard dengan ERROR HANDLING
// (program tidak akan crash jika user mengetik huruf/string)
int inputAngka(string pesan) {
    while (true) {                                        // ulangi terus sampai input valid
        cout << pesan;                                    // tampilkan prompt (seperti input(pesan) di Python)
        string input;                                     // variabel teks input mentah
        getline(cin, input);                              // baca satu baris penuh (seperti input() di Python)
        bool valid = !input.empty();                      // asumsikan valid jika tidak kosong
        for (size_t i = 0; i < input.size(); i++) {       // periksa tiap karakter
            if (!isdigit((unsigned char)input[i])) {      // jika ada karakter bukan angka
                valid = false;                            // baris ini bukan angka
                break;                                    // hentikan pemeriksaan
            }
        }
        if (valid) {                                      // jika seluruh baris adalah angka
            int angka = 0;                                // hasil ubah teks menjadi bilangan bulat
            for (size_t i = 0; i < input.size(); i++) {   // susun angka dari tiap digit
                angka = angka * 10 + (input[i] - '0');
            }
            return angka;                                 // kembalikan angka yang valid
        }
        // pesan error lalu ulangi minta input (sama seperti Python)
        cout << teksMerah("Input harus berupa angka! Silakan coba lagi.\n\n");
    }
}

// Fungsi tambahData: menambahkan objek Film baru ke dalam daftar lalu simpan ke file
void tambahData(vector<Film>& daftar) {
    string judul;                                         // variabel untuk judul
    vector<string> genre;                                 // list genre (bisa lebih dari satu)
    int id;                                               // variabel untuk id (angka mulai dari 1)
    int harga;                                            // variabel untuk data harga
    int durasi;                                           // variabel untuk data durasi

    while (true) {                                        // ulangi sampai id valid
        id = inputAngka("Masukkan ID      : ");           // baca id (angka) dengan error handling
        if (cekIdValid(id)) {                             // jika id valid (mulai dari 1)
            break;                                        // lanjut ke langkah berikutnya
        }
        cout << teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n") << endl; // pesan error
    }
    if (cekIdAda(daftar, id)) {                           // jika id sudah dipakai
        cout << teksMerah("ID sudah digunakan!") << endl; // beri tahu user
        return;                                           // kembali ke menu utama
    }
    cout << "Masukkan Judul   : ";                        // minta input judul
    getline(cin, judul);                                  // baca judul (boleh spasi)
    int jumlahGenre;                                      // banyaknya genre yang ingin dimasukkan
    while (true) {                                        // ulangi sampai jumlah benar
        jumlahGenre = inputAngka("Jumlah Genre     : ");  // minta jumlah genre
        if (jumlahGenre >= 1) break;                      // jumlah benar (mulai dari 1)
        cout << teksMerah("Jumlah genre harus angka mulai dari 1!\n") << endl; // pesan error
    }
    genre.clear();                                        // kosongkan list sebelum mengisi
    for (int i = 0; i < jumlahGenre; i++) {               // minta genre satu per satu
        while (true) {                                    // ulangi sampai genre ini valid
            cout << "Genre ke-" << (i + 1) << "       : ";// minta genre ke-i
            string g;                                     // variabel genre tunggal
            getline(cin, g);                              // baca genre (boleh spasi)
            if (!g.empty() && isupper((unsigned char)g[0])) {  // jika awal huruf besar
                genre.push_back(g);                       // simpan genre
                break;                                    // lanjut ke genre berikutnya
            }
            cout << teksMerah("Genre harus diawali dengan huruf besar (contoh: Action)!\n") << endl; // pesan error
        }
    }
    harga = inputAngka("Harga Tiket      : ");            // baca harga dengan error handling
    durasi = inputAngka("Durasi (menit)   : ");           // baca durasi dengan error handling

    Film baru(id, judul, genre, harga, durasi);           // buat objek Film baru
    daftar.push_back(baru);                               // simpan objek ke dalam vector
    cout << teksHijau("Data berhasil ditambahkan!") << endl;         // konfirmasi sukses
}

// Fungsi cetakJudulBaris: mencetak judul bagian dalam kotak (misal DAFTAR FILM)
void cetakJudulBaris(string judul) {
    int panjang = 42;                                     // lebar kotak judul
    cout << WARNA_BIRU;                                   // border atas (biru)
    cetakGaris("+", "=", "+", panjang);
    cout << WARNA_KUNING;                                 // judul di tengah (kuning)
    cetakJudul(judul, panjang);
    cout << WARNA_BIRU;                                   // border bawah (biru)
    cetakGaris("+", "=", "+", panjang);
    cout << WARNA_RESET;                                  // reset warna
    cout << endl;                                         // baris kosong
}

// Fungsi tampilkanData: menampilkan semua objek Film dalam daftar
void tampilkanData(vector<Film>& daftar) {
    if (daftar.empty()) {                                 // jika daftar masih kosong
        cout << "Belum ada data film." << endl;           // tampilkan pesan kosong
        return;                                           // hentikan proses
    }
    cout << endl;                                         // cetak baris kosong
    cetakJudulBaris("DAFTAR FILM");                       // judul daftar dengan kotak
    // urutkan daftar berdasarkan id (ascending) sebelum ditampilkan
    sort(daftar.begin(), daftar.end(), [](Film& a, Film& b) { return a.getId() < b.getId(); });
    for (int i = 0; i < daftar.size(); i++) {             // ulangi semua elemen daftar
        cout << WARNA_KUNING << "Film ke-" << (i + 1) << ":" << WARNA_RESET << endl;     // nomor urut film (kuning)
        daftar[i].tampilkan();                            // panggil method tampilkan
    }
}

// Fungsi tampilkanMenuUpdate: menampilkan submenu pilihan kolom yang akan diupdate
void tampilkanMenuUpdate() {
    cout << WARNA_BIRU;                                   // border atas submenu (biru)        
    cetakGaris("+", "=", "+", 38);
    cout << WARNA_KUNING;                                 // judul submenu (kuning)   
    cetakJudul("PILIH KOLOM UNTUK UPDATE", 38);
    cout << WARNA_BIRU;                                   // garis pemisah (biru)  
    cetakGaris("+", "=", "+", 38);
    cout << WARNA_KUNING;                                 // pilihan kolom (kuning)
    cetakBaris("  [1] Ubah Judul", 38);                   // pilihan 1
    cetakBaris("  [2] Ubah Genre", 38);                   // pilihan 2   
    cetakBaris("  [3] Ubah Harga", 38);                   // pilihan 3   
    cetakBaris("  [4] Ubah Durasi", 38);                  // pilihan 4 
    cetakBaris("  [5] Selesai Update", 38);               // pilihan 5
    cout << WARNA_BIRU;                                   // border bawah submenu (biru)
    cetakGaris("+", "=", "+", 38);
    cout << WARNA_RESET;                                  // reset warna
}

// Fungsi updateData: mengubah data objek Film berdasarkan id dengan pilihan kolom
void updateData(vector<Film>& daftar) {
    int id;                                               // variabel untuk id sasaran (angka)

    while (true) {                                        // ulangi sampai id valid
        id = inputAngka("Masukkan ID yang akan diupdate : "); // baca id sasaran (angka)
        if (cekIdValid(id)) {                             // jika id valid (mulai dari 1)
            break;                                        // lanjut ke langkah berikutnya
        }
        cout << teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n") << endl; // pesan error
    }

    // cari index film sasaran (perulangan dijalankan sampai selesai, tanpa break)
    int idxSasaran = -1;                                              // index sasaran (default belum ketemu)
    for (int i = 0; i < daftar.size(); i++) {                         // ulangi semua elemen daftar
        if (daftar[i].getId() == id) {                                // jika id cocok dengan sasaran
            idxSasaran = i;                                           // simpan index sasaran
        }
    }

    if (idxSasaran == -1) {                                           // jika tidak ada yang cocok
        cout << teksMerah("ID tidak ditemukan!") << endl;             // beri tahu user
        return;                                                       // kembali ke menu utama
    }

    int pilihan;                                                      // variabel pilihan kolom
    do {                                                              // ulangi sampai pilih selesai
        // tampilkan data saat ini milik film yang dicari
        cout << "\nData film saat ini:\n" << endl;
        daftar[idxSasaran].tampilkan();                               // tampilkan detail film sasaran

        // tampilkan submenu pilihan kolom yang mau diupdate
        tampilkanMenuUpdate();
        pilihan = inputAngka("Pilih kolom (1-5) : ");                 // baca pilihan kolom

        switch (pilihan) {                                            // jalankan sesuai pilihan
        case 1: {                                                     // ubah judul
            string judulBaru;                                         // variabel judul baru
            cout << "Judul baru        : ";                           // minta input judul baru
            getline(cin, judulBaru);                                  // baca judul baru
            daftar[idxSasaran].setJudul(judulBaru);                   // ubah judul lewat setter
            cout << teksHijau("Judul berhasil diubah!") << endl;      // konfirmasi sukses
            break;                                                    // keluar dari switch
        }
        case 2: {                                                     // ubah genre
            int jumlahGenre;                                          // banyaknya genre baru
            while (true) {                                            // ulangi sampai jumlah benar
                jumlahGenre = inputAngka("Jumlah Genre      : ");     // minta jumlah genre
                if (jumlahGenre >= 1) break;                          // jumlah benar (mulai dari 1)
                cout << teksMerah("Jumlah genre harus angka mulai dari 1!\n") << endl; // pesan error
            }
            vector<string> genreBaru;                                 // list genre baru
            genreBaru.clear();                                        // kosongkan list sebelum mengisi
            for (int i = 0; i < jumlahGenre; i++) {                   // minta genre satu per satu
                while (true) {                                        // ulangi sampai genre ini valid
                    cout << "Genre ke-" << (i + 1) << "        : ";   // minta genre ke-i
                    string g;                                         // variabel genre tunggal
                    getline(cin, g);                                  // baca genre (boleh spasi)
                    if (!g.empty() && isupper((unsigned char)g[0])) { // jika awal huruf besar
                        genreBaru.push_back(g);                       // simpan genre
                        break;                                        // lanjut ke genre berikutnya
                    }
                    cout << teksMerah("Genre harus diawali dengan huruf besar (contoh: Action)!'\n") << endl; // pesan error
                }
            }
            daftar[idxSasaran].setGenre(genreBaru);                   // ubah genre lewat setter
            cout << teksHijau("Genre berhasil diubah!") << endl;      // konfirmasi sukses
            break;                                                    // keluar dari switch
        }
        case 3: {                                                     // ubah harga
            int hargaBaru;                                            // variabel harga baru
            hargaBaru = inputAngka("Harga baru        : ");           // baca harga baru
            daftar[idxSasaran].setHarga(hargaBaru);                   // ubah harga lewat setter
            cout << teksHijau("Harga berhasil diubah!") << endl;      // konfirmasi sukses
            break;                                                    // keluar dari switch
        }
        case 4: {                                                     // ubah durasi
            int durasiBaru;                                           // variabel durasi baru
            durasiBaru = inputAngka("Durasi baru(menit): ");          // baca durasi baru
            daftar[idxSasaran].setDurasi(durasiBaru);                 // ubah durasi lewat setter
            cout << teksHijau("Durasi berhasil diubah!") << endl;     // konfirmasi sukses
            break;                                                    // keluar dari switch
        }
        case 5:                                                       // selesai update
            cout << teksHijau("Update selesai.") << endl;             // pesan selesai
            break;                                                    // keluar dari switch
        default:                                                      // pilihan tidak valid
            cout << teksMerah("Pilihan tidak valid!") << endl;        // pesan salah
        }
    } while (pilihan != 5);                                           // ulangi selama belum selesai
}

// Fungsi hapusData: menghapus objek Film dari daftar berdasarkan id lalu simpan ke file
void hapusData(vector<Film>& daftar) {
    int id;                                               // variabel untuk id yang akan dihapus (angka)

    while (true) {                                        // ulangi sampai id valid
        id = inputAngka("Masukkan ID yang akan dihapus : "); // baca id sasaran (angka)
        if (cekIdValid(id)) {                             // jika id valid (mulai dari 1)
            break;                                        // lanjut ke langkah berikutnya
        }
        cout << teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n") << endl; // pesan error
    }

    // cari index yang akan dihapus (perulangan dijalankan sampai selesai, tanpa break)
    int idxHapus = -1;                                    // index sasaran (default belum ketemu)
    for (int i = 0; i < daftar.size(); i++) {             // ulangi semua elemen daftar
        if (daftar[i].getId() == id) {                    // jika id cocok dengan sasaran
            idxHapus = i;                                 // simpan index sasaran
        }
    }

    if (idxHapus == -1) {                                 // jika tidak ada yang cocok
        cout << teksMerah("ID tidak ditemukan!") << endl;            // id tidak ada di daftar
        return;                                           // kembali ke menu utama
    }

    daftar.erase(daftar.begin() + idxHapus);              // hapus elemen pada index ke-idxHapus
    cout << teksHijau("Data berhasil dihapus!") << endl;  // konfirmasi sukses
}

// Fungsi cariData: mencari satu objek Film berdasarkan id
void cariData(vector<Film>& daftar) {
    int id;                                               // variabel untuk id yang dicari (angka)

    while (true) {                                        // ulangi sampai id valid
        id = inputAngka("Masukkan ID yang dicari : ");    // baca id (angka)
        if (cekIdValid(id)) {                             // jika id valid (mulai dari 1)
            break;                                        // lanjut ke langkah berikutnya
        }
        cout << teksMerah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n") << endl; // pesan error
    }

    // cari film yang cocok (perulangan dijalankan sampai selesai, tanpa break)
    int idxCari = -1;                                     // index hasil (default belum ketemu)
    for (int i = 0; i < daftar.size(); i++) {             // ulangi semua elemen daftar
        if (daftar[i].getId() == id) {                    // jika id cocok
            idxCari = i;                                  // simpan index hasil
        }
    }

    if (idxCari != -1) {                                  // jika ditemukan
        cout << teksHijau("Film ditemukan:") << endl;     // tampilkan pesan ketemu
        daftar[idxCari].tampilkan();                      // tampilkan detail film
    } else {                                              // jika tidak ditemukan
        cout << teksMerah("Film dengan ID '" + to_string(id) + "' tidak ditemukan!") << endl; // pesan tidak ketemu
    }
}

// Fungsi tampilkanMenu: menampilkan daftar menu utama dengan tampilan menarik
void tampilkanMenu() {
    cout << endl;                                         // cetak baris kosong
    cout << WARNA_BIRU;                                   // border atas menu (biru)
    cetakGaris("+", "=", "+", 36);
    cout << WARNA_KUNING;                                 // judul menu (kuning)
    cetakJudul("MENU BIOSKOP", 36);
    cout << WARNA_BIRU;                                   // garis pemisah (biru)
    cetakGaris("+", "=", "+", 36);
    cout << WARNA_KUNING;                                 // daftar menu (kuning)
    cetakBaris("  [1] Tambah Data Film", 36);             // menu tambah
    cetakBaris("  [2] Tampilkan Data Film", 36);          // menu tampil
    cetakBaris("  [3] Update Data Film", 36);             // menu update
    cetakBaris("  [4] Hapus Data Film", 36);              // menu hapus
    cetakBaris("  [5] Cari Data Film", 36);               // menu cari
    cetakBaris("  [6] Keluar", 36);                       // menu keluar
    cout << WARNA_BIRU;                                   // border bawah menu (biru)
    cetakGaris("+", "=", "+", 36);
    cout << WARNA_RESET;                                  // reset warna
}

int main() {
    vector<Film> daftarFilm;            // buat vector kosong untuk menyimpan film
    int pilihan;                        // variabel pilihan menu

    do {                                // ulangi tampilan menu
        tampilkanMenu();                // tampilkan daftar menu
        pilihan = inputAngka("Pilih menu (1-6) : ");      // baca pilihan dengan error handling

        switch (pilihan) {              // pilih aksi berdasarkan angka pilihan
        case 1:                         // jika memilih 1
            tambahData(daftarFilm);     // panggil fungsi tambahData
            break;                      // keluar dari switch
        case 2:                         // jika memilih 2
            tampilkanData(daftarFilm);  // panggil fungsi tampilkanData
            break;                      // keluar dari switch
        case 3:                         // jika memilih 3
            updateData(daftarFilm);     // panggil fungsi updateData
            break;                      // keluar dari switch
        case 4:                         // jika memilih 4
            hapusData(daftarFilm);      // panggil fungsi hapusData
            break;                      // keluar dari switch
        case 5:                         // jika memilih 5
            cariData(daftarFilm);       // panggil fungsi cariData
            break;                      // keluar dari switch
        case 6:                         // jika memilih 6
            cout << teksHijau("Program selesai. Terima kasih!") << endl; // pesan keluar
            break;                      // keluar dari switch
        default:                        // jika pilihan bukan 1-6
            cout << teksMerah("Pilihan tidak valid!") << endl;  // pesan pilihan salah
        }
    } while (pilihan != 6);             // ulangi selama belum memilih 6 (keluar)

    return 0;                    
}
