-- =====================================================
-- SIWARGA - Sistem Informasi Warga Terpadu
-- Database: siwarga
-- Versi: 1.0
-- Cara import: buka phpMyAdmin -> New -> Import file ini
-- =====================================================

CREATE DATABASE IF NOT EXISTS `siwarga` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `siwarga`;

SET FOREIGN_KEY_CHECKS=0;

-- ======================
-- USERS
-- ======================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `role` ENUM('super_admin','admin_rtrw','ketua_rtrw','warga') NOT NULL,
  `nik` VARCHAR(20) DEFAULT NULL,
  `rt` VARCHAR(5) DEFAULT NULL,
  `rw` VARCHAR(5) DEFAULT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `aktif` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password default semua: lihat README
-- super_admin / admin123
-- adminrtrw / admin123
-- ketua / ketua123
-- warga01 / warga123
INSERT INTO `users` (`username`,`password`,`nama`,`email`,`role`,`nik`,`rt`,`rw`) VALUES
('superadmin','$2y$10$LHhSLNlRavLlU56tPdhCfeOlEQtAu7ujA0IFX06peBRaaavWuwzDS','Super Administrator','superadmin@siwarga.id','super_admin',NULL,NULL,NULL),
('adminrtrw','$2y$10$LHhSLNlRavLlU56tPdhCfeOlEQtAu7ujA0IFX06peBRaaavWuwzDS','Admin RT 01 RW 02','admin@siwarga.id','admin_rtrw',NULL,'01','02'),
('ketua','$2y$10$u.4.q7Pt7EOeVY.KL/CLfegPCpbXnY15qwEnKj0tKK4QLYhazoFLC','Bapak Suryanto','ketua@siwarga.id','ketua_rtrw',NULL,'01','02'),
('warga01','$2y$10$WRCdqMZMIBYuCPIuTHRm/uXhKrWHg/IeVNFxpzItc7jXKnPZbYk9O','Budi Santoso','budi@siwarga.id','warga','3201010101010001','01','02');

