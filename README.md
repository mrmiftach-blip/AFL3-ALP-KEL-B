# Job Portal Magang

Repository proyek Job Portal Magang. Dibangun dengan framework Laravel 11.  
Panduan di bawah ini menjelaskan langkah-langkah untuk menjalankan proyek di lingkungan pengembangan lokal.

---

## Cara Menjalankan Proyek

Ikuti langkah-langkah di bawah ini secara berurutan:

### 1. Mengambil Kode dari GitHub

Gunakan perintah berikut untuk melakukan clone repository:

```bash
git clone https://github.com/mrmiftach-blip/AFL3-ALP-KEL-B
cd magang-portal
```

(Jika repository sudah pernah di-clone sebelumnya, jalankan perintah `git pull` untuk memperbarui kode lokal).

### 2. Install Dependencies

Buka terminal di dalam direktori `magang-portal`, kemudian jalankan:

```bash
composer install
```

### 3. Konfigurasi Environment (.env)

1. Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```
2. Buka file `.env` menggunakan teks editor.
3. Sesuaikan konfigurasi database untuk menggunakan MySQL:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=magang_portal
    DB_USERNAME=root
    DB_PASSWORD=
    ```
4. Sesuaikan konfigurasi Mailtrap untuk pengetesan fitur kirim Email (Notifikasi):
    ```env
    MAIL_MAILER=smtp
    MAIL_SCHEME=null
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=tanya_diardo
    MAIL_PASSWORD=tanya_diardo
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="no-reply@magang-portal.test"
    MAIL_FROM_NAME="Portal Magang"
    ```

### 4. Generate Application Key

Jalankan perintah berikut untuk menghasilkan kunci enkripsi aplikasi:

```bash
php artisan key:generate
```

### 5. Setup Database MySQL

**Pengguna XAMPP / Laragon:**

1. Pastikan servis MySQL berjalan.
2. Buat database baru dengan nama: **`magang_portal`** melalui phpMyAdmin.

**Pengguna Laravel Herd:**

1. Pastikan servis MySQL berjalan.
2. Buat database baru dengan nama: **`magang_portal`** menggunakan database client (seperti DBeaver, dll).

### 6. Menjalankan Migrasi dan Seeder

Untuk membuat struktur tabel dan mengisi data awal (seeder), jalankan perintah berikut:

```bash
php artisan migrate:fresh --seed
```

### 7. Menjalankan Server Lokal

**Pengguna XAMPP / Laragon:**
Jalankan development server Laravel dengan perintah:

```bash
php artisan serve
```

Aplikasi dapat diakses melalui browser di alamat: **http://127.0.0.1:8000**

**Pengguna Laravel Herd:**
Tidak perlu menjalankan perintah `serve`. Aplikasi sudah otomatis berjalan dan dapat diakses melalui alamat: **http://magang-portal.test** (atau sesuai nama folder repository).

### 8. Menjalankan Queue Worker (Untuk Notifikasi)

Sistem menggunakan *Queue* secara asinkron untuk mengirim email dan In-App Notification. Agar email bisa dikirim, buka terminal baru (jangan tutup server yang sedang jalan) lalu biarkan perintah ini terus menyala:

```bash
php artisan queue:work
```

---

## Data Akun Uji Coba (Seeder)

Proses seeder telah menyisipkan 3 akun default ke dalam database untuk keperluan pengujian.

| Role           | Email               | Password    |
| :------------- | :------------------ | :---------- |
| **Admin**      | `admin@mail.com`    | `qwerty123` |
| **Perusahaan** | `company1@mail.com` | `qwerty123` |
| **Mahasiswa**  | `student1@mail.com` | `qwerty123` |

> [!TIP]
> **Rute Bypass untuk Development:**
> Jika UI Login belum selesai, Anda bisa menggunakan rute sakti ini untuk langsung mencoba *role* tertentu:
> - Akses `/masuk-student` untuk login otomatis sebagai Mahasiswa.
> - Akses `/masuk-perusahaan` untuk login otomatis sebagai Perusahaan.
> - Akses `/keluar` untuk membuang sesi login (menjadi *Guest* kembali).

---

## Pembagian Direktori Kerja

-   **Views (UI):** Terdapat di direktori `resources/views/`. Gunakan Bootstrap 5 untuk pengembangan UI.
-   **Controllers (Logic):** Terdapat di direktori `app/Http/Controllers/`.
-   **Routes (URL):** Terdapat di file `routes/web.php`. Cek file ini untuk melihat daftar URL yang telah disesuaikan.
