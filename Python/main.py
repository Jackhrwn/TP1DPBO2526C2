from film import Film  # import class Film dari file film.py

daftar_film = []  # list kosong untuk menyimpan objek-objek Film


# Kode warna 
BIRU  = "\033[34m"    # warna biru (untuk tampilan menu)
HIJAU = "\033[32m"    # warna hijau (untuk pesan berhasil)
MERAH = "\033[31m"    # warna merah (untuk pesan gagal)
YELLOW= "\033[33m"    # warna kuning (Untuk Judul)
RESET = "\033[0m"     # reset warna ke normal


# Fungsi teks_hijau: membungkus teks dengan warna hijau (pesan berhasil)
def teks_hijau(teks):
    return HIJAU + teks + RESET


# Fungsi teks_merah: membungkus teks dengan warna merah (pesan gagal)
def teks_merah(teks):
    return MERAH + teks + RESET


# Fungsi cetak_garis: mencetak satu garis border, misal +===+
def cetak_garis(kiri, tengah, kanan, panjang):
    print(kiri + tengah * panjang + kanan)


# Fungsi cetak_judul: mencetak judul di tengah kotak dengan border
# (border "|" biru, isi judul kuning)
def cetak_judul(judul, panjang):
    kiri = (panjang - len(judul)) // 2      # hitung jarak spasi kiri
    print(BIRU + "|" + RESET, end="")       # border kiri (biru)
    print(YELLOW + " " * kiri + judul + RESET, end="")  # judul (kuning) di tengah
    print(" " * (panjang - kiri - len(judul)), end="")  # spasi penggenap
    print(BIRU + "|" + RESET)               # border kanan (biru)


# Fungsi cetak_baris: mencetak satu baris isi dengan border kiri-kanan
# (border "|" biru, isi kuning)
def cetak_baris(isi, panjang):
    print(BIRU + "|" + RESET, end="")       # border kiri (biru)
    print(YELLOW + isi + RESET, end="")     # isi (kuning)
    print(" " * (panjang - len(isi)), end="")  # spasi penggenap
    print(BIRU + "|" + RESET)               # border kanan (biru)


# Fungsi input_angka: membaca angka dari keyboard dengan ERROR HANDLING
# (program tidak akan crash jika user menginput huruf/string)
def input_angka(pesan):
    while True:                       # ulangi terus sampai input valid
        try:                          # coba jalankan blok ini
            nilai = int(input(pesan)) # baca input lalu ubah jadi bilangan bulat
            return nilai              # kembalikan angka yang valid
        except ValueError:            # jika input tidak bisa diubah ke angka
            print(teks_merah("Input harus berupa angka! Silakan coba lagi.\n"))  # pesan error


# Fungsi cek_id_valid: memeriksa apakah id berupa angka mulai dari 1 (1, 2, 3, ...)
def cek_id_valid(id):
    # valid jika id lebih besar atau sama dengan 1
    return id >= 1


# Fungsi cek_id_ada: memeriksa apakah sebuah id sudah ada di dalam daftar
def cek_id_ada(daftar, id):
    ketemu = False               # penanda hasil pencarian
    for film in daftar:          # ulangi semua objek dalam daftar
        if film.get_id() == id:  # jika id objek sama dengan id yang dicari
            ketemu = True        # tandai ketemu 
    return ketemu                # kembalikan hasil


# Fungsi tambah_data: menambahkan objek Film baru ke dalam daftar
def tambah_data(daftar):
    # minta input id (angka) dan ulangi sampai id valid
    while True:
        id = input_angka("Masukkan ID      : ")
        # jika id valid (mulai dari 1) lanjut ke langkah berikutnya
        if cek_id_valid(id):
            break
        # jika id bukan angka mulai dari 1 langsung minta input id yang lain
        print(teks_merah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"))
    # jika id sudah dipakai
    if cek_id_ada(daftar, id):
        # beri tahu user id sudah digunakan lalu kembali ke menu utama
        print(teks_merah("ID sudah digunakan!"))
        return
    # minta input judul
    judul = input("Masukkan Judul   : ")
    # minta user menentukan berapa genre yang ingin dimasukkan
    while True:
        jumlah_genre = input_angka("Jumlah Genre     : ")
        if jumlah_genre >= 1:
            break
        print(teks_merah("Jumlah genre harus angka mulai dari 1!\n"))
    # minta user memasukkan genre satu per satu (langsung dicek huruf besar)
    genre = []
    for i in range(jumlah_genre):
        while True:
            g = input(f"Genre ke-{i + 1}       : ").strip()
            # validasi: tidak boleh kosong dan harus diawali huruf besar
            if g and g[0].isupper():
                genre.append(g)
                break
            # pesan error lalu ulangi minta input genre yang sama
            print(teks_merah("Genre harus diawali dengan huruf besar (contoh: Action)!\n"))
    # minta input harga dengan error handling
    harga = input_angka("Harga Tiket      : ")
    # minta input durasi dengan error handling
    durasi = input_angka("Durasi (menit)   : ")

    # buat objek Film baru lalu simpan ke dalam daftar
    daftar.append(Film(id, judul, genre, harga, durasi))
    # konfirmasi data berhasil ditambahkan
    print(teks_hijau("Data berhasil ditambahkan!"))


# Fungsi tampilkan_data: menampilkan semua objek Film dalam daftar
def tampilkan_data(daftar):
    # jika daftar masih kosong
    if not daftar:
        # tampilkan pesan belum ada data
        print("Belum ada data film.")
        # hentikan proses tampil
        return
    # cetak judul daftar film (garis border biru, judul kuning)
    print()
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 42)
    print(YELLOW, end="")
    cetak_judul("DAFTAR FILM", 42)
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 42)
    print(RESET, end="")
    print()
    # urutkan daftar berdasarkan id (ascending) sebelum ditampilkan
    daftar.sort(key=lambda f: f.get_id())
    # ulangi semua objek dalam daftar
    for i, film in enumerate(daftar):
        # tampilkan nomor urut film
        print(YELLOW + f"Film ke-{i + 1}:" + RESET)
        # panggil method tampilkan milik objek film
        film.tampilkan()


