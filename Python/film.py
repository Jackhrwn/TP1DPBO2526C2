BIRU   = "\033[34m"    # warna biru (untuk garis border)
YELLOW = "\033[33m"    # warna kuning (untuk isi data film)
RESET  = "\033[0m"     # reset warna ke normal


# === Class Film ===
class Film:
    # Method khusus __init__ (constructor)
    def __init__(self, id:int, judul:str, genre:str, harga:int, durasi:int):
        # atribut dibuat "private" (diawali _) untuk enkapsulasi
        self._id = id            # atribut id unik film (angka mulai dari 1, contoh: 1, 2, 3)
        self._judul = judul      # atribut judul film
        self._genre = genre      # atribut genre film (list, contoh: ["Action", "Drama"])
        self._harga = harga      # atribut harga tiket film dalam rupiah
        self._durasi = durasi    # atribut durasi film dalam menit

    # Getter id: mengambil (membaca) nilai atribut id
    def get_id(self)->int:
        return self._id
    # Getter judul: mengambil nilai atribut judul
    def get_judul(self)->str:
        return self._judul
    # Getter genre: mengambil nilai atribut genre
    def get_genre(self)->list:
        return self._genre
    # Getter genre_text: menggabungkan list genre menjadi satu string
    def get_genre_text(self)->str:
        return ', '.join(self._genre)   # gabungkan genre dengan tanda koma
    # Getter harga: mengambil nilai atribut harga
    def get_harga(self)->int:
        return self._harga
    # Getter durasi: mengambil nilai atribut durasi
    def get_durasi(self)->int:
        return self._durasi

    # Setter judul: mengubah nilai atribut judul
    def set_judul(self, judul:str)->None:
        self._judul = judul
    # Setter genre: mengubah nilai atribut genre
    def set_genre(self, genre:list)->None:
        self._genre = genre
    # Setter harga: mengubah nilai atribut harga
    def set_harga(self, harga:int)->None:
        self._harga = harga
    # Setter durasi: mengubah nilai atribut durasi
    def set_durasi(self, durasi:int)->None:
        self._durasi = durasi

    # Method tampil_durasi: mengubah durasi (menit) menjadi format jam jika >= 1 jam
    def tampil_durasi(self)->str:
        jam = self._durasi // 60        # hitung jumlah jam
        sisa = self._durasi % 60        # hitung sisa menit
        if jam > 0:                     # jika durasi 60 menit atau lebih
            hasil = f"{jam} jam"        # tampilkan jumlah jam
            if sisa > 0:                # jika ada sisa menit
                hasil += f" {sisa} menit"   # tampilkan sisa menit
            return hasil                # kembalikan format jam
        return f"{self._durasi} menit"  # kembali ke format menit

    # Method tampilkan: mencetak seluruh atribut film di dalam kotak yang rapi
    def tampilkan(self)->None:
        # lebar isi kotak
        panjang = 40
        # border atas kotak (biru)
        print(BIRU + "+" + "-" * panjang + "+" + RESET)
        # isi kotak: setiap atribut film (kuning)
        print(YELLOW, end="")
        self._cetak_baris(f"ID      : {self.get_id()}", panjang)
        self._cetak_baris(f"Judul   : {self.get_judul()}", panjang)
        self._cetak_baris(f"Genre   : {self.get_genre_text()}", panjang)
        self._cetak_baris(f"Harga   : Rp {self.get_harga()}", panjang)
        self._cetak_baris(f"Durasi  : {self.tampil_durasi()}", panjang)
        print(RESET, end="")
        # border bawah kotak (biru)
        print(BIRU + "+" + "-" * panjang + "+" + RESET)
        # baris kosong
        print()

    # Method _cetak_baris: mencetak satu baris isi dengan border kiri-kanan
    def _cetak_baris(self, isi:str, panjang:int)->None:
        print(BIRU + "|" + RESET, end="")           # border kiri (biru)
        print(YELLOW + isi + RESET, end="")         # isi (kuning)
        print(" " * (panjang - len(isi)), end="")   # spasi penggenap
        print(BIRU + "|" + RESET)                   # border kanan (biru)