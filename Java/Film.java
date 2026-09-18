import java.util.ArrayList; // import library untuk ArrayList (array dinamis berupa list of object)

public class Film {
    private static final String WARNA_BIRU   = "\033[34m";    // warna biru 
    private static final String WARNA_KUNING = "\033[33m";    // warna kuning 
    private static final String WARNA_RESET  = "\033[0m";     // reset warna ke normal

    private int id;                 // atribut id unik film (angka mulai dari 1, contoh: 1, 2, 3)
    private String judul;           // atribut judul film
    private ArrayList<String> genre;// atribut genre film (bisa lebih dari satu)
    private int harga;              // atribut harga tiket film dalam rupiah
    private int durasi;             // atribut durasi film dalam menit

    // Constructor
    public Film(int id, String judul, ArrayList<String> genre, int harga, int durasi) {
        this.id = id;               // isi atribut id dengan nilai parameter
        this.judul = judul;         // isi atribut judul dengan nilai parameter
        this.genre = genre;         // isi atribut genre (list) dengan nilai parameter
        this.harga = harga;         // isi atribut harga dengan nilai parameter
        this.durasi = durasi;       // isi atribut durasi dengan nilai parameter
    }

    // Getter id: mengambil (membaca) nilai atribut id
    public int getId() { return id; }
    // Getter judul: mengambil nilai atribut judul
    public String getJudul() { return judul; }
    // Getter genre: mengambil nilai atribut genre
    public ArrayList<String> getGenre() { return genre; }
    // Getter genreText: menggabungkan list genre menjadi satu string
    public String getGenreText() {
        return String.join(", ", genre);   // gabungkan genre dengan tanda koma
    }
    // Getter harga: mengambil nilai atribut harga
    public int getHarga() { return harga; }
    // Getter durasi: mengambil nilai atribut durasi
    public int getDurasi() { return durasi; }

    // Setter hudul: mengubah nilai atribut judul
    public void setJudul(String judul) { this.judul = judul; }
    // Setter genre: mengubah nilai atribut genre
    public void setGenre(ArrayList<String> genre) { this.genre = genre; }
    // Setter harga: mengubah nilai atribut harga
    public void setHarga(int harga) { this.harga = harga; }
    // Setter durasi: mengubah nilai atribut durasi
    public void setDurasi(int durasi) { this.durasi = durasi; }

    // Method tampilDurasi: mengubah durasi (menit) menjadi format jam jika >= 1 jam
    public String tampilDurasi() {
        int jam = durasi / 60;                    // hitung jumlah jam
        int sisa = durasi % 60;                   // hitung sisa menit
        if (jam > 0) {                            // jika durasi 60 menit atau lebih
            String hasil = jam + " jam";          // tampilkan jumlah jam
            if (sisa > 0) {                       // jika ada sisa menit
                hasil += " " + sisa + " menit";   // tampilkan sisa menit
            }
            return hasil;                         // kembalikan format jam
        }
        return durasi + " menit";                 // kembali ke format menit
    }

    // Method cetakGaris: mencetak satu garis border, misal ===
    private void cetakGaris(String kiri, String tengah, String kanan, int panjang) {
        System.out.print(kiri);
        for (int i = 0; i < panjang; i++) System.out.print(tengah);
        System.out.println(kanan);
    }

    // Method cetakBaris: mencetak satu baris isi dengan border kiri-kanan
    private void cetakBaris(String isi, int panjang) {
        System.out.print(WARNA_BIRU + "|" + WARNA_RESET);      // border kiri (biru)
        System.out.print(WARNA_KUNING + isi + WARNA_RESET);    // isi (kuning)
        for (int i = isi.length(); i < panjang; i++) System.out.print(" ");  // spasi penggenap
        System.out.println(WARNA_BIRU + "|" + WARNA_RESET);    // border kanan (biru)
    }

    // Method tampilkan: mencetak seluruh atribut film di dalam kotak yang rapi
    public void tampilkan() {
        int panjang = 40;                                  // lebar isi kotak
        System.out.print(WARNA_BIRU);                      // border atas (biru)
        cetakGaris("+", "-", "+", panjang);
        System.out.print(WARNA_KUNING);                    // isi kotak (kuning)
        cetakBaris("ID      : " + id, panjang);            // tampilkan id
        cetakBaris("Judul   : " + judul, panjang);         // tampilkan judul
        cetakBaris("Genre   : " + getGenreText(), panjang);// tampilkan genre (list)
        cetakBaris("Harga   : Rp " + harga, panjang);      // tampilkan harga
        cetakBaris("Durasi  : " + tampilDurasi(), panjang);// tampilkan durasi (format jam)
        System.out.print(WARNA_BIRU);                      // border bawah (biru)
        cetakGaris("+", "-", "+", panjang);
        System.out.print(WARNA_RESET);                     // reset warna
        System.out.println();                              // baris kosong
    }
}
