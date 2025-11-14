# 🌟 Kultilas — Website Ekstrakurikuler Sekolah

Kultilas adalah website berbasis **PHP Native** yang dirancang untuk memudahkan pengelolaan kegiatan ekstrakurikuler di sekolah.  
Melalui platform ini, siswa dapat melihat informasi ekskul, bergabung, serta berinteraksi dengan pembina dan anggota lainnya.

---

## 🏫 Fitur Utama

- 📋 **Manajemen Ekskul** — Tambah, ubah, dan hapus data ekstrakurikuler.
- 👨‍🎓 **Manajemen Anggota** — Daftar siswa dan keanggotaan ekskul.
- 🧑‍🏫 **Manajemen Pembina** — Data guru pembina yang membimbing ekskul.
- 📰 **Berita & Kegiatan** — Informasi terbaru seputar kegiatan ekskul.
- 🔒 **Autentikasi Pengguna** — Login untuk admin, pembina, dan anggota.
- 🎨 **Tampilan Responsif** — Desain sederhana dan rapi untuk desktop maupun mobile.

---

## 🧩 Teknologi yang Digunakan

| Komponen | Teknologi |
|-----------|------------|
| Bahasa Pemrograman | PHP 8+ (Native, tanpa framework) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| Server Lokal | Laragon / XAMPP / WAMP |
| Version Control | Git & GitHub |

---

## ⚙️ Instalasi dan Menjalankan Proyek

Ikuti langkah-langkah berikut untuk menjalankan proyek **Kultilas** di komputer lokalmu:

### 1️⃣ Clone Repository
```bash
git clone https://github.com/Raditt10/Kult1las-native.git

2️⃣ Pindah ke Folder Proyek

cd Kult1las-native

3️⃣ Pindahkan ke Direktori Server Lokal

Jika kamu menggunakan Laragon, XAMPP, atau WAMP, pindahkan folder proyek ke:

C:\laragon\www\

atau

C:\xampp\htdocs\

4️⃣ Buat Database di phpMyAdmin

    Buka http://localhost/phpmyadmin

    Buat database baru dengan nama:

    kultilas

    Import file database:

        Cari file bernama kultilas.sql di folder proyek (jika tersedia).

        Klik Import di phpMyAdmin dan unggah file tersebut.

5️⃣ Konfigurasi Koneksi Database

Buka file:

config/database.php

Lalu sesuaikan pengaturannya:

$host = 'localhost';
$user = 'root';
$pass = ''; // Kosongkan jika default XAMPP/Laragon
$db   = 'kultilas';

6️⃣ Jalankan Proyek

Buka browser dan kunjungi:

http://localhost/Kult1las-native/

👥 Role Pengguna
Role	Deskripsi
Admin	Mengelola seluruh data sistem
Pembina	Mengelola kegiatan dan anggota ekskul
Siswa	Melihat dan bergabung ke ekskul
🗂️ Struktur Folder Singkat

Kult1las-native/
├── assets/           # File CSS, JS, dan gambar
├── config/           # File konfigurasi (database, constants, dsb)
├── pages/            # Halaman utama aplikasi
├── includes/         # Template header, footer, dan komponen lain
├── admin/            # Halaman khusus admin
├── index.php         # Halaman utama
└── kultilas.sql      # File database (jika disertakan)

🧑‍💻 Kontributor

    Rafaditya Syahputra - Front-end Developer
    GitHub Profile

📄 Lisensi

Proyek ini bersifat open-source dan dapat digunakan untuk keperluan pendidikan atau pengembangan internal sekolah.
💬 Catatan

Jika ada error seperti:

fatal: not a git repository (or any of the parent directories): .git

Pastikan kamu sudah berada di direktori proyek sebelum menjalankan perintah git.
