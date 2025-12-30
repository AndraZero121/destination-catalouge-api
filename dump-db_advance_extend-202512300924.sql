-- Quick-start data dump for app-tourism-server
-- Import with: mysql -uUSER -pPASSWORD db_advance_extend < dump-db_advance_extend-202512300924.sql

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `saved_destinations`;
DROP TABLE IF EXISTS `destination_photos`;
DROP TABLE IF EXISTS `destinations`;
DROP TABLE IF EXISTS `cities`;
DROP TABLE IF EXISTS `provinces`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `provinces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `province_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_province_id_foreign` (`province_id`),
  CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `province_id` bigint unsigned NOT NULL,
  `city_id` bigint unsigned NOT NULL,
  `budget_min` decimal(12,2) DEFAULT NULL,
  `budget_max` decimal(12,2) DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `destinations_category_id_foreign` (`category_id`),
  KEY `destinations_province_id_foreign` (`province_id`),
  KEY `destinations_city_id_foreign` (`city_id`),
  CONSTRAINT `destinations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `destinations_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `destinations_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `destination_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `destination_id` bigint unsigned NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `destination_photos_destination_id_foreign` (`destination_id`),
  CONSTRAINT `destination_photos_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `saved_destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `saved_destinations_user_id_foreign` (`user_id`),
  KEY `saved_destinations_destination_id_foreign` (`destination_id`),
  CONSTRAINT `saved_destinations_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_destinations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_destination_id_foreign` (`destination_id`),
  CONSTRAINT `reviews_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`,`name`,`email`,`password`,`phone`,`photo_url`,`created_at`,`updated_at`) VALUES
(1,'Admin Utama','admin@example.com','$2y$12$C9uuTJcfmXypeteDkV6uR.8RZunoxcW2A4tTGUliOH4kyjSExH.Di','628111111111',NULL,NOW(),NOW()),
(2,'Budi Santoso','budi@example.com','$2y$12$C9uuTJcfmXypeteDkV6uR.8RZunoxcW2A4tTGUliOH4kyjSExH.Di','6281234567890',NULL,NOW(),NOW()),
(3,'Siti Aminah','siti@example.com','$2y$12$C9uuTJcfmXypeteDkV6uR.8RZunoxcW2A4tTGUliOH4kyjSExH.Di','6289876543210',NULL,NOW(),NOW());

INSERT INTO `categories` (`id`,`name`,`slug`,`created_at`,`updated_at`) VALUES
(1,'Pantai','pantai',NOW(),NOW()),
(2,'Gunung','gunung',NOW(),NOW()),
(3,'Taman','taman',NOW(),NOW()),
(4,'Museum','museum',NOW(),NOW()),
(5,'Kuliner','kuliner',NOW(),NOW()),
(6,'Belanja','belanja',NOW(),NOW()),
(7,'Air Terjun','air-terjun',NOW(),NOW()),
(8,'Danau','danau',NOW(),NOW()),
(9,'Sejarah','sejarah',NOW(),NOW());

INSERT INTO `provinces` (`id`,`name`,`created_at`,`updated_at`) VALUES
(1,'Jawa Tengah',NOW(),NOW()),
(2,'Jawa Barat',NOW(),NOW()),
(3,'Jawa Timur',NOW(),NOW()),
(4,'Bali',NOW(),NOW()),
(5,'DKI Jakarta',NOW(),NOW());

INSERT INTO `cities` (`id`,`province_id`,`name`,`created_at`,`updated_at`) VALUES
(1,1,'Semarang',NOW(),NOW()),
(2,1,'Solo',NOW(),NOW()),
(3,1,'Magelang',NOW(),NOW()),
(4,2,'Bandung',NOW(),NOW()),
(5,2,'Bogor',NOW(),NOW()),
(6,2,'Depok',NOW(),NOW()),
(7,3,'Surabaya',NOW(),NOW()),
(8,3,'Malang',NOW(),NOW()),
(9,3,'Batu',NOW(),NOW()),
(10,4,'Denpasar',NOW(),NOW()),
(11,4,'Ubud',NOW(),NOW()),
(12,4,'Kuta',NOW(),NOW()),
(13,5,'Jakarta Pusat',NOW(),NOW()),
(14,5,'Jakarta Selatan',NOW(),NOW()),
(15,5,'Kepulauan Seribu',NOW(),NOW());

INSERT INTO `destinations` (`id`,`name`,`description`,`category_id`,`province_id`,`city_id`,`budget_min`,`budget_max`,`facilities`,`latitude`,`longitude`,`created_at`,`updated_at`) VALUES
(1,'Pantai Parangtritis','Pantai pasir hitam yang populer untuk menikmati sunset dan aktivitas ATV di sepanjang bibir pantai.',1,1,1,25000.00,100000.00,'Area parkir, penyewaan ATV, gazebo, warung makan',-7.94250000,110.33040000,NOW(),NOW()),
(2,'Gunung Bromo','Salah satu ikon wisata Jawa Timur yang terkenal dengan sunrise view di lautan pasir dan kawah aktifnya.',2,3,8,150000.00,500000.00,'Jeep tour, area parkir, mushola, tempat makan',-7.94249300,112.95301200,NOW(),NOW()),
(3,'Kawah Putih Ciwidey','Danau kawah dengan air berwarna kebiruan dan kabut tipis yang memberikan suasana dramatis.',3,2,4,50000.00,150000.00,'Shuttle, spot foto, toilet, tempat makan',-7.16666900,107.40279400,NOW(),NOW()),
(4,'Pura Tanah Lot','Ikon Bali dengan pura di atas batu karang, populer untuk menikmati sunset dramatis.',1,4,12,60000.00,200000.00,'Area parkir, spot foto, toko suvenir, warung makan',-8.62173700,115.08664700,NOW(),NOW()),
(5,'Monumen Nasional','Landmark Jakarta dengan museum sejarah dan dek observasi untuk melihat kota dari ketinggian.',9,5,13,20000.00,75000.00,'Museum, lift observasi, area parkir, taman',-6.17539200,106.82715300,NOW(),NOW()),
(6,'Curug Cimahi','Air terjun tinggi di Bandung dengan nuansa hutan pinus dan udara sejuk.',7,2,4,25000.00,75000.00,'Area parkir, deck foto, toilet',-6.82065000,107.58058900,NOW(),NOW()),
(7,'Lawang Sewu','Bangunan heritage berarsitektur Belanda di Semarang dengan tur sejarah.',9,1,1,20000.00,80000.00,'Pemandu tur, area parkir, galeri',-6.98026500,110.40908800,NOW(),NOW()),
(8,'Ranu Kumbolo','Danau jernih di jalur pendakian Semeru dengan pemandangan bintang saat malam.',8,3,8,100000.00,350000.00,'Camping ground, jalur trekking, area istirahat',-8.00909500,112.93496700,NOW(),NOW());

INSERT INTO `destination_photos` (`id`,`destination_id`,`photo_url`,`order`,`created_at`,`updated_at`) VALUES
(1,1,'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',1,NOW(),NOW()),
(2,1,'https://images.unsplash.com/photo-1493558103817-58b2924bce98',2,NOW(),NOW()),
(3,2,'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee',1,NOW(),NOW()),
(4,2,'https://images.unsplash.com/photo-1441974231531-c6227db76b6e',2,NOW(),NOW()),
(5,3,'https://images.unsplash.com/photo-1469474968028-56623f02e42e',1,NOW(),NOW()),
(6,3,'https://images.unsplash.com/photo-1437622368342-7a3d73a34c8f',2,NOW(),NOW()),
(7,4,'https://images.unsplash.com/photo-1501785888041-af3ef285b470',1,NOW(),NOW()),
(8,4,'https://images.unsplash.com/photo-1505236732-1c7978f2d2c7',2,NOW(),NOW()),
(9,5,'https://images.unsplash.com/photo-1500375592092-40eb2168fd21',1,NOW(),NOW()),
(10,5,'https://images.unsplash.com/photo-1548670984-34846dc9d6b7',2,NOW(),NOW()),
(11,6,'https://images.unsplash.com/photo-1502082553048-f009c37129b9',1,NOW(),NOW()),
(12,6,'https://images.unsplash.com/photo-1448518184296-a22facb4446f',2,NOW(),NOW()),
(13,7,'https://images.unsplash.com/photo-1489515217757-5fd1be406fef',1,NOW(),NOW()),
(14,7,'https://images.unsplash.com/photo-1467269204594-9661b134dd2b',2,NOW(),NOW()),
(15,8,'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429',1,NOW(),NOW()),
(16,8,'https://images.unsplash.com/photo-1501785888041-af3ef285b470',2,NOW(),NOW());

SET foreign_key_checks = 1;
