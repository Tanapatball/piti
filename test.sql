-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: piti
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` varchar(255) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES ('CAT01','อาหารแห้ง','2026-03-08 03:12:07','2026-03-08 03:12:07'),('CAT02','เครื่องดื่ม','2026-03-08 03:12:07','2026-03-08 03:12:07'),('CAT03','ของใช้สำนักงาน','2026-03-08 03:12:07','2026-03-08 03:12:07'),('CAT04','อุปกรณ์ทำความสะอาด','2026-03-08 03:12:07','2026-03-08 03:12:07'),('CAT05','วัตถุดิบ','2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issue_items`
--

DROP TABLE IF EXISTS `issue_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issue_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_out_id` bigint(20) unsigned NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `fraction_qty` int(11) NOT NULL DEFAULT 0,
  `net_weight` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `issue_items_stock_out_id_foreign` (`stock_out_id`),
  KEY `issue_items_product_id_foreign` (`product_id`),
  CONSTRAINT `issue_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `issue_items_stock_out_id_foreign` FOREIGN KEY (`stock_out_id`) REFERENCES `stock_outs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issue_items`
--

LOCK TABLES `issue_items` WRITE;
/*!40000 ALTER TABLE `issue_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `issue_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_08_17_161205_create_roles_table',1),(4,'2025_08_17_161451_create_users_table',1),(5,'2025_08_18_170911_create_warehouses_table',1),(6,'2025_08_18_171910_create_categories_table',1),(7,'2025_08_18_171915_create_products_table',1),(8,'2025_08_18_171916_create_receive_types_table',1),(9,'2025_08_18_171921_create_transactions_table',1),(10,'2025_08_18_171922_create_transaction_items_table',1),(11,'2025_08_28_033658_add_current_stock_to_products_table',1),(12,'2025_08_28_035347_create_stock_outs_table',1),(13,'2025_10_02_185920_create_issue_items_table',1),(14,'2026_03_17_151615_add_receive_stock_alert_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('admin@example.com','$2y$12$Wb/XLCfiu.Tf6b6oHZ47ru/yK8jEjDibeYHvN2g..3hiRWkNH2Qbq','2026-03-16 08:07:38');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `stock_min` int(11) DEFAULT NULL,
  `stock_max` int(11) DEFAULT NULL,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `size` varchar(50) DEFAULT NULL,
  `pack` varchar(50) DEFAULT NULL,
  `weight_per_kg` decimal(10,2) DEFAULT NULL,
  `weight_total` decimal(10,2) DEFAULT NULL,
  `warehouse_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  CONSTRAINT `products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES ('P001','ข้าวสารหอมมะลิ 5 กก.','CAT01',10,200,0,'5 กก.','ถุง',5.00,0.00,1,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P002','น้ำมันพืช 1 ลิตร','CAT01',20,300,0,'1 ลิตร','ขวด',0.92,0.00,1,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P003','น้ำดื่ม 600 มล.','CAT02',50,500,0,'600 มล.','แพ็ค 12',7.20,0.00,1,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P004','กระดาษ A4 80 แกรม','CAT03',5,100,0,'500 แผ่น','รีม',2.50,0.00,2,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P005','น้ำยาล้างจาน 3.8 ลิตร','CAT04',5,50,0,'3.8 ลิตร','แกลลอน',3.80,0.00,3,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P006','น้ำตาลทราย 1 กก.','CAT05',15,200,0,'1 กก.','ถุง',1.00,0.00,3,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P007','ปากกาลูกลื่น','CAT03',20,200,0,NULL,'โหล',0.15,0.00,2,'2026-03-08 03:12:07','2026-03-08 03:12:07'),('P008','แป้งสาลี 1 กก.','CAT05',10,150,0,'1 กก.','ถุง',1.00,0.00,3,'2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receive_types`
--

DROP TABLE IF EXISTS `receive_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receive_types` (
  `receive_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`receive_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receive_types`
--

LOCK TABLES `receive_types` WRITE;
/*!40000 ALTER TABLE `receive_types` DISABLE KEYS */;
INSERT INTO `receive_types` VALUES (1,'ซื้อ','2026-03-08 03:12:07','2026-03-08 03:12:07'),(2,'รับบริจาค','2026-03-08 03:12:07','2026-03-08 03:12:07'),(3,'โอนย้ายคลัง','2026-03-08 03:12:07','2026-03-08 03:12:07'),(4,'รับคืน','2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `receive_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `role_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `roles_role_name_unique` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','ผู้ดูแลระบบ','2026-03-08 03:12:05','2026-03-08 03:12:05'),(2,'staff','พนักงาน','2026-03-08 03:12:05','2026-03-08 03:12:05'),(3,'user','ผู้ใช้งานทั่วไป','2026-03-08 03:12:05','2026-03-08 03:12:05');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2njIUjPEpZYfiSOxKjoBS7vkVznorQQL6DAGxqo6',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 OPR/128.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOG1HbDlJemxVYVE2UzA5cW1XTHN6dGdwNEhJcmRXcDBqaDczelk0RCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrdXBzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1773843205),('uF0ck3jQVeKcohRGpohzojTZC6LKyeTTjGl3dZJN',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 OPR/128.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUUZDSXlWWFhsNTlha3NITlI4djhzWGoyUkd0dGdmTEtUNHdDZnhMMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdG9jay1vdXRzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1773764690);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_outs`
--

DROP TABLE IF EXISTS `stock_outs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_outs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` varchar(20) NOT NULL,
  `trans_id` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `fraction_qty` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `issued_to` varchar(150) NOT NULL,
  `issued_date` date NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_outs_product_id_foreign` (`product_id`),
  KEY `stock_outs_trans_id_foreign` (`trans_id`),
  KEY `stock_outs_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_outs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_outs_trans_id_foreign` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`trans_id`) ON DELETE SET NULL,
  CONSTRAINT `stock_outs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_outs`
--

LOCK TABLES `stock_outs` WRITE;
/*!40000 ALTER TABLE `stock_outs` DISABLE KEYS */;
INSERT INTO `stock_outs` VALUES (1,'P001','RCV-001',5,0,1,'สมชาย จันทร์ดี','2026-01-15','เบิกใช้ในโรงอาหาร','2026-03-08 03:12:07','2026-03-08 03:12:07'),(2,'P003','RCV-002',20,0,2,'สมหญิง แสงทอง','2026-01-25','เบิกน้ำดื่มสำหรับประชุม','2026-03-08 03:12:07','2026-03-08 03:12:07'),(3,'P004','RCV-002',5,0,2,'สมหญิง แสงทอง','2026-02-10','เบิกกระดาษสำนักงาน','2026-03-08 03:12:07','2026-03-08 03:12:07'),(4,'P005','RCV-003',3,0,1,'สมชาย จันทร์ดี','2026-02-20','เบิกน้ำยาล้างจานแม่บ้าน','2026-03-08 03:12:07','2026-03-08 03:12:07'),(5,'P007','RCV-004',10,0,2,'สมหญิง แสงทอง','2026-03-01','เบิกปากกาแผนกบัญชี','2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `stock_outs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_items`
--

DROP TABLE IF EXISTS `transaction_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trans_id` varchar(50) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `full_qty` int(11) NOT NULL DEFAULT 0,
  `fraction_qty` int(11) NOT NULL DEFAULT 0,
  `net_weight` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_trans_id_foreign` (`trans_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_items_trans_id_foreign` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`trans_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_items`
--

LOCK TABLES `transaction_items` WRITE;
/*!40000 ALTER TABLE `transaction_items` DISABLE KEYS */;
INSERT INTO `transaction_items` VALUES (1,'RCV-001','P001','LOT-001',45,0,250.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(2,'RCV-001','P002','LOT-002',100,0,92.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(3,'RCV-002','P003','LOT-003',180,5,1440.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(4,'RCV-002','P004','LOT-004',25,0,75.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(5,'RCV-003','P005','LOT-005',17,0,76.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(6,'RCV-003','P006','LOT-006',80,0,80.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(7,'RCV-004','P007','LOT-007',40,0,7.50,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(8,'RCV-004','P008','LOT-008',40,0,40.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(9,'RCV-004','P001','LOT-009',30,0,150.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(10,'RCV-005','P003','LOT-010',100,0,720.00,'2026-03-08 03:12:07','2026-03-08 03:12:07'),(11,'RCV-005','P002','LOT-011',50,0,46.00,'2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `transaction_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `trans_id` varchar(50) NOT NULL,
  `trans_date` date NOT NULL,
  `reference_doc` varchar(100) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `receive_type_id` bigint(20) unsigned DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `transactions_receive_type_id_foreign` (`receive_type_id`),
  CONSTRAINT `transactions_receive_type_id_foreign` FOREIGN KEY (`receive_type_id`) REFERENCES `receive_types` (`receive_type_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES ('RCV-001','2026-01-10','PO-2026-001','INV-001',1,'สั่งซื้อประจำเดือน ม.ค.','2026-03-08 03:12:07','2026-03-08 03:12:07'),('RCV-002','2026-01-20','PO-2026-002','INV-002',1,'สั่งซื้อเพิ่มเติม','2026-03-08 03:12:07','2026-03-08 03:12:07'),('RCV-003','2026-02-05','DN-2026-001',NULL,2,'รับบริจาคจากบริษัท ABC','2026-03-08 03:12:07','2026-03-08 03:12:07'),('RCV-004','2026-02-15','PO-2026-003','INV-003',1,'สั่งซื้อประจำเดือน ก.พ.','2026-03-08 03:12:07','2026-03-08 03:12:07'),('RCV-005','2026-03-01','TRF-001',NULL,3,'โอนย้ายจากคลังสำรอง','2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `receive_stock_alert` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'สมชาย','จันทร์ดี','bally0653531012@gmail.com','0812345678',1,1,'$2y$12$NqBNnyMwGvE1z3QzIWATpeZHSOQJwCBLxIKl1Ply/w/cYJp1WC926','0e4qavI2zRnVO4boBrizE8ixmstbIVCRTXugpcU8qKlM3hUbxhCqTCJtRyEd','2026-03-08 03:12:06','2026-03-17 08:43:48'),(2,'สมหญิง','แสงทอง','staff@example.com','0898765432',2,1,'$2y$12$hlChKLN1AH8/CdJyJC9JEO6dPWecw9YSRpQ9nkKQ6M4KNDdtMgK56',NULL,'2026-03-08 03:12:07','2026-03-17 08:43:48'),(3,'วิชัย','ใจดี','user@example.com','0856789012',3,1,'$2y$12$EQaZnLTVTCoKhh/K1bfb8OvkwbaqfLbPmpAsXQIJ28dB4eaNw/XNW',NULL,'2026-03-08 03:12:07','2026-03-17 08:43:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouses` (
  `warehouse_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`warehouse_id`),
  UNIQUE KEY `warehouses_warehouse_name_unique` (`warehouse_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (1,'คลังสินค้าหลัก','อาคาร A ชั้น 1','2026-03-08 03:12:07','2026-03-08 03:12:07'),(2,'คลังสินค้าสำรอง','อาคาร B ชั้น 2','2026-03-08 03:12:07','2026-03-08 03:12:07'),(3,'คลังวัตถุดิบ','อาคาร C ชั้น 1','2026-03-08 03:12:07','2026-03-08 03:12:07');
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-18 21:15:58
