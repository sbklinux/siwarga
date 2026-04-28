# SIWARGA - Sistem Informasi Warga Terpadu

## Original Problem Statement
User minta dibuatkan website SIWARGA berbasis PHP/MySQL siap dipakai di XAMPP, lengkap dengan controller dan database, untuk pendataan warga RT/RW.

## Stack
- PHP 8.x vanilla + PDO MySQL
- Bootstrap 5, FontAwesome 6, jQuery, DataTables, Chart.js (CDN)
- MariaDB / MySQL
- Session-based auth (bcrypt)

## User Choices
1. PHP/MySQL untuk XAMPP (bukan React/FastAPI)
2. Semua fitur sekaligus
3. JWT/custom auth → diimplementasi sebagai PHP session (standar PHP)
4. Design free → Bootstrap 5 dengan tema biru navy formal pemerintahan
5. Seed dummy data: Ya

## What's Implemented (28 Apr 2026)
- ✅ Login multi-role (Super Admin, Admin RT/RW, Ketua RT/RW, Warga)
- ✅ Dashboard statistik (4 grafik: usia, pekerjaan, trend iuran, tabel tamu)
- ✅ Master Data CRUD: KK, Warga (+ upload KTP/KK), Pendatang, Kelahiran, Kematian, Pindah
- ✅ Pengajuan Surat: ajukan → verifikasi admin → approval ketua → cetak (browser print PDF)
- ✅ Iuran Warga + Buku Kas (Pemasukan/Pengeluaran)
- ✅ Buku Tamu Digital (check-in/check-out) + Laporan Keamanan
- ✅ Manajemen User + Audit Log
- ✅ Export CSV (Excel-compatible) & PDF (browser print)
- ✅ Profil Saya: edit data + ganti password + (warga) edit data warga + upload foto
- ✅ CSRF token, role-based access control, password hashing bcrypt

## File Output
- `/app/siwarga_php/` (41 PHP files, total 356K)
- `/app/siwarga_php.zip` (63K, ready to download)
- `/app/frontend/public/download/siwarga_php.zip`

## Default Credentials
| Role | Username | Password |
|------|----------|----------|
| Super Admin | superadmin | admin123 |
| Admin RT/RW | adminrtrw | admin123 |
| Ketua RT/RW | ketua | ketua123 |
| Warga | warga01 | warga123 |

## Cara Install di XAMPP
1. Extract ZIP ke `C:\xampp\htdocs\siwarga_php\`
2. Aktifkan Apache & MySQL
3. Import `database/siwarga.sql` via phpMyAdmin
4. Akses `http://localhost/siwarga_php/`

## Backlog / Next Items
- P1: Notifikasi WhatsApp via API gateway
- P1: QR Code kartu warga digital
- P2: Backup database otomatis (cron)
- P2: Mobile responsive improvements
- P2: API REST untuk integrasi mobile app
- P2: 2FA untuk admin