-- ======================
-- KARTU KELUARGA
-- ======================
DROP TABLE IF EXISTS `kartu_keluarga`;
CREATE TABLE `kartu_keluarga` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `no_kk` VARCHAR(20) UNIQUE NOT NULL,
  `kepala_keluarga` VARCHAR(100) NOT NULL,
  `alamat` TEXT,
  `rt` VARCHAR(5),
  `rw` VARCHAR(5),
  `kelurahan` VARCHAR(100),
  `kecamatan` VARCHAR(100),
  `kota` VARCHAR(100),
  `provinsi` VARCHAR(100),
  `status_rumah` ENUM('milik','sewa','kontrak','menumpang') DEFAULT 'milik',
  `jumlah_anggota` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kartu_keluarga` (`no_kk`,`kepala_keluarga`,`alamat`,`rt`,`rw`,`kelurahan`,`kecamatan`,`kota`,`provinsi`,`status_rumah`,`jumlah_anggota`) VALUES
('3201010101010001','Budi Santoso','Jl. Mawar No. 12','01','02','Sukamaju','Bogor Selatan','Kota Bogor','Jawa Barat','milik',4),
('3201010101010002','Andi Pratama','Jl. Mawar No. 14','01','02','Sukamaju','Bogor Selatan','Kota Bogor','Jawa Barat','milik',3),
('3201010101010003','Siti Aminah','Jl. Melati No. 5','01','02','Sukamaju','Bogor Selatan','Kota Bogor','Jawa Barat','sewa',2),
('3201010101010004','Hendro Wijaya','Jl. Melati No. 8','01','02','Sukamaju','Bogor Selatan','Kota Bogor','Jawa Barat','kontrak',5),
('3201010101010005','Ratna Dewi','Jl. Anggrek No. 21','01','02','Sukamaju','Bogor Selatan','Kota Bogor','Jawa Barat','milik',3);

-- ======================
-- WARGA
-- ======================
DROP TABLE IF EXISTS `warga`;
CREATE TABLE `warga` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(20) UNIQUE NOT NULL,
  `no_kk` VARCHAR(20),
  `nama` VARCHAR(100) NOT NULL,
  `tempat_lahir` VARCHAR(100),
  `tanggal_lahir` DATE,
  `jenis_kelamin` ENUM('L','P') NOT NULL,
  `agama` VARCHAR(20),
  `pendidikan` VARCHAR(50),
  `pekerjaan` VARCHAR(100),
  `status_perkawinan` ENUM('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') DEFAULT 'Belum Kawin',
  `status_keluarga` VARCHAR(50),
  `no_hp` VARCHAR(20),
  `email` VARCHAR(100),
  `alamat` TEXT,
  `rt` VARCHAR(5),
  `rw` VARCHAR(5),
  `foto_ktp` VARCHAR(255),
  `foto_kk` VARCHAR(255),
  `status_aktif` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `warga` (`nik`,`no_kk`,`nama`,`tempat_lahir`,`tanggal_lahir`,`jenis_kelamin`,`agama`,`pendidikan`,`pekerjaan`,`status_perkawinan`,`status_keluarga`,`no_hp`,`email`,`alamat`,`rt`,`rw`) VALUES
('3201010101010001','3201010101010001','Budi Santoso','Bogor','1985-03-15','L','Islam','S1','Karyawan Swasta','Kawin','Kepala Keluarga','081234567801','budi@siwarga.id','Jl. Mawar No. 12','01','02'),
('3201010101010002','3201010101010001','Sri Wahyuni','Bandung','1988-06-22','P','Islam','SMA','Ibu Rumah Tangga','Kawin','Istri','081234567802','sri@siwarga.id','Jl. Mawar No. 12','01','02'),
('3201010101010003','3201010101010001','Aditya Santoso','Bogor','2012-01-10','L','Islam','SD','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Mawar No. 12','01','02'),
('3201010101010004','3201010101010001','Aulia Santoso','Bogor','2015-09-05','P','Islam','SD','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Mawar No. 12','01','02'),
('3201010101010005','3201010101010002','Andi Pratama','Jakarta','1980-07-19','L','Islam','S1','Wiraswasta','Kawin','Kepala Keluarga','081234567803','andi@siwarga.id','Jl. Mawar No. 14','01','02'),
('3201010101010006','3201010101010002','Lina Marlina','Bogor','1983-11-30','P','Islam','D3','Pegawai Negeri','Kawin','Istri','081234567804','lina@siwarga.id','Jl. Mawar No. 14','01','02'),
('3201010101010007','3201010101010002','Reza Pratama','Bogor','2010-04-12','L','Islam','SMP','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Mawar No. 14','01','02'),
('3201010101010008','3201010101010003','Siti Aminah','Bogor','1975-02-28','P','Islam','SMA','Pedagang','Cerai Hidup','Kepala Keluarga','081234567805','siti@siwarga.id','Jl. Melati No. 5','01','02'),
('3201010101010009','3201010101010003','Fajar Maulana','Bogor','2005-08-14','L','Islam','SMA','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Melati No. 5','01','02'),
('3201010101010010','3201010101010004','Hendro Wijaya','Surabaya','1978-12-01','L','Kristen','S2','PNS','Kawin','Kepala Keluarga','081234567806','hendro@siwarga.id','Jl. Melati No. 8','01','02'),
('3201010101010011','3201010101010004','Maria Wijaya','Surabaya','1982-03-25','P','Kristen','S1','Guru','Kawin','Istri','081234567807','maria@siwarga.id','Jl. Melati No. 8','01','02'),
('3201010101010012','3201010101010004','David Wijaya','Bogor','2008-11-08','L','Kristen','SMP','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Melati No. 8','01','02'),
('3201010101010013','3201010101010004','Sarah Wijaya','Bogor','2011-05-17','P','Kristen','SD','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Melati No. 8','01','02'),
('3201010101010014','3201010101010004','Ibu Wijaya','Surabaya','1950-01-20','P','Kristen','SD','Pensiunan','Cerai Mati','Mertua',NULL,NULL,'Jl. Melati No. 8','01','02'),
('3201010101010015','3201010101010005','Ratna Dewi','Yogyakarta','1990-09-09','P','Hindu','S1','Dokter','Kawin','Kepala Keluarga','081234567808','ratna@siwarga.id','Jl. Anggrek No. 21','01','02'),
('3201010101010016','3201010101010005','Wayan Sudiarta','Bali','1988-04-04','L','Hindu','S1','Arsitek','Kawin','Suami','081234567809','wayan@siwarga.id','Jl. Anggrek No. 21','01','02'),
('3201010101010017','3201010101010005','Putu Dewi','Bogor','2018-12-12','P','Hindu','TK','Pelajar','Belum Kawin','Anak',NULL,NULL,'Jl. Anggrek No. 21','01','02'),
('3201010101010018','3201010101010001','Joko Susanto','Solo','1995-06-16','L','Islam','SMA','Sopir','Kawin','Lainnya','081234567810','joko@siwarga.id','Jl. Mawar No. 12','01','02'),
('3201010101010019','3201010101010002','Dewi Lestari','Bogor','1992-10-01','P','Islam','S1','Bidan','Kawin','Lainnya','081234567811','dewi@siwarga.id','Jl. Mawar No. 14','01','02'),
('3201010101010020','3201010101010003','Bagas Setiawan','Bogor','2002-07-07','L','Islam','SMA','Mahasiswa','Belum Kawin','Anak','081234567812','bagas@siwarga.id','Jl. Melati No. 5','01','02');

-- ======================
-- PENDATANG
-- ======================
DROP TABLE IF EXISTS `pendatang`;
CREATE TABLE `pendatang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `nik` VARCHAR(20),
  `asal_daerah` VARCHAR(255),
  `tujuan_tinggal` VARCHAR(255),
  `lama_tinggal` VARCHAR(50),
  `penjamin` VARCHAR(100),
  `alamat_tinggal` TEXT,
  `rt` VARCHAR(5),
  `rw` VARCHAR(5),
  `tanggal_datang` DATE,
  `file_identitas` VARCHAR(255),
  `status` ENUM('aktif','selesai') DEFAULT 'aktif',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pendatang` (`nama`,`nik`,`asal_daerah`,`tujuan_tinggal`,`lama_tinggal`,`penjamin`,`alamat_tinggal`,`rt`,`rw`,`tanggal_datang`,`status`) VALUES
('Rendi Saputra','3273010101010001','Bandung','Bekerja','6 bulan','Budi Santoso','Jl. Mawar No. 12','01','02','2025-08-10','aktif'),
('Nurul Hidayah','3372010101010002','Semarang','Kuliah','4 tahun','Hendro Wijaya','Jl. Melati No. 8','01','02','2025-09-01','aktif'),
('Bambang Triadi','3174010101010003','Jakarta','Bekerja','1 tahun','Andi Pratama','Jl. Mawar No. 14','01','02','2024-11-15','selesai');

-- ======================
-- KELAHIRAN
-- ======================
DROP TABLE IF EXISTS `kelahiran`;
CREATE TABLE `kelahiran` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_bayi` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L','P'),
  `tempat_lahir` VARCHAR(100),
  `tanggal_lahir` DATE,
  `nama_ayah` VARCHAR(100),
  `nama_ibu` VARCHAR(100),
  `no_kk` VARCHAR(20),
  `rt` VARCHAR(5),
  `rw` VARCHAR(5),
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kelahiran` (`nama_bayi`,`jenis_kelamin`,`tempat_lahir`,`tanggal_lahir`,`nama_ayah`,`nama_ibu`,`no_kk`,`rt`,`rw`) VALUES
('Aulia Santoso','P','Bogor','2015-09-05','Budi Santoso','Sri Wahyuni','3201010101010001','01','02'),
('Putu Dewi','P','Bogor','2018-12-12','Wayan Sudiarta','Ratna Dewi','3201010101010005','01','02'),
('Bayi Pratama','L','Bogor','2025-10-20','Andi Pratama','Lina Marlina','3201010101010002','01','02');

-- ======================
-- KEMATIAN
-- ======================
DROP TABLE IF EXISTS `kematian`;
CREATE TABLE `kematian` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(20),
  `nama` VARCHAR(100) NOT NULL,
  `tanggal_meninggal` DATE,
  `tempat_meninggal` VARCHAR(100),
  `penyebab` VARCHAR(255),
  `surat_keterangan` VARCHAR(255),
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kematian` (`nik`,`nama`,`tanggal_meninggal`,`tempat_meninggal`,`penyebab`) VALUES
('3201010101010099','Pak Hartono','2024-12-15','RS Bogor Medika','Sakit jantung'),
('3201010101010098','Ibu Mariam','2025-03-20','Rumah','Usia lanjut');

