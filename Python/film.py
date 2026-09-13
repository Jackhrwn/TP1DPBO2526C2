# Kode warna ANSI untuk tampilan berwarna di terminal
BIRU   = "\033[34m"     # warna biru (untuk garis border)
YELLOW = "\033[33m"   # warna kuning (untuk isi data film)
RESET  = "\033[0m"     # reset warna ke normal


# === Class Film ===
class Film:
    # Method khusus __init__ (constructor)
    def __init__(self, id:int, judul:str, genre:str, harga:int, durasi:int):
        self.id = id            # atribut id unik film (angka mulai dari 1, contoh: 1, 2, 3)
        self.judul = judul      # atribut judul film
        self.genre = genre      # atribut genre film (list, contoh: ["Action", "Drama"])
        self.harga = harga      # atribut harga tiket film dalam rupiah
        self.durasi = durasi    # atribut durasi film dalam menit

    # Method tampil_durasi: mengubah durasi (menit) menjadi format jam jika >= 1 jam
    def tampil_durasi(self)->int:
        jam = self.durasi // 60             # hitung jumlah jam
        sisa = self.durasi % 60             # hitung sisa menit
        if jam > 0:                         # jika durasi 60 menit atau lebih
            hasil = f"{jam} jam"            # tampilkan jumlah jam
            if sisa > 0:                    # jika ada sisa menit
                hasil += f" {sisa} menit"   # tampilkan sisa menit
            return hasil                    # kembalikan format jam
        return f"{self.durasi} menit"       # kembali ke format menit

    # Method tampilkan: mencetak seluruh atribut film di dalam kotak yang rapi
    def tampilkan(self)->None:
        # lebar isi kotak
        panjang = 40
        # border atas kotak (biru)
        print(BIRU + "+" + "-" * panjang + "+" + RESET)
        # isi kotak: setiap atribut film (kuning)
        print(YELLOW, end="")
        self._cetak_baris(f"ID      : {self.id}", panjang)
        self._cetak_baris(f"Judul   : {self.judul}", panjang)
        self._cetak_baris(f"Genre   : {', '.join(self.genre)}", panjang)
        self._cetak_baris(f"Harga   : Rp {self.harga}", panjang)
        self._cetak_baris(f"Durasi  : {self.tampil_durasi()}", panjang)
        print(RESET, end="")
        # border bawah kotak (biru)
        print(BIRU + "+" + "-" * panjang + "+" + RESET)
        # baris kosong
        print()

    # Method _cetak_baris: mencetak satu baris isi dengan border kiri-kanan
    def _cetak_baris(self, isi:int, panjang:int)->None:
        print(BIRU + "|" + RESET, end="")           # border kiri (biru)
        print(YELLOW + isi + RESET, end="")         # isi (kuning)
        print(" " * (panjang - len(isi)), end="")   # spasi penggenap
        print(BIRU + "|" + RESET)                   # border kanan (biru)