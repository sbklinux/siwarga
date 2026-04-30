-- =====================================================
-- SIWARGA - Migration v1.1
-- Tambahan fitur: Reset Password Request
-- Jalankan di phpMyAdmin: Pilih DB `siwarga` > Tab SQL > Paste > Go
-- =====================================================
USE `siwarga`;

DROP TABLE IF EXISTS `password_reset_requests`;
CREATE TABLE `password_reset_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100),
  `no_hp` VARCHAR(20),
  `alasan` TEXT,
  `status` ENUM('pending','approved','ditolak') DEFAULT 'pending',
  `new_password_plain` VARCHAR(50) DEFAULT NULL,
  `processed_by` INT DEFAULT NULL,
  `processed_at` DATETIME DEFAULT NULL,
  `ip_address` VARCHAR(50),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