-- ======================
-- PINDAH / KELUAR
-- ======================
DROP TABLE IF EXISTS `pindah`;
CREATE TABLE `pindah` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(20),
  `nama` VARCHAR(100) NOT NULL,
  `tujuan_pindah` VARCHAR(255),
  `tanggal_pindah` DATE,
  `alasan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pindah` (`nik`,`nama`,`tujuan_pindah`,`tanggal_pindah`,`alasan`) VALUES
('3201010101010050','Doni Setiawan','Jakarta Selatan','2025-01-15','Pekerjaan'),
('3201010101010051','Keluarga Suparman','Surabaya','2024-08-20','Mengikuti suami pindah tugas');

-- ======================
-- PENGAJUAN SURAT
-- ======================
DROP TABLE IF EXISTS `pengajuan_surat`;
CREATE TABLE `pengajuan_surat` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nomor_surat` VARCHAR(50),
  `nik` VARCHAR(20),
  `nama` VARCHAR(100),
  `jenis_surat` VARCHAR(100),
  `keperluan` TEXT,
  `keterangan` TEXT,
  `status` ENUM('pending','verifikasi','approved','ditolak') DEFAULT 'pending',
  `catatan_admin` TEXT,
  `tanggal_pengajuan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `tanggal_approve` DATETIME NULL,
  `user_id` INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pengajuan_surat` (`nomor_surat`,`nik`,`nama`,`jenis_surat`,`keperluan`,`status`,`user_id`) VALUES
('001/RT01/RW02/2025','3201010101010001','Budi Santoso','Surat Domisili','Persyaratan pinjaman bank','approved',4),
('002/RT01/RW02/2025','3201010101010005','Andi Pratama','Surat Pengantar SKCK','Melamar pekerjaan','verifikasi',NULL),
('003/RT01/RW02/2025','3201010101010008','Siti Aminah','Surat Tidak Mampu','Pengobatan rumah sakit','pending',NULL),
('004/RT01/RW02/2025','3201010101010010','Hendro Wijaya','Surat Usaha','Mendaftarkan UMKM','approved',NULL);

-- ======================
-- IURAN
-- ======================
DROP TABLE IF EXISTS `iuran`;
CREATE TABLE `iuran` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `no_kk` VARCHAR(20),
  `nama_kk` VARCHAR(100),
  `jenis_iuran` ENUM('bulanan','keamanan','kebersihan','sosial') DEFAULT 'bulanan',
  `bulan` VARCHAR(7),
  `nominal` DECIMAL(12,2),
  `tanggal_bayar` DATE,
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `iuran` (`no_kk`,`nama_kk`,`jenis_iuran`,`bulan`,`nominal`,`tanggal_bayar`) VALUES
('3201010101010001','Budi Santoso','bulanan','2025-10',50000,'2025-10-05'),
('3201010101010001','Budi Santoso','keamanan','2025-10',30000,'2025-10-05'),
('3201010101010002','Andi Pratama','bulanan','2025-10',50000,'2025-10-07'),
('3201010101010003','Siti Aminah','bulanan','2025-10',50000,'2025-10-12'),
('3201010101010004','Hendro Wijaya','bulanan','2025-10',50000,'2025-10-03'),
('3201010101010005','Ratna Dewi','bulanan','2025-10',50000,'2025-10-04'),
('3201010101010001','Budi Santoso','bulanan','2025-11',50000,'2025-11-08'),
('3201010101010002','Andi Pratama','bulanan','2025-11',50000,'2025-11-10'),
('3201010101010004','Hendro Wijaya','kebersihan','2025-11',25000,'2025-11-06');

-- ======================
-- KEUANGAN
-- ======================
DROP TABLE IF EXISTS `keuangan`;
CREATE TABLE `keuangan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tipe` ENUM('pemasukan','pengeluaran') NOT NULL,
  `kategori` VARCHAR(100),
  `jumlah` DECIMAL(12,2),
  `tanggal` DATE,
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `keuangan` (`tipe`,`kategori`,`jumlah`,`tanggal`,`keterangan`) VALUES
('pemasukan','Iuran Bulanan',300000,'2025-10-31','Total iuran Oktober'),
('pengeluaran','Honor Petugas Kebersihan',150000,'2025-10-31','Honor bulanan'),
('pemasukan','Iuran Bulanan',150000,'2025-11-30','Total iuran November (sebagian)'),
('pengeluaran','Pembelian Lampu Jalan',75000,'2025-11-12','3 buah lampu LED'),
('pengeluaran','Konsumsi Rapat RT',50000,'2025-11-20','Snack dan minuman');

-- ======================
-- BUKU TAMU
-- ======================
DROP TABLE IF EXISTS `buku_tamu`;
CREATE TABLE `buku_tamu` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_tamu` VARCHAR(100) NOT NULL,
  `no_identitas` VARCHAR(50),
  `asal` VARCHAR(255),
  `tujuan` VARCHAR(255),
  `nama_dikunjungi` VARCHAR(100),
  `jam_masuk` DATETIME,
  `jam_keluar` DATETIME NULL,
  `foto_identitas` VARCHAR(255),
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `buku_tamu` (`nama_tamu`,`no_identitas`,`asal`,`tujuan`,`nama_dikunjungi`,`jam_masuk`,`jam_keluar`) VALUES
('Hasan Ali','3201010101010777','Bandung','Silaturahmi','Budi Santoso','2025-11-15 14:00:00','2025-11-15 17:30:00'),
('Tini Suryani','3201010101010888','Jakarta','Mengantar paket','Hendro Wijaya','2025-11-20 10:15:00','2025-11-20 10:30:00'),
('Pak Pos','3201010101010999','Kantor Pos','Pengiriman','Ratna Dewi','2025-11-22 09:00:00',NULL);

