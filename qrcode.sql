-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.43-0ubuntu0.22.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for barcode
CREATE DATABASE IF NOT EXISTS `barcode` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `barcode`;

-- Dumping structure for table barcode.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.cache: ~0 rows (approximately)

-- Dumping structure for table barcode.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.cache_locks: ~0 rows (approximately)

-- Dumping structure for table barcode.content_sections
CREATE TABLE IF NOT EXISTS `content_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` json NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_sections_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.content_sections: ~7 rows (approximately)
INSERT INTO `content_sections` (`id`, `name`, `slug`, `content`, `published`, `created_at`, `updated_at`) VALUES
	(1, 'Manufacturing', 'manufacturing', '{"title": "Production & Manufacturing Process", "blocks": [{"kind": "image", "name": "gear"}, {"kind": "rich", "heading": "Production Process", "paragraphs": ["We are committed to long-term supplier relations based on trust and transparency.", "Our products pass through many suppliers from raw materials to finished goods.", "We do not own factories; we work with independent manufacturers."]}, {"kind": "rich", "heading": "Environmental Considerations", "paragraphs": ["Water, energy and chemicals are managed to recognized standards (e.g., ZDHC). Continuous improvement programs and audits help reduce the footprint."]}, {"kind": "rich", "heading": "Worker Wellbeing & Audits", "paragraphs": ["Facilities are assessed against the Code of Conduct; corrective action plans are monitored to drive performance and positive impact."]}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(2, 'Materials', 'materials', '{"title": "Key Materials & Components Used", "blocks": [{"kind": "rich", "heading": "Made From", "paragraphs": ["Made from 100% organic cotton (BCI) and contains at least 20% recycled cotton."]}, {"kind": "list", "items": [{"rows": [{"key": "ID", "value": "60977"}, {"key": "PERCENTAGE", "value": "80%"}, {"key": "BY VOLUME", "value": "65 GMS"}, {"key": "RECYCLABLE", "value": true}, {"key": "SUPPLIER", "value": "Diganta Sweaters Ltd, Bangladesh"}], "group": "Organic Cotton (BCI)"}, {"rows": [{"key": "ID", "value": "60974"}, {"key": "PERCENTAGE", "value": "20%"}, {"key": "BY VOLUME", "value": "35 GMS"}, {"key": "RECYCLABLE", "value": true}, {"key": "SUPPLIER", "value": "Indigo Fabrics Pvt. Ltd., Bangladesh"}], "group": "Recycled Cotton"}], "title": "Key Materials & Component List"}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(3, 'Custody', 'custody', '{"title": "Chain of Custody & Traceability", "blocks": [{"kind": "rich", "heading": "Item Traceability Map", "paragraphs": ["placeholder"]}, {"kind": "eventLog", "title": "Item Track & Trace Event Log", "events": [{"at": "2025-03-14T12:43:00Z", "lat": 23.256832, "lng": 91.7318, "actor": "VECTOR_GARMENT", "status": "SHIPPED"}, {"at": "2025-03-13T17:15:00Z", "lat": 23.810331, "lng": 90.412521, "actor": "RED_EXPRESS", "status": "SHIPPED"}, {"at": null, "lat": null, "lng": null, "actor": "ESPREQ_RETAIL", "status": "RECEIVED"}]}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(4, 'Usage', 'usage', '{"title": "Use, Care, Reuse & Recycle", "blocks": [{"kind": "image", "name": "recycle"}, {"kind": "rich", "heading": "Usage & Care Guidelines", "paragraphs": ["Follow label instructions. Reshape chunky knits and dry flat. Reduce washing frequency and temperature to lower impact."]}, {"kind": "rich", "heading": "End of Life & Recycling", "paragraphs": ["Bring unwanted clothes to our collecting boxes (any brand). Wearable items are resold; others become new products or cleaning cloths."]}, {"kind": "rich", "heading": "Close the Loop", "paragraphs": ["Our Garment Collecting program has run since 2013, with recycling boxes in stores worldwide."]}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(5, 'Certifications', 'certs', '{"title": "Certifications", "blocks": [{"kind": "image", "name": "medal"}, {"kind": "rich", "heading": "Compliance Statement", "paragraphs": ["We increase transparency and pioneer new materials and business models toward net-zero (2040)."]}, {"kind": "cardGrid", "cards": [{"href": "#", "text": "Collection supports circularity; products are fully compostable.", "title": "Cradle to Cradle Certified® Gold"}], "title": "Programs"}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(6, 'Sustainability', 'sustain', '{"title": "Sustainability", "blocks": [{"kind": "image", "name": "leaf"}, {"kind": "rich", "heading": "Our Commitment", "paragraphs": ["We collaborate with partners globally to meet high environmental and social standards."]}, {"kind": "rich", "heading": "Manufacturing & Supply Chain", "paragraphs": ["Long-term relationships with independent manufacturers; supplier list published and updated regularly."]}, {"kind": "rich", "heading": "Textile Materials", "paragraphs": ["Eco-friendly fibers like organic cotton; lower-impact processes."]}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(7, 'Environmental Impact', 'impact', '{"title": "Environmental Impact", "blocks": [{"kind": "image", "name": "earth"}, {"kind": "rich", "heading": "Statement", "paragraphs": ["We aim for net-zero emissions by 2040, keeping products and materials in use for as long as possible."]}, {"kind": "metricsGrid", "cards": [{"note": "cradle-to-gate", "label": "CO₂e", "value": "12.4 kg"}, {"note": "freshwater use", "label": "Water", "value": "980 L"}, {"note": "process energy", "label": "Energy", "value": "18.2 kWh"}]}]}', 1, '2025-09-16 02:54:09', '2025-09-16 02:54:09');

-- Dumping structure for table barcode.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table barcode.gtin_mappings
CREATE TABLE IF NOT EXISTS `gtin_mappings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `product_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `season` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gtin14` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gtin16` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `qr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gtin_mappings_product_id_foreign` (`product_id`),
  KEY `gtin_mappings_gtin14_index` (`gtin14`),
  KEY `gtin_mappings_gtin16_index` (`gtin16`),
  CONSTRAINT `gtin_mappings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.gtin_mappings: ~11 rows (approximately)
INSERT INTO `gtin_mappings` (`id`, `product_id`, `product_no`, `order_no`, `season`, `color_code`, `size_code`, `gtin14`, `gtin16`, `quantity`, `qr_path`, `barcode_path`, `created_at`, `updated_at`) VALUES
	(1, 1, '32343434', 'ORD000123', 'SS25', 'C001', 'S12', '07300235375212', '07300235375212000006', 1, 'http://localhost:8000/qr/07300235375212HM000006.png', '/barcodes/07300235375212.png', '2025-09-16 02:54:09', '2025-09-16 02:54:09'),
	(2, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213401', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213401.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(3, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213402', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213402.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(4, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213403', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213403.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(5, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213404', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213404.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(6, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213405', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213405.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(7, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213406', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213406.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(8, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213407', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213407.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(9, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213408', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213408.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(10, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213409', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213409.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41'),
	(11, 1, '32343434', '470996-5787', '3-2026', '002', '1', '32343434002134', '3234343400213410', 10, 'http://localhost:8000/storage/qrcodes/qr_3234343400213410.png', NULL, '2025-09-16 02:55:41', '2025-09-16 02:55:41');

-- Dumping structure for table barcode.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.jobs: ~0 rows (approximately)

-- Dumping structure for table barcode.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.job_batches: ~0 rows (approximately)

-- Dumping structure for table barcode.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.migrations: ~14 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(42, '0001_01_01_000000_create_users_table', 1),
	(43, '0001_01_01_000001_create_cache_table', 1),
	(44, '0001_01_01_000002_create_jobs_table', 1),
	(45, '2025_09_08_053721_create_oauth_auth_codes_table', 1),
	(46, '2025_09_08_053722_create_oauth_access_tokens_table', 1),
	(47, '2025_09_08_053723_create_oauth_refresh_tokens_table', 1),
	(48, '2025_09_08_053724_create_oauth_clients_table', 1),
	(49, '2025_09_08_053725_create_oauth_device_codes_table', 1),
	(50, '2025_09_08_060042_create_personal_access_tokens_table', 1),
	(51, '2025_09_08_065632_create_products_table', 1),
	(52, '2025_09_09_033627_create_permission_tables', 1),
	(53, '2025_09_09_040947_add_role_to_users_table', 1),
	(54, '2025_09_09_071550_create_gtin_mappings_table', 1),
	(55, '2025_09_16_064100_create_content_sections_table', 1);

-- Dumping structure for table barcode.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table barcode.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.model_has_roles: ~0 rows (approximately)

-- Dumping structure for table barcode.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.oauth_access_tokens: ~0 rows (approximately)

-- Dumping structure for table barcode.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.oauth_auth_codes: ~0 rows (approximately)

-- Dumping structure for table barcode.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_uris` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `grant_types` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_owner_type_owner_id_index` (`owner_type`,`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.oauth_clients: ~0 rows (approximately)

-- Dumping structure for table barcode.oauth_device_codes
CREATE TABLE IF NOT EXISTS `oauth_device_codes` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_code` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `user_approved_at` datetime DEFAULT NULL,
  `last_polled_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_device_codes_user_code_unique` (`user_code`),
  KEY `oauth_device_codes_user_id_index` (`user_id`),
  KEY `oauth_device_codes_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.oauth_device_codes: ~0 rows (approximately)

-- Dumping structure for table barcode.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.oauth_refresh_tokens: ~0 rows (approximately)

-- Dumping structure for table barcode.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table barcode.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.permissions: ~0 rows (approximately)

-- Dumping structure for table barcode.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table barcode.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `season` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `construction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gtin14` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_base_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `factory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufactured_on` date DEFAULT NULL,
  `packaged_on` date DEFAULT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_updated_at_pretty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_hero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gear_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_process` longtext COLLATE utf8mb4_unicode_ci,
  `environmental_considerations` longtext COLLATE utf8mb4_unicode_ci,
  `worker_wellbeing` longtext COLLATE utf8mb4_unicode_ci,
  `identity_json` json DEFAULT NULL,
  `manufacturing_json` json DEFAULT NULL,
  `materials_json` json DEFAULT NULL,
  `custody_json` json DEFAULT NULL,
  `usage_json` json DEFAULT NULL,
  `certs_json` json DEFAULT NULL,
  `sustain_json` json DEFAULT NULL,
  `impact_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_product_no_unique` (`product_no`),
  KEY `products_season_customer_group_index` (`season`,`customer_group`),
  KEY `products_construction_type_index` (`construction_type`),
  KEY `products_sku_code_index` (`sku_code`),
  KEY `products_gtin14_index` (`gtin14`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.products: ~1 rows (approximately)
INSERT INTO `products` (`id`, `product_no`, `name`, `description`, `season`, `customer_group`, `construction_type`, `brand`, `model`, `sku_code`, `barcode`, `gtin14`, `qr_base_url`, `qr_url`, `shop_url`, `supplier_code`, `supplier_name`, `factory`, `address`, `origin`, `batch`, `manufactured_on`, `packaged_on`, `publisher`, `info_updated_at_pretty`, `image`, `image_logo`, `image_hero`, `image_footer`, `gear_image_url`, `production_process`, `environmental_considerations`, `worker_wellbeing`, `identity_json`, `manufacturing_json`, `materials_json`, `custody_json`, `usage_json`, `certs_json`, `sustain_json`, `impact_json`, `created_at`, `updated_at`) VALUES
	(1, '32343434', 'CONNERY Baby Woven Trousers', '100% Organic Cotton Flat Woven Trousers for Babies inspired by Work Wear', 'SS25', 'Baby', 'Flat Woven', 'H&M Group', 'HM0004588CX000006', '0004588C-CONNERY', '88768333888282', '07300235375212', 'https://qr.hmgroup.com/01', 'https://qr.hmgroup.com/01/07300235375212/21/HM0004588CX000006', 'https://hm.com/product/88768333888282', 'SUP001', 'Russell Garments SIM Fabrics Ltd.', 'Manufactured for H & M Hennes & Mauritz GBC AB by Russell Garments SIM Fabrics Ltd.', '315, Road - 4, Baridhara DOHS, Dhaka, Bangladesh - 1206', 'Made in Bangladesh', '4588-3-2025', '2025-04-08', '2025-04-09', 'H&M Group', '08/04/2025 - 04:07:45', 'https://cdn.qliktag.io/production/63ee03119cde45000847eb1c/HM%20Baby%20Trousers.png', '/hm-logo.svg', 'https://cdn.qliktag.io/production/63ee03119cde45000847eb1c/HM%20Baby%20Trousers.png', '/images/brand-footer.jpg', 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Industrial_robot_gear_icon.svg/640px-Industrial_robot_gear_icon.svg.png', 'We are committed to long-term supplier relations based on trust and transparency...', 'Water, energy and chemicals are managed to recognized standards (e.g., ZDHC)...', 'Facilities are assessed against the Code of Conduct; corrective action plans...', '{"brand": "H&M Group", "model": "HM0004588CX000006", "gtin14": "07300235375212", "qr_url": "https://qr.hmgroup.com/01/07300235375212/21/HM0004588CX000006", "barcode": "88768333888282", "shop_url": "https://hm.com/product/88768333888282", "sku_code": "0004588C-CONNERY", "qr_base_url": "https://qr.hmgroup.com/01"}', '{"batch": "4588-3-2025", "origin": "Made in Bangladesh", "address": "315, Road - 4, Baridhara DOHS, Dhaka, Bangladesh - 1206", "factory": "Manufactured for H & M Hennes & Mauritz GBC AB by Russell Garments SIM Fabrics Ltd.", "publisher": "H&M Group", "packaged_on": "2025-04-09", "supplier_code": "SUP001", "supplier_name": "Russell Garments SIM Fabrics Ltd.", "manufactured_on": "2025-04-08", "info_updated_at_pretty": "08/04/2025 - 04:07:45"}', '{"items": [{"id": "60977", "title": "Organic Cotton (BCI)", "supplier": "Diganta Sweaters Ltd", "percentage": 80, "recyclable": true, "by_volume_g": 65}, {"id": "60974", "title": "Recycled Cotton", "supplier": "Indigo Fabrics Pvt. Ltd.", "percentage": 20, "recyclable": true, "by_volume_g": 35}], "made_from": "100% organic cotton (BCI) incl. 20% recycled."}', '{"events": [{"lat": 23.256832, "lng": 91.7318, "org": "VECTOR_GARMENT", "when": "2025-03-14T12:43:00Z", "status": "SHIPPED"}, {"lat": 23.810331, "lng": 90.412521, "org": "RED_EXPRESS", "when": "2025-03-13T17:15:00Z", "status": "SHIPPED"}, {"lat": null, "lng": null, "org": "ESPREQ_RETAIL", "when": null, "status": "RECEIVED"}], "serial": "HM0004588CX000006"}', '{"close_loop": "Garment Collecting program since 2013.", "guidelines": "Follow label. Wash cooler & less often.", "end_of_life": "Bring to collecting boxes; recycle/upcycle.", "recycle_image_url": "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Recycling_symbol2.svg/512px-Recycling_symbol2.svg.png"}', '[{"link": "https://hm.com/product/88768333888282", "title": "Cradle to Cradle Certified® Gold", "summary": "Supports circularity; fully compostable."}]', '{"commitment": "High environmental and social standards.", "supply_chain": "Independent manufacturers; transparent list.", "leaf_image_url": "https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Leaf_icon_%28The_Noun_Project%29.svg/512px-Leaf_icon_%28The_Noun_Project%29.svg.png", "textile_materials": "Eco-friendly fibers like organic cotton."}', '{"co2e_kg": 12.4, "water_l": 980, "energy_kwh": 18.2, "earth_image_url": "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/CO2_footprint_icon.svg/512px-CO2_footprint_icon.svg.png"}', '2025-09-16 02:54:09', '2025-09-16 02:54:09');

-- Dumping structure for table barcode.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.roles: ~0 rows (approximately)

-- Dumping structure for table barcode.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.role_has_permissions: ~0 rows (approximately)

-- Dumping structure for table barcode.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.sessions: ~2 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('cWOrUCXE0Zb8Owd88QrnvWo2Qagq2f4cvQJscRZ4', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSVJKVnpZa2xoNGZ1NTR0dzB6VGhzdnRBTWJ3MFFYUHRFUjlITDhNeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYXJjb2RlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1758012941),
	('z5oJosw74xf2C9mfeHkUfLIHcsxli8fRUjOVGHyU', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMDJIbnQ5bElndnBDRTBac2V4T3lmRHdHaERBZ01WdmNCOW95UEc5MyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2JhcmNvZGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1758021225);

-- Dumping structure for table barcode.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table barcode.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
	(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$OhsQ3.NLXfhkiL0L9BGcTuboGAIr.S6f6C2d8hpRwCvHM9cPpL0cq', NULL, '2025-09-16 02:54:09', '2025-09-16 02:54:09', 'admin'),
	(2, 'Normal User', 'user@example.com', NULL, '$2y$12$yLN3/gbcVp5oIjaF2/nC/.MZRutlndaCgC.k37SxkcY0c0u4.VITq', NULL, '2025-09-16 02:54:09', '2025-09-16 02:54:09', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
