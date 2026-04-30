# SIWARGA - Sistem Informasi Warga Terpadu

Aplikasi web pendataan warga berbasis **PHP + MySQL** siap di-host di **XAMPP**.

## Fitur Lengkap
- Multi-role (Super Admin, Admin RT/RW, Ketua RT/RW, Warga)
- Master Data: Kartu Keluarga, Warga, Pendatang, Kelahiran, Kematian, Pindah
- Administrasi: Pengajuan Surat (Domisili, SKCK, Usaha, Tidak Mampu, Pindah, dll) + alur Verifikasi → Approval → Cetak PDF
- Keuangan: Iuran Warga & Buku Kas (Pemasukan/Pengeluaran)
- Keamanan: Buku Tamu Digital (check-in/check-out) & Laporan Keamanan
- Manajemen User & Audit Log
- Dashboard statistik (grafik usia, pekerjaan, trend iuran)
- Export CSV/Excel & PDF (semua tabel)

## Cara Instalasi di XAMPP

### 1. Copy Folder
Salin seluruh folder `siwarga_php/` ke dalam folder XAMPP, contoh:
```
C:\xampp\htdocs\siwarga_php\
```

### 2. Jalankan XAMPP
Aktifkan **Apache** dan **MySQL** dari XAMPP Control Panel.

### 3. Import Database
1. Buka `http://localhost/phpmyadmin`
2. Klik tab **Import**
3. Pilih file `database/siwarga.sql`
4. Klik **Go** → database `siwarga` akan dibuat lengkap dengan data dummy.

### 4. Konfigurasi (opsional)
Edit `config/database.php` jika username/password MySQL Anda berbeda dari default XAMPP:
```php
$DB_HOST = 'localhost';
$DB_NAME = 'siwarga';
$DB_USER = 'root';
$DB_PASS = '';
```

### 5. Akses Aplikasi
Buka di browser:
```
http://localhost/siwarga_php/
```

## Akun Default

| Role          | Username     | Password   |
|---------------|--------------|------------|
| Super Admin   | `superadmin` | `admin123` |
| Admin RT/RW   | `adminrtrw`  | `admin123` |
| Ketua RT/RW   | `ketua`      | `ketua123` |
| Warga         | `warga01`    | `warga123` |

> **Penting:** Ganti password setelah login pertama kali!

## Struktur Folder
```
siwarga_php/
├── assets/css/style.css
├── config/
│   ├── config.php          # Konfigurasi global + session
│   └── database.php        # Koneksi PDO MySQL
├── database/
│   └── siwarga.sql         # Schema + data dummy (import via phpMyAdmin)
├── includes/
│   ├── auth.php            # Cek login & role
│   ├── functions.php       # Helper (e(), rupiah(), tanggal_id, csrf)
│   ├── header.php          # Layout header + navbar
│   ├── sidebar.php         # Menu sidebar (role-aware)
│   └── footer.php          # Layout footer + script
├── modules/
│   ├── kk/                 # CRUD Kartu Keluarga
│   ├── warga/              # CRUD Warga
│   ├── pendatang/
│   ├── kelahiran/
│   ├── kematian/
│   ├── pindah/
│   ├── surat/              # Pengajuan + Approval + Cetak
│   ├── iuran/
│   ├── keuangan/
│   ├── tamu/               # Buku Tamu
│   ├── keamanan/           # Laporan Keamanan
│   ├── users/              # Manajemen User
│   ├── audit/              # Audit Log
│   └── laporan/            # Export CSV & PDF
├── index.php               # Redirect ke login
├── login.php               # Halaman login
├── logout.php
└── dashboard.php           # Dashboard statistik
```

## Hak Akses Tiap Role

| Modul                  | Super Admin | Admin RT/RW | Ketua RT/RW | Warga |
|------------------------|:-----------:|:-----------:|:-----------:|:-----:|
| Manajemen User         | ✓           |             |             |       |
| Master Data CRUD       | ✓           | ✓           | view        |       |
| Verifikasi Surat       | ✓           | ✓           |             |       |
| Approval Surat         | ✓           |             | ✓           |       |
| Iuran & Keuangan       | ✓           | ✓           | view        |       |
| Buku Tamu & Keamanan   | ✓           | ✓           | ✓           |       |
| Pengajuan Surat        | ✓           | ✓           | ✓           | ✓     |
| Audit Log              | ✓           |             |             |       |

## Alur Pengajuan Surat
```
Warga ajukan → status: pending
Admin RT/RW verifikasi → status: verifikasi
Ketua RT/RW approve → status: approved (siap cetak)
                  ↘ tolak → status: ditolak
```

## Stack Teknologi
- **Backend**: PHP 7.4+ vanilla, PDO MySQL
- **Frontend**: Bootstrap 5, FontAwesome 6, jQuery, DataTables, Chart.js (semua via CDN)
- **DB**: MySQL / MariaDB

## Keamanan
- Password di-hash dengan **bcrypt** (`password_hash`)
- **CSRF token** pada semua form POST
- Role-based access control di setiap modul
- **Audit log** untuk pelacakan aktivitas user
- HTML escape (`e()`) di seluruh output

## Pengembangan Lanjutan (saran)
- Upload foto KTP/KK & file identitas pendatang
- Notifikasi WhatsApp via API gateway
- QR Code untuk kartu warga digital
- Backup database otomatis (cron)
- API REST untuk integrasi mobile app

---
© SIWARGA v1.0