-- ======================
-- LAPORAN KEAMANAN
-- ======================
DROP TABLE IF EXISTS `laporan_keamanan`;
CREATE TABLE `laporan_keamanan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pelapor` VARCHAR(100),
  `jenis_laporan` ENUM('kehilangan','gangguan','keluhan') DEFAULT 'keluhan',
  `judul` VARCHAR(255),
  `isi` TEXT,
  `tanggal_kejadian` DATE,
  `status` ENUM('baru','proses','selesai') DEFAULT 'baru',
  `tindak_lanjut` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `laporan_keamanan` (`pelapor`,`jenis_laporan`,`judul`,`isi`,`tanggal_kejadian`,`status`) VALUES
('Budi Santoso','kehilangan','Sandal hilang','Sandal di teras hilang malam hari','2025-11-10','proses'),
('Hendro Wijaya','gangguan','Suara bising kendaraan','Motor knalpot brong sering lewat tengah malam','2025-11-18','baru'),
('Ratna Dewi','keluhan','Penerangan jalan rusak','Lampu jalan depan rumah no 21 mati sudah seminggu','2025-11-20','selesai');

-- ======================
-- AUDIT LOG
-- ======================
DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT,
  `aksi` VARCHAR(255),
  `modul` VARCHAR(50),
  `detail` TEXT,
  `ip_address` VARCHAR(50),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- AKUN DEFAULT (ganti password setelah login!)
-- superadmin / admin123    -> Super Admin
-- adminrtrw  / admin123    -> Admin RT/RW
-- ketua      / ketua123    -> Ketua RT/RW
-- warga01    / warga123    -> Warga
-- =====================================================
