using namespace std;    // agar tidak perlu menulis std:: berulang kali

// Kode warna ANSI untuk tampilan berwarna di terminal
#define WARNA_BIRU   "\033[34m"   // warna biru 
#define WARNA_HIJAU  "\033[32m"   // warna hijau 
#define WARNA_MERAH  "\033[31m"   // warna merah 
#define WARNA_KUNING "\033[33m"   // warna kuning 
#define WARNA_RESET  "\033[0m"    // reset warna ke normal

// Deklarasi awal (forward declaration) agar bisa dipakai oleh method Film::tampilkan
void cetakGaris(string kiri, string tengah, string kanan, int panjang);
void cetakBaris(string isi, int panjang);

// ====== CLASS FILM ======
class Film {
    private:                                 
        int id;                              // atribut id unik film (angka mulai dari 1)
        string judul;                        // atribut judul film
        vector<string> genre;                // atribut genre film (bisa lebih dari satu)
        int harga;                           // atribut harga tiket film dalam rupiah
        int durasi;                          // atribut durasi film dalam menit

    public:
        // Constructor: dijalankan saat objek Film dibuat
        Film(int id, string judul, vector<string> genre, int harga, int durasi) {
            this->id = id;                   // isi atribut id dengan nilai parameter
            this->judul = judul;             // isi atribut judul dengan nilai parameter
            this->genre = genre;             // isi atribut genre (list) dengan nilai parameter
            this->harga = harga;             // isi atribut harga dengan nilai parameter
            this->durasi = durasi;           // isi atribut durasi dengan nilai parameter
        }

        // Getter id: mengambil nilai id
        int getId() { return id; }
        // Getter judul: mengambil nilai judul
        string getJudul() { return judul; }
        // Getter genre: mengambil nilai genre
        vector<string> getGenre() { return genre; }
        // Method getGenreText: menggabungkan list genre menjadi satu string
        string getGenreText() {
            string hasil = "";                           // string hasil gabungan
            for (size_t i = 0; i < genre.size(); i++) {  // ulangi semua genre
                if (i > 0) hasil += ", ";                // pemisah koma antar genre
                hasil += genre[i];                       // tambahkan genre tersebut
            }
            return hasil;                                // kembalikan string gabungan
        }

        // Getter harga: mengambil nilai harga
        int getHarga() { return harga; }
        // Getter durasi: mengambil nilai durasi
        int getDurasi() { return durasi; }

        // Setter judul: mengubah nilai judul
        void setJudul(string judul) { this->judul = judul; }
        // Setter genre: mengubah nilai genre
        void setGenre(vector<string> genre) { this->genre = genre; }
        // Setter harga: mengubah nilai harga
        void setHarga(int harga) { this->harga = harga; }
        // Setter durasi: mengubah nilai durasi
        void setDurasi(int durasi) { this->durasi = durasi; }

        // Method tampilDurasi : mengubah durasi (menit) menjadi format jam jika >= 1 jam
        string tampilDurasi() {
            int jam = durasi / 60;                              // hitung jumlah jam
            int sisa = durasi % 60;                             // hitung sisa menit
            if (jam > 0) {                                      // jika durasi 60 menit atau lebih
                string hasil = to_string(jam) + " jam";         // tampilkan jumlah jam
                if (sisa > 0) {                                 // jika ada sisa menit
                    hasil += " " + to_string(sisa) + " menit";  // tampilkan sisa menit
                }
                return hasil;                                   // kembalikan format jam
            }
            return to_string(durasi) + " menit";                // kembali ke menit biasa
        }

        // Method tampilkan: mencetak seluruh atribut film dalam kotak yang rapi
        void tampilkan() {
            cout << WARNA_BIRU;                                    // border atas (biru)
            cetakGaris("+", "-", "+", 40);
            cout << WARNA_KUNING;                                  // isi kotak (kuning)
            cetakBaris("ID      : " + to_string(id), 40);          // tampilkan id
            cetakBaris("Judul   : " + judul, 40);                  // tampilkan judul
            cetakBaris("Genre   : " + getGenreText(), 40);         // tampilkan genre (list)
            cetakBaris("Harga   : Rp " + to_string(harga), 40);    // tampilkan harga
            cetakBaris("Durasi  : " + tampilDurasi(), 40);         // tampilkan durasi (format jam)
            cout << WARNA_BIRU;                                    // border bawah (biru)
            cetakGaris("+", "-", "+", 40);
            cout << WARNA_RESET;                                   // reset warna
            cout << endl;                                          // baris kosong
        }
};
