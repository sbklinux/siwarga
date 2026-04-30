-- =====================================================
-- SIWARGA - Migration v1.2
-- Tambahan fitur: Tabel Pengaturan Kop Surat Dinamis
-- =====================================================
USE `siwarga`;

DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key_name` VARCHAR(50) UNIQUE NOT NULL,
  `value` TEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pengaturan` (`key_name`,`value`) VALUES
('nama_kabupaten', 'PEMERINTAH KABUPATEN BEKASI'),
('nama_rt_rw', 'RT 01 / RW 19'),
('nama_perumahan', 'PERUMAHAN KIRANA CIBITUNG'),
('alamat_lengkap', 'Desa Wanajaya, Kecamatan Cibitung, Kabupaten Bekasi, Jawa Barat'),
('nama_kota_ttd', 'Cibitung'),
('nama_ketua', 'Bapak Suryanto'),
('jabatan_ketua', 'Ketua RT 01 / RW 19'),
('no_telp', '(021) 1234567'),
('email_resmi', 'rt01rw19@kirana-cibitung.id'),
('ttd_file', ''),
('kop_footer', 'Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.');
