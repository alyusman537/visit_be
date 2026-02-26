-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               12.1.2-MariaDB-ubu2404 - mariadb.org binary distribution
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             12.13.1.1
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table visit.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` uuid NOT NULL DEFAULT uuid(),
  `name` varchar(100) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `latitude` double(10,8) DEFAULT NULL,
  `longitude` double(11,8) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `imagePath` varchar(100) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table visit.customers: ~0 rows (approximately)

-- Dumping structure for table visit.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` uuid NOT NULL DEFAULT uuid(),
  `user_id` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `role` varchar(30) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table visit.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `user_id`, `email`, `name`, `pass`, `role`, `aktif`, `created_at`, `updated_at`) VALUES
	('96830a27-04a3-11f1-b66e-1a13a891ef7a', 'alyusman', 'liusmanx@gmail.com', 'ali usman', '$2y$10$CCSeGIUrt.2AVSH1iNc.GOBq1O7M4I4nQC5jL0lQdaheC7uRjAFyy', 'admin', 1, '2026-02-08 04:06:47', '2026-02-08 04:06:47');

-- Dumping structure for table visit.visits
CREATE TABLE IF NOT EXISTS `visits` (
  `id` uuid NOT NULL DEFAULT uuid(),
  `customer_id` uuid NOT NULL,
  `latitude` double(10,8) DEFAULT NULL,
  `longitude` double(10,8) DEFAULT NULL,
  `photo_path` varchar(200) DEFAULT NULL,
  `visit_date` datetime DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `synced` tinyint(1) DEFAULT 1,
  `user_id` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Dumping data for table visit.visits: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