# Fungsi tampilkan_menu_update: menampilkan submenu pilihan kolom yang diupdate
def tampilkan_menu_update():
    # garis border biru, judul & daftar update kuning
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 38)
    print(YELLOW, end="")
    cetak_judul("PILIH KOLOM UNTUK UPDATE", 38)
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 38)
    print(YELLOW, end="")
    cetak_baris("  [1] Ubah Judul", 38)
    cetak_baris("  [2] Ubah Genre", 38)
    cetak_baris("  [3] Ubah Harga", 38)
    cetak_baris("  [4] Ubah Durasi", 38)
    cetak_baris("  [5] Selesai Update", 38)
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 38)
    print(RESET, end="")


# Fungsi update_data: mengubah data objek Film berdasarkan id dengan pilihan kolom
def update_data(daftar):
    # minta input id sasaran (angka) dan ulangi sampai id valid
    while True:
        id = input_angka("Masukkan ID yang akan diupdate : ")
        # jika id valid (mulai dari 1) lanjut ke langkah berikutnya
        if cek_id_valid(id):
            break
        # jika id bukan angka mulai dari 1 langsung minta input id yang lain
        print(teks_merah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"))

    # cari objek sasaran
    film_target = None   # objek sasaran (default belum ketemu)
    for film in daftar:
        # jika id objek cocok dengan id sasaran
        if film.get_id() == id:
            # simpan objek sasaran
            film_target = film

    # jika tidak ada yang cocok
    if film_target is None:
        # beri tahu user id tidak ditemukan lalu kembali ke menu utama
        print(teks_merah("ID tidak ditemukan!"))
        return

    # ulangi sampai user memilih selesai
    while True:
        # tampilkan data film saat ini
        print("\nData film saat ini:")
        print()
        film_target.tampilkan()

        # tampilkan submenu pilihan kolom
        tampilkan_menu_update()
        # baca pilihan kolom dengan error handling
        pilihan = input_angka("Pilih kolom (1-5) : ")

        # jalankan sesuai pilihan kolom
        if pilihan == 1:
            # ubah judul
            judul_baru = input("Judul baru        : ")
            film_target.set_judul(judul_baru)
            # konfirmasi judul berhasil diubah
            print(teks_hijau("Judul berhasil diubah!"))
        elif pilihan == 2:
            # minta user menentukan berapa genre baru yang ingin dimasukkan
            while True:
                jumlah_genre = input_angka("Jumlah Genre      : ")
                if jumlah_genre >= 1:
                    break
                print(teks_merah("Jumlah genre harus angka mulai dari 1!\n"))
            # minta user memasukkan genre baru satu per satu (langsung dicek huruf besar)
            genre_baru = []
            for i in range(jumlah_genre):
                while True:
                    g = input(f"Genre ke-{i + 1}        : ").strip()
                    # validasi: tidak boleh kosong dan harus diawali huruf besar
                    if g and g[0].isupper():
                        genre_baru.append(g)
                        break
                    # pesan error lalu ulangi minta input genre yang sama
                    print(teks_merah("Genre harus diawali dengan huruf besar (contoh: Action)!\n"))
            film_target.set_genre(genre_baru)
            # konfirmasi genre berhasil diubah
            print(teks_hijau("Genre berhasil diubah!"))
        elif pilihan == 3:
            # ubah harga
            harga_baru = input_angka("Harga baru        : ")
            film_target.set_harga(harga_baru)
            # konfirmasi harga berhasil diubah
            print(teks_hijau("Harga berhasil diubah!"))
        elif pilihan == 4:
            # ubah durasi
            durasi_baru = input_angka("Durasi baru(menit): ")
            film_target.set_durasi(durasi_baru)
            # konfirmasi durasi berhasil diubah
            print(teks_hijau("Durasi berhasil diubah!"))
        elif pilihan == 5:
            # selesai update
            print(teks_hijau("Update selesai."))
            # hentikan perulangan submenu
            return
        else:
            # pilihan tidak valid
            print(teks_merah("Pilihan tidak valid!"))


# Fungsi hapus_data: menghapus objek Film dari daftar berdasarkan id
def hapus_data(daftar):
    # minta input id sasaran (angka) dan ulangi sampai id valid
    while True:
        id = input_angka("Masukkan ID yang akan dihapus : ")
        # jika id valid (mulai dari 1) lanjut ke langkah berikutnya
        if cek_id_valid(id):
            break
        # jika id bukan angka mulai dari 1 langsung minta input id yang lain
        print(teks_merah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"))

    # cari index yang akan dihapus 
    index_hapus = -1   # index sasaran (default belum ketemu)
    for i, film in enumerate(daftar):
        # jika id objek cocok dengan id sasaran
        if film.get_id() == id:
            # simpan index sasaran
            index_hapus = i

    # jika tidak ada yang cocok
    if index_hapus == -1:
        # beri tahu user id tidak ditemukan lalu kembali ke menu utama
        print(teks_merah("ID tidak ditemukan!"))
        return

    # hapus objek pada index ke-index_hapus
    daftar.pop(index_hapus)
    # konfirmasi data berhasil dihapus
    print(teks_hijau("Data berhasil dihapus!"))


# Fungsi cari_data: mencari satu objek Film berdasarkan id
def cari_data(daftar):
    # minta input id yang dicari (angka) dan ulangi sampai id valid
    while True:
        id = input_angka("Masukkan ID yang dicari : ")
        # jika id valid (mulai dari 1) lanjut ke langkah berikutnya
        if cek_id_valid(id):
            break
        # jika id bukan angka mulai dari 1 langsung minta input id yang lain
        print(teks_merah("ID harus berupa angka mulai dari 1 (1, 2, 3, ...)!\n"))

    # cari objek yang cocok 
    film_cari = None   # objek hasil (default belum ketemu)
    for film in daftar:
        # jika id objek cocok dengan id yang dicari
        if film.get_id() == id:
            # simpan objek hasil
            film_cari = film

    # jika ditemukan
    if film_cari is not None:
        # tampilkan pesan film ditemukan
        print(teks_hijau("Film ditemukan:"))
        # tampilkan detail film
        film_cari.tampilkan()
    # jika tidak ditemukan
    else:
        # pesan jika film tidak ditemukan lalu kembali ke menu utama
        print(teks_merah(f"Film dengan ID '{id}' tidak ditemukan!"))


# Fungsi tampilkan_menu: menampilkan daftar menu utama dengan tampilan menarik
def tampilkan_menu():
    # cetak baris kosong
    print()
    # mulai warna biru untuk seluruh kotak menu
    print(BIRU, end="")
    # kotak menu utama
    cetak_garis("+", "=", "+", 36)
    print(YELLOW, end="")
    cetak_judul("MENU BIOSKOP", 36)
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 36)
    print(YELLOW, end="")
    cetak_baris("  [1] Tambah Data Film", 36)
    cetak_baris("  [2] Tampilkan Data Film", 36)
    cetak_baris("  [3] Update Data Film", 36)
    cetak_baris("  [4] Hapus Data Film", 36)
    cetak_baris("  [5] Cari Data Film", 36)
    cetak_baris("  [6] Keluar", 36)
    print(BIRU, end="")
    cetak_garis("+", "=", "+", 36)
    # reset warna
    print(RESET, end="")


# Fungsi main
def main():
    # ulangi terus sampai user memilih keluar
    while True:
        # tampilkan daftar menu
        tampilkan_menu()
        # baca pilihan menu dengan error handling
        pilihan = input_angka("Pilih menu (1-6) : ")

        # jika memilih 1
        if pilihan == 1:
            # panggil fungsi tambah_data
            tambah_data(daftar_film)
        # jika memilih 2
        elif pilihan == 2:
            # panggil fungsi tampilkan_data
            tampilkan_data(daftar_film)
        # jika memilih 3
        elif pilihan == 3:
            # panggil fungsi update_data
            update_data(daftar_film)
        # jika memilih 4
        elif pilihan == 4:
            # panggil fungsi hapus_data
            hapus_data(daftar_film)
        # jika memilih 5
        elif pilihan == 5:
            # panggil fungsi cari_data
            cari_data(daftar_film)
        # jika memilih 6
        elif pilihan == 6:
            # pesan program selesai
            print(teks_hijau("Program selesai. Terima kasih!"))
            # hentikan perulangan (keluar dari program)
            break
        # jika pilihan bukan 1-6
        else:
            # pesan pilihan tidak valid
            print(teks_merah("Pilihan tidak valid!"))


# jalankan fungsi main hanya jika file ini dijalankan langsung
if __name__ == "__main__":
    # panggil fungsi main sebagai titik awal program
    main()