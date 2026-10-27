-- Campus Lost & Found: full schema + demo data for MySQL / MariaDB.
-- Import this in phpMyAdmin (InfinityFree > MySQL Databases > phpMyAdmin > Import).
-- Demo accounts all use the password: password  (change the admin password after importing).

/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Electronics','electronics','2026-10-27 17:25:10','2026-10-27 17:25:10'),
(2,'Wallets & Cards','wallets-cards','2026-10-27 17:25:10','2026-10-27 17:25:10'),
(3,'Keys','keys','2026-10-27 17:25:10','2026-10-27 17:25:10'),
(4,'Bags','bags','2026-10-27 17:25:10','2026-10-27 17:25:10'),
(5,'Books & Stationery','books-stationery','2026-10-27 17:25:10','2026-10-27 17:25:10'),
(6,'Others','others','2026-10-27 17:25:10','2026-10-27 17:25:10'),
(7,'Umbrellas','umbrellas','2026-10-27 17:25:10','2026-10-27 17:25:10');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `claims` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `claims_item_id_user_id_unique` (`item_id`,`user_id`),
  KEY `claims_user_id_foreign` (`user_id`),
  CONSTRAINT `claims_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `claims_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `claims` DISABLE KEYS */;
INSERT INTO `claims` VALUES
(1,1,3,'They are mine! The case has \"SR\" engraved on the back and the left earbud tip is a slightly larger size than the right one.','WhatsApp 0812-1111-2222','pending','2026-10-26 18:32:00','2026-10-26 22:32:00'),
(2,1,4,'I lost my AirPods in the library on Monday. Mine have a crack on the lid hinge and are paired to \"Andi\'s iPhone\".','LINE @andi.pratama','pending','2026-10-26 21:32:00','2026-10-27 01:32:00'),
(3,2,2,'I think I found it! A brown wallet was on the chair next to the Padang food stall at around 12.30. I handed it to the canteen cashier.','WhatsApp 0813-3333-4444','pending','2026-10-25 15:18:00','2026-10-25 19:18:00'),
(4,3,5,'This is mine. The sticker on the back says \"Dewi L. - IF 2024\" and the battery cover is held with clear tape.','WhatsApp 0857-5555-6666','pending','2026-10-25 13:33:00','2026-10-25 17:33:00'),
(5,10,4,'I found a white stylus on a desk in Studio 2, but it might be a Logitech Crayon rather than an Apple Pencil.','LINE @andi.pratama','rejected','2026-10-26 16:35:00','2026-10-26 20:35:00'),
(6,11,2,'That is my motorbike key. The keychain is a black metal Batman logo and there is a small red remote fob too.','WhatsApp 0813-3333-4444','approved','2026-10-24 15:24:00','2026-10-24 19:24:00'),
(7,11,5,'I lost a Honda key near the parking lot last week, could it be mine?','WhatsApp 0857-5555-6666','rejected','2026-10-24 18:24:00','2026-10-24 22:24:00'),
(8,12,5,'Mine! The cable has a strip of yellow tape near the connector and the charger has a dent on one corner.','WhatsApp 0857-5555-6666','approved','2026-10-25 16:29:00','2026-10-25 20:29:00'),
(9,13,3,'I found a navy Herschel backpack outside 302 and brought it to the security post on the ground floor.','WhatsApp 0812-1111-2222','approved','2026-10-23 12:24:00','2026-10-23 16:24:00'),
(10,14,3,'It is my tote. Inside there should be a folding umbrella, wet wipes and an empty Tupperware.','WhatsApp 0812-1111-2222','approved','2026-10-24 14:09:00','2026-10-24 18:09:00'),
(11,15,2,'That is mine, the Mandiri e-money card has my name printed on it: Budi Santoso.','WhatsApp 0813-3333-4444','approved','2026-10-25 14:14:00','2026-10-25 18:14:00');
/*!40000 ALTER TABLE `claims` ENABLE KEYS */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `type` enum('lost','found') NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `status` enum('open','claimed','returned') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_user_id_foreign` (`user_id`),
  KEY `items_category_id_foreign` (`category_id`),
  KEY `items_status_index` (`status`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES
(1,2,1,'found','AirPods Pro 2 in a matte black case','Found on the centre table of the Central Library reading room, 2nd floor. Small white scratch near the charging port. I\'m keeping it until the owner can describe what\'s engraved on the case.','Central Library, 2nd floor reading room','2026-10-26','items/electronics-earbuds.jpg','open','2026-10-26 16:32:00','2026-10-27 12:32:00'),
(2,3,2,'lost','Brown leather Fossil wallet with my KTM','Bi-fold brown wallet with my student ID (KTM), a BNI ATM card and my SIM C. Probably dropped it around lunchtime.','Tower 1 canteen','2026-10-25','items/wallet-brown.jpg','open','2026-10-25 13:18:00','2026-10-26 09:18:00'),
(3,4,5,'found','Casio fx-991EX ClassWiz calculator','Found under seat 18 in the Auditorium after the Calculus midterm. There is a name sticker on the back.','Main Auditorium, seat 18','2026-10-25','items/stationery-calculator.jpg','open','2026-10-25 11:33:00','2026-10-26 07:33:00'),
(4,4,6,'found','Blue Hydro Flask tumbler with stickers','Found on the bleachers after basketball practice. Covered in stickers, including a GitHub Octocat.','Sports Center indoor court','2026-10-26','items/others-tumbler.jpg','open','2026-10-26 08:11:00','2026-10-27 04:11:00'),
(5,2,1,'lost','Logitech MX Master 3S mouse','Left my wireless mouse plugged into PC #12 in the Computer Graphics Lab. The USB receiver was still attached.','Computer Graphics Lab (Lab 305)','2026-10-24','items/electronics-mouse.jpg','open','2026-10-24 17:46:00','2026-10-25 13:46:00'),
(6,3,2,'found','Flazz BCA card with a custom skin','Found next to the self-service printer in the Student Center lobby.','Student Center, 1st floor lobby','2026-10-26',NULL,'open','2026-10-26 14:32:00','2026-10-27 10:32:00'),
(7,4,3,'lost','Boarding house key with green tag \"KOS 104\"','Brass key on a green acrylic tag. Lost somewhere between the faculty gate and the gazebo.','Faculty main gate to the gazebo path','2026-10-23','items/keys.jpg','open','2026-10-23 15:26:00','2026-10-24 11:26:00'),
(8,2,5,'lost','Black spiral notebook: Algorithms and Data Structures','Handwritten lecture notes on dynamic programming and graphs. I really need it for finals.','Department library, room A','2026-10-22','items/stationery-notebook.jpg','open','2026-10-22 12:00:00','2026-10-23 08:00:00'),
(9,5,6,'lost','Ray-Ban prescription glasses in a black case','Black metal frame, high prescription lenses, inside a black leather case.','Faculty mosque, prayer hall','2026-10-21','items/others-glasses.jpg','open','2026-10-21 12:56:00','2026-10-22 08:56:00'),
(10,3,1,'lost','Apple Pencil (2nd generation)','Lost in Design Studio 2 during the UI/UX workshop. Engraved with \"SR\".','Multimedia Design Studio 2','2026-10-26',NULL,'open','2026-10-26 14:35:00','2026-10-27 10:35:00'),
(11,4,3,'found','Honda Vario key with a Batman keychain','Found hanging on the fence of motorcycle parking B near the Informatics building.','Informatics motorcycle parking B','2026-10-24',NULL,'claimed','2026-10-24 13:24:00','2026-10-25 09:24:00'),
(12,3,1,'found','Anker 65W charger with USB-C cable','Left plugged into the wall socket behind bench #4 at the central gazebo.','Central campus gazebo','2026-10-25','items/electronics-charger.jpg','claimed','2026-10-25 14:29:00','2026-10-26 10:29:00'),
(13,2,4,'lost','Navy Herschel backpack','Left on the bench outside classroom 302 after the Operating Systems lecture. Has a notebook and pencil case inside.','Classroom 302 corridor','2026-10-23','items/bag-backpack.jpg','returned','2026-10-23 10:24:00','2026-10-24 06:24:00'),
(14,4,4,'found','Cream canvas tote bag, \"Stay Curious\" print','Found on the ground floor benches of Building C.','Building C, ground floor benches','2026-10-24','items/bag-tote.jpg','returned','2026-10-24 12:09:00','2026-10-25 08:09:00'),
(15,5,2,'found','Black cardholder with e-money and TransJakarta cards','Found on a seat of campus shuttle bus #2.','Campus shuttle bus #2','2026-10-25','items/wallet-cardholder.jpg','returned','2026-10-25 12:14:00','2026-10-26 08:14:00');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_10_27_000001_create_categories_table',1),
(5,'2026_10_27_000002_create_items_table',1),
(6,'2026_10_27_000003_create_claims_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Campus Admin','admin@lostfound.test','2026-10-27 17:25:09','$2y$12$TlTJjD42bI5pGzHY0DCl6O0ENJG1MzzfQBEJrqQBw9Nuscu70bEqa','admin',NULL,'2026-10-27 17:25:09','2026-10-27 17:25:09'),
(2,'Budi Santoso','budi@lostfound.test','2026-10-27 17:25:09','$2y$12$yb4/zRNMniHOkVvcKoTSAOCa18dTjUeHYKDEEhlYYwUgufzAjNQ8u','user',NULL,'2026-10-27 17:25:09','2026-10-27 17:25:09'),
(3,'Siti Rahma','siti@lostfound.test','2026-10-27 17:25:09','$2y$12$w.Yyn28H0cTr/JepOEOpkOwEYBmCyvbxqD1dnwHooz2qAiERDjbIG','user',NULL,'2026-10-27 17:25:09','2026-10-27 17:25:09'),
(4,'Andi Pratama','andi@lostfound.test','2026-10-27 17:25:10','$2y$12$e/vTeWnwHSbAbY3uiDIEoOEAySwMNQpgVCekvdyUcwnIbMgYoXw7.','user',NULL,'2026-10-27 17:25:10','2026-10-27 17:25:10'),
(5,'Dewi Lestari','dewi@lostfound.test','2026-10-27 17:25:10','$2y$12$yQGhS8ucsPLraJBUy2uV8eID4cO4dfwbMQgQA.ooYNL2LPLkv7GEq','user',NULL,'2026-10-27 17:25:10','2026-10-27 17:25:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

