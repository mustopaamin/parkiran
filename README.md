# parkiran
Tugas Mata Kuliah Pemrograman Web Lanjut

---

Aplikasi ini dirancang untuk membantu petugas parkir dalam mencatat kendaraan yang masuk dan keluar dari area parkir, serta menghitung biaya parkir berdasarkan durasi waktu.

---

## Teknologi yang Digunakan
- **Bahasa Pemrograman**: PHP
- **Database**: MySQL
- **Template UI**: AdminLTE

---

## Alur Aplikasi
1. **Login**
   - Username: `admin`
   - Password: `admin`

2. **Input Data Kendaraan Masuk**
   - Petugas memasukkan data kendaraan yang masuk.
   - Sistem akan melakukan pengecekan nomor kendaraan:
     - Jika nomor kendaraan belum ada di database, maka sistem akan menyimpannya sebagai data baru.
     - Jika nomor kendaraan sudah ada, sistem hanya mencatat waktu masuk.

3. **Filter Nomor Kendaraan Saat Keluar**
   - Ketika kendaraan keluar, petugas dapat memfilter nomor kendaraan untuk validasi data.
   - Setelah data kendaraan ditemukan, tekan tombol **Keluar**.

4. **Perhitungan Durasi dan Biaya Parkir**
   - Sistem akan menghitung durasi waktu parkir (dari waktu masuk hingga waktu keluar).
   - Biaya parkir dihitung berdasarkan jenis kendaraan:
     - **Motor**: Rp 3.000 / Jam
     - **Mobil**: Rp 5.000 / Jam

5. **Logout**
   - Setelah selesai menggunakan aplikasi, petugas dapat logout untuk mengamankan sesi.
