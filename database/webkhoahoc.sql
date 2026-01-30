-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: webkhoahoc
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_id_course_id_unique` (`user_id`,`course_id`),
  KEY `carts_course_id_foreign` (`course_id`),
  CONSTRAINT `carts_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Lập trình Web','lap-trinh-web','Các khóa học về phát triển web',NULL,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(2,'Lập trình Mobile','lap-trinh-mobile','Các khóa học về phát triển ứng dụng di động',NULL,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(3,'Data Science','data-science','Khoa học dữ liệu và Machine Learning',NULL,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(4,'DevOps','devops','CI/CD, Docker, Kubernetes',NULL,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(5,'UI/UX Design','uiux-design','Thiết kế giao diện người dùng',NULL,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(6,'Kinh doanh','kinh-doanh','Các khóa học về kinh doanh và marketing',NULL,'active','2026-01-21 19:13:35','2026-01-21 19:13:35');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_user`
--

DROP TABLE IF EXISTS `course_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `progress` int(11) NOT NULL DEFAULT 0 COMMENT 'Progress percentage',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_user_user_id_course_id_unique` (`user_id`,`course_id`),
  KEY `course_user_course_id_foreign` (`course_id`),
  CONSTRAINT `course_user_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_user`
--

LOCK TABLES `course_user` WRITE;
/*!40000 ALTER TABLE `course_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT 'Total duration in minutes',
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_slug_unique` (`slug`),
  KEY `courses_category_id_foreign` (`category_id`),
  KEY `courses_instructor_id_foreign` (`instructor_id`),
  CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,1,2,'Laravel từ cơ bản đến nâng cao','laravel-tu-co-ban-den-nang-cao',NULL,1500000.00,990000.00,'Học Laravel framework một cách toàn diện, từ các khái niệm cơ bản đến nâng cao.','Nội dung chi tiết của khóa học Laravel từ cơ bản đến nâng cao. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.','beginner',0,'published',0,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(2,1,3,'ReactJS cho người mới bắt đầu','reactjs-cho-nguoi-moi-bat-dau',NULL,1200000.00,NULL,'Xây dựng ứng dụng web hiện đại với ReactJS.','Nội dung chi tiết của khóa học ReactJS cho người mới bắt đầu. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.','beginner',0,'published',0,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(3,2,4,'Flutter Mobile App Development','flutter-mobile-app-development',NULL,1800000.00,1290000.00,'Phát triển ứng dụng mobile đa nền tảng với Flutter.','Nội dung chi tiết của khóa học Flutter Mobile App Development. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.','beginner',0,'published',0,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(4,3,2,'Machine Learning cơ bản','machine-learning-co-ban',NULL,2000000.00,1500000.00,'Nhập môn Machine Learning với Python.','Nội dung chi tiết của khóa học Machine Learning cơ bản. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.','beginner',0,'published',0,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(5,4,3,'Docker & Kubernetes thực chiến','docker-kubernetes-thuc-chien',NULL,1600000.00,NULL,'Containerization và orchestration cho ứng dụng.','Nội dung chi tiết của khóa học Docker & Kubernetes thực chiến. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.','beginner',0,'published',0,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(6,5,4,'Figma UI/UX Design','figma-uiux-design',NULL,800000.00,590000.00,'Thiết kế giao diện chuyên nghiệp với Figma.','Nội dung chi tiết của khóa học Figma UI/UX Design. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.','beginner',0,'published',0,'2026-01-21 19:13:35','2026-01-21 19:13:35');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
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
-- Table structure for table `lesson_progress`
--

DROP TABLE IF EXISTS `lesson_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_progress` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `lesson_id` bigint(20) unsigned NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  KEY `lesson_progress_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_progress`
--

LOCK TABLES `lesson_progress` WRITE;
/*!40000 ALTER TABLE `lesson_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lessons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_type` varchar(255) NOT NULL DEFAULT 'youtube' COMMENT 'youtube, vimeo, upload',
  `content` longtext DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT 'Duration in minutes',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_preview` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lessons_course_id_slug_unique` (`course_id`,`slug`),
  CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES (1,1,'Giới thiệu về Laravel','gioi-thieu-ve-laravel-1','https://www.youtube.com/watch?v=MFh0Fd7BsjE','youtube','Xem video để học Giới thiệu về Laravel',15,1,1,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(2,1,'Cài đặt môi trường Laravel','cai-dat-moi-truong-laravel-2','https://www.youtube.com/watch?v=BXiHvgrJfkg','youtube','Xem video để học Cài đặt môi trường Laravel',20,2,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(3,1,'Routing trong Laravel','routing-trong-laravel-3',NULL,'text','# Routing trong Laravel\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',10,3,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(4,1,'Controllers và Views','controllers-va-views-4','https://www.youtube.com/watch?v=gUEZwdnzL4Q','youtube','Xem video để học Controllers và Views',25,4,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(5,1,'Database Migrations','database-migrations-5',NULL,'text','# Database Migrations\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',15,5,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(6,1,'Eloquent ORM','eloquent-orm-6','https://www.youtube.com/watch?v=ImtZ5yENzgE','youtube','Xem video để học Eloquent ORM',30,6,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(7,1,'Blade Templates','blade-templates-7',NULL,'text','# Blade Templates\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',12,7,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(8,1,'Xây dựng dự án thực tế','xay-dung-du-an-thuc-te-8','https://www.youtube.com/watch?v=MYyJ4PuL4pY','youtube','Xem video để học Xây dựng dự án thực tế',45,8,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(9,2,'React là gì?','react-la-gi-1','https://www.youtube.com/watch?v=Tn6-PIqc4UM','youtube','Xem video để học React là gì?',12,1,1,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(10,2,'Cài đặt React với Create React App','cai-dat-react-voi-create-react-app-2','https://www.youtube.com/watch?v=w7ejDZ8SWv8','youtube','Xem video để học Cài đặt React với Create React App',15,2,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(11,2,'JSX và Components','jsx-va-components-3',NULL,'text','# JSX và Components\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',10,3,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(12,2,'Props và State','props-va-state-4','https://www.youtube.com/watch?v=IYvD9oBCuJI','youtube','Xem video để học Props và State',20,4,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(13,2,'Hooks trong React','hooks-trong-react-5',NULL,'text','# Hooks trong React\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',18,5,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(14,2,'React Router','react-router-6','https://www.youtube.com/watch?v=Law7wfdg_ls','youtube','Xem video để học React Router',22,6,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(15,2,'Context API','context-api-7',NULL,'text','# Context API\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',15,7,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(16,2,'Dự án Todo App','du-an-todo-app-8','https://www.youtube.com/watch?v=pCA4qpQDZD8','youtube','Xem video để học Dự án Todo App',40,8,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(17,3,'Giới thiệu Flutter','gioi-thieu-flutter-1','https://www.youtube.com/watch?v=1ukSR1GRtMU','youtube','Xem video để học Giới thiệu Flutter',15,1,1,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(18,3,'Cài đặt Flutter SDK','cai-dat-flutter-sdk-2','https://www.youtube.com/watch?v=fmPmrJGbb6w','youtube','Xem video để học Cài đặt Flutter SDK',18,2,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(19,3,'Dart Programming cơ bản','dart-programming-co-ban-3',NULL,'text','# Dart Programming cơ bản\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',20,3,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(20,3,'Widgets trong Flutter','widgets-trong-flutter-4','https://www.youtube.com/watch?v=C5lpPjoivaw','youtube','Xem video để học Widgets trong Flutter',25,4,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(21,3,'Layout và Navigation','layout-va-navigation-5',NULL,'text','# Layout và Navigation\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',15,5,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(22,3,'State Management với Provider','state-management-voi-provider-6','https://www.youtube.com/watch?v=d_m5csmrf7I','youtube','Xem video để học State Management với Provider',30,6,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(23,3,'Kết nối API','ket-noi-api-7',NULL,'text','# Kết nối API\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',20,7,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(24,3,'Build ứng dụng hoàn chỉnh','build-ung-dung-hoan-chinh-8','https://www.youtube.com/watch?v=wLqlLSvF3Lo','youtube','Xem video để học Build ứng dụng hoàn chỉnh',50,8,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(25,4,'Machine Learning là gì?','machine-learning-la-gi-1','https://www.youtube.com/watch?v=ukzFI9rgwfU','youtube','Xem video để học Machine Learning là gì?',18,1,1,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(26,4,'Python cho Machine Learning','python-cho-machine-learning-2',NULL,'text','# Python cho Machine Learning\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',15,2,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(27,4,'NumPy và Pandas','numpy-va-pandas-3','https://www.youtube.com/watch?v=vmEHCJofslg','youtube','Xem video để học NumPy và Pandas',25,3,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(28,4,'Linear Regression','linear-regression-4',NULL,'text','# Linear Regression\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',20,4,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(29,4,'Classification Algorithms','classification-algorithms-5','https://www.youtube.com/watch?v=vsWrXfO3wWw','youtube','Xem video để học Classification Algorithms',30,5,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(30,4,'Neural Networks','neural-networks-6',NULL,'text','# Neural Networks\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',25,6,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(31,4,'Deep Learning với TensorFlow','deep-learning-voi-tensorflow-7','https://www.youtube.com/watch?v=tPYj3fFJGjk','youtube','Xem video để học Deep Learning với TensorFlow',35,7,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(32,4,'Dự án dự đoán giá nhà','du-an-du-doan-gia-nha-8','https://www.youtube.com/watch?v=Wqmtf9SA_kk','youtube','Xem video để học Dự án dự đoán giá nhà',45,8,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(33,5,'Container hóa là gì?','container-hoa-la-gi-1','https://www.youtube.com/watch?v=rOTqprHv1YE','youtube','Xem video để học Container hóa là gì?',15,1,1,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(34,5,'Cài đặt Docker','cai-dat-docker-2','https://www.youtube.com/watch?v=gAkwW2tuIqE','youtube','Xem video để học Cài đặt Docker',12,2,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(35,5,'Docker Images và Containers','docker-images-va-containers-3',NULL,'text','# Docker Images và Containers\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',18,3,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(36,5,'Dockerfile','dockerfile-4','https://www.youtube.com/watch?v=WmcdMiyqfZs','youtube','Xem video để học Dockerfile',22,4,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(37,5,'Docker Compose','docker-compose-5',NULL,'text','# Docker Compose\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',20,5,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(38,5,'Giới thiệu Kubernetes','gioi-thieu-kubernetes-6','https://www.youtube.com/watch?v=X48VuDVv0do','youtube','Xem video để học Giới thiệu Kubernetes',25,6,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(39,5,'Deploy ứng dụng lên K8s','deploy-ung-dung-len-k8s-7',NULL,'text','# Deploy ứng dụng lên K8s\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',30,7,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(40,5,'CI/CD Pipeline','cicd-pipeline-8','https://www.youtube.com/watch?v=scEDHsr3APg','youtube','Xem video để học CI/CD Pipeline',40,8,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(41,6,'Giới thiệu Figma','gioi-thieu-figma-1','https://www.youtube.com/watch?v=FTFaQWZBqQ8','youtube','Xem video để học Giới thiệu Figma',12,1,1,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(42,6,'Interface cơ bản','interface-co-ban-2',NULL,'text','# Interface cơ bản\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',10,2,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(43,6,'Làm việc với Shapes','lam-viec-voi-shapes-3','https://www.youtube.com/watch?v=Cx2dkpBxst8','youtube','Xem video để học Làm việc với Shapes',20,3,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(44,6,'Typography và Colors','typography-va-colors-4',NULL,'text','# Typography và Colors\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',15,4,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(45,6,'Components và Variants','components-va-variants-5','https://www.youtube.com/watch?v=k8NpeRMYN_Y','youtube','Xem video để học Components và Variants',25,5,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(46,6,'Auto Layout','auto-layout-6',NULL,'text','# Auto Layout\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.',18,6,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(47,6,'Prototyping','prototyping-7','https://www.youtube.com/watch?v=iBkXf6u8_M4','youtube','Xem video để học Prototyping',22,7,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35'),(48,6,'Thiết kế Mobile App','thiet-ke-mobile-app-8','https://www.youtube.com/watch?v=PeGfX7W1mJk','youtube','Xem video để học Thiết kế Mobile App',50,8,0,'active','2026-01-21 19:13:35','2026-01-21 19:13:35');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
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
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_update_users_table',1),(5,'2024_01_01_000002_create_categories_table',1),(6,'2024_01_01_000003_create_courses_table',1),(7,'2024_01_01_000004_create_lessons_table',1),(8,'2024_01_01_000005_create_course_user_table',1),(9,'2024_01_01_000006_create_lesson_progress_table',1),(10,'2024_01_01_000007_create_carts_table',1),(11,'2024_01_01_000008_create_orders_table',1),(12,'2024_01_01_000009_create_order_items_table',1),(13,'2024_01_01_000010_create_reviews_table',1),(14,'2026_01_22_020756_create_quiz_questions_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_course_id_foreign` (`course_id`),
  CONSTRAINT `order_items_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cod','bank_transfer','momo','vnpay') NOT NULL DEFAULT 'cod',
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_questions`
--

DROP TABLE IF EXISTS `quiz_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` bigint(20) unsigned NOT NULL,
  `question` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_answer` enum('a','b','c','d') NOT NULL,
  `explanation` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_questions_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `quiz_questions_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_questions`
--

LOCK TABLES `quiz_questions` WRITE;
/*!40000 ALTER TABLE `quiz_questions` DISABLE KEYS */;
INSERT INTO `quiz_questions` VALUES (1,1,'Khái niệm chính trong bài \'Giới thiệu về Laravel\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(2,1,'Trong Giới thiệu về Laravel, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(3,1,'Ứng dụng thực tế của Giới thiệu về Laravel là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(4,2,'Khái niệm chính trong bài \'Cài đặt môi trường Laravel\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(5,2,'Trong Cài đặt môi trường Laravel, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(6,2,'Ứng dụng thực tế của Cài đặt môi trường Laravel là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(7,3,'Khái niệm chính trong bài \'Routing trong Laravel\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(8,3,'Trong Routing trong Laravel, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(9,3,'Ứng dụng thực tế của Routing trong Laravel là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(10,4,'Khái niệm chính trong bài \'Controllers và Views\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(11,4,'Trong Controllers và Views, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(12,4,'Ứng dụng thực tế của Controllers và Views là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(13,5,'Khái niệm chính trong bài \'Database Migrations\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(14,5,'Trong Database Migrations, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(15,5,'Ứng dụng thực tế của Database Migrations là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(16,6,'Khái niệm chính trong bài \'Eloquent ORM\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(17,6,'Trong Eloquent ORM, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(18,6,'Ứng dụng thực tế của Eloquent ORM là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(19,7,'Khái niệm chính trong bài \'Blade Templates\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(20,7,'Trong Blade Templates, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(21,7,'Ứng dụng thực tế của Blade Templates là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(22,8,'Khái niệm chính trong bài \'Xây dựng dự án thực tế\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(23,8,'Trong Xây dựng dự án thực tế, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(24,8,'Ứng dụng thực tế của Xây dựng dự án thực tế là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(25,9,'Khái niệm chính trong bài \'React là gì?\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(26,9,'Trong React là gì?, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(27,9,'Ứng dụng thực tế của React là gì? là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(28,10,'Khái niệm chính trong bài \'Cài đặt React với Create React App\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(29,10,'Trong Cài đặt React với Create React App, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(30,10,'Ứng dụng thực tế của Cài đặt React với Create React App là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(31,11,'Khái niệm chính trong bài \'JSX và Components\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(32,11,'Trong JSX và Components, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(33,11,'Ứng dụng thực tế của JSX và Components là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(34,12,'Khái niệm chính trong bài \'Props và State\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(35,12,'Trong Props và State, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(36,12,'Ứng dụng thực tế của Props và State là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(37,13,'Khái niệm chính trong bài \'Hooks trong React\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(38,13,'Trong Hooks trong React, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(39,13,'Ứng dụng thực tế của Hooks trong React là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(40,14,'Khái niệm chính trong bài \'React Router\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(41,14,'Trong React Router, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(42,14,'Ứng dụng thực tế của React Router là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(43,15,'Khái niệm chính trong bài \'Context API\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(44,15,'Trong Context API, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(45,15,'Ứng dụng thực tế của Context API là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(46,16,'Khái niệm chính trong bài \'Dự án Todo App\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(47,16,'Trong Dự án Todo App, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(48,16,'Ứng dụng thực tế của Dự án Todo App là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(49,17,'Khái niệm chính trong bài \'Giới thiệu Flutter\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(50,17,'Trong Giới thiệu Flutter, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(51,17,'Ứng dụng thực tế của Giới thiệu Flutter là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(52,18,'Khái niệm chính trong bài \'Cài đặt Flutter SDK\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(53,18,'Trong Cài đặt Flutter SDK, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(54,18,'Ứng dụng thực tế của Cài đặt Flutter SDK là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(55,19,'Khái niệm chính trong bài \'Dart Programming cơ bản\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(56,19,'Trong Dart Programming cơ bản, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(57,19,'Ứng dụng thực tế của Dart Programming cơ bản là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(58,20,'Khái niệm chính trong bài \'Widgets trong Flutter\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(59,20,'Trong Widgets trong Flutter, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(60,20,'Ứng dụng thực tế của Widgets trong Flutter là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(61,21,'Khái niệm chính trong bài \'Layout và Navigation\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(62,21,'Trong Layout và Navigation, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(63,21,'Ứng dụng thực tế của Layout và Navigation là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(64,22,'Khái niệm chính trong bài \'State Management với Provider\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(65,22,'Trong State Management với Provider, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(66,22,'Ứng dụng thực tế của State Management với Provider là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(67,23,'Khái niệm chính trong bài \'Kết nối API\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(68,23,'Trong Kết nối API, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(69,23,'Ứng dụng thực tế của Kết nối API là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(70,24,'Khái niệm chính trong bài \'Build ứng dụng hoàn chỉnh\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(71,24,'Trong Build ứng dụng hoàn chỉnh, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(72,24,'Ứng dụng thực tế của Build ứng dụng hoàn chỉnh là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(73,25,'Khái niệm chính trong bài \'Machine Learning là gì?\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(74,25,'Trong Machine Learning là gì?, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(75,25,'Ứng dụng thực tế của Machine Learning là gì? là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(76,26,'Khái niệm chính trong bài \'Python cho Machine Learning\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(77,26,'Trong Python cho Machine Learning, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(78,26,'Ứng dụng thực tế của Python cho Machine Learning là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(79,27,'Khái niệm chính trong bài \'NumPy và Pandas\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(80,27,'Trong NumPy và Pandas, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(81,27,'Ứng dụng thực tế của NumPy và Pandas là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(82,28,'Khái niệm chính trong bài \'Linear Regression\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(83,28,'Trong Linear Regression, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(84,28,'Ứng dụng thực tế của Linear Regression là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(85,29,'Khái niệm chính trong bài \'Classification Algorithms\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(86,29,'Trong Classification Algorithms, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(87,29,'Ứng dụng thực tế của Classification Algorithms là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(88,30,'Khái niệm chính trong bài \'Neural Networks\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(89,30,'Trong Neural Networks, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(90,30,'Ứng dụng thực tế của Neural Networks là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(91,31,'Khái niệm chính trong bài \'Deep Learning với TensorFlow\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(92,31,'Trong Deep Learning với TensorFlow, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(93,31,'Ứng dụng thực tế của Deep Learning với TensorFlow là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(94,32,'Khái niệm chính trong bài \'Dự án dự đoán giá nhà\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(95,32,'Trong Dự án dự đoán giá nhà, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(96,32,'Ứng dụng thực tế của Dự án dự đoán giá nhà là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(97,33,'Khái niệm chính trong bài \'Container hóa là gì?\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(98,33,'Trong Container hóa là gì?, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(99,33,'Ứng dụng thực tế của Container hóa là gì? là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(100,34,'Khái niệm chính trong bài \'Cài đặt Docker\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(101,34,'Trong Cài đặt Docker, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(102,34,'Ứng dụng thực tế của Cài đặt Docker là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(103,35,'Khái niệm chính trong bài \'Docker Images và Containers\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(104,35,'Trong Docker Images và Containers, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(105,35,'Ứng dụng thực tế của Docker Images và Containers là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(106,36,'Khái niệm chính trong bài \'Dockerfile\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(107,36,'Trong Dockerfile, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(108,36,'Ứng dụng thực tế của Dockerfile là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(109,37,'Khái niệm chính trong bài \'Docker Compose\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(110,37,'Trong Docker Compose, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(111,37,'Ứng dụng thực tế của Docker Compose là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(112,38,'Khái niệm chính trong bài \'Giới thiệu Kubernetes\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(113,38,'Trong Giới thiệu Kubernetes, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(114,38,'Ứng dụng thực tế của Giới thiệu Kubernetes là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(115,39,'Khái niệm chính trong bài \'Deploy ứng dụng lên K8s\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(116,39,'Trong Deploy ứng dụng lên K8s, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(117,39,'Ứng dụng thực tế của Deploy ứng dụng lên K8s là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(118,40,'Khái niệm chính trong bài \'CI/CD Pipeline\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(119,40,'Trong CI/CD Pipeline, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(120,40,'Ứng dụng thực tế của CI/CD Pipeline là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(121,41,'Khái niệm chính trong bài \'Giới thiệu Figma\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(122,41,'Trong Giới thiệu Figma, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(123,41,'Ứng dụng thực tế của Giới thiệu Figma là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(124,42,'Khái niệm chính trong bài \'Interface cơ bản\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(125,42,'Trong Interface cơ bản, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(126,42,'Ứng dụng thực tế của Interface cơ bản là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(127,43,'Khái niệm chính trong bài \'Làm việc với Shapes\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(128,43,'Trong Làm việc với Shapes, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(129,43,'Ứng dụng thực tế của Làm việc với Shapes là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(130,44,'Khái niệm chính trong bài \'Typography và Colors\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(131,44,'Trong Typography và Colors, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(132,44,'Ứng dụng thực tế của Typography và Colors là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(133,45,'Khái niệm chính trong bài \'Components và Variants\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(134,45,'Trong Components và Variants, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(135,45,'Ứng dụng thực tế của Components và Variants là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(136,46,'Khái niệm chính trong bài \'Auto Layout\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(137,46,'Trong Auto Layout, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(138,46,'Ứng dụng thực tế của Auto Layout là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(139,47,'Khái niệm chính trong bài \'Prototyping\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(140,47,'Trong Prototyping, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(141,47,'Ứng dụng thực tế của Prototyping là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(142,48,'Khái niệm chính trong bài \'Thiết kế Mobile App\' là gì?','Khái niệm A - Đúng','Khái niệm B','Khái niệm C','Khái niệm D','a','Đây là khái niệm chính được đề cập trong bài học.',1,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(143,48,'Trong Thiết kế Mobile App, cách thực hành đúng nhất là?','Cách thực hành 1','Cách thực hành 2 - Đúng','Cách thực hành 3','Cách thực hành 4','b','Đây là cách thực hành được khuyến nghị trong bài học.',2,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(144,48,'Ứng dụng thực tế của Thiết kế Mobile App là?','Ứng dụng 1','Ứng dụng 2','Ứng dụng 3 - Đúng','Ứng dụng 4','c','Đây là ứng dụng thực tế quan trọng nhất của bài học.',3,'2026-01-21 19:13:35','2026-01-21 19:13:35');
/*!40000 ALTER TABLE `quiz_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_user_id_course_id_unique` (`user_id`,`course_id`),
  KEY `reviews_course_id_foreign` (`course_id`),
  CONSTRAINT `reviews_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','instructor','student') NOT NULL DEFAULT 'student',
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@example.com','admin',NULL,NULL,NULL,'active','2026-01-21 19:13:31','$2y$12$PiVJQeIz670I/0oDQqnJjOMDqNyp7r9eMy8NnDL1LZvFpo2ek6jL.',NULL,'2026-01-21 19:13:31','2026-01-21 19:13:31'),(2,'Nguyễn Văn A','instructor1@example.com','instructor',NULL,NULL,'Giảng viên có nhiều năm kinh nghiệm trong lĩnh vực giảng dạy.','active','2026-01-21 19:13:31','$2y$12$CYSAr.NNl5KA.zoPaftIYuXAhiPQSef8QnKiwHK5CHf5I6WS3IKNa',NULL,'2026-01-21 19:13:31','2026-01-21 19:13:31'),(3,'Trần Thị B','instructor2@example.com','instructor',NULL,NULL,'Giảng viên có nhiều năm kinh nghiệm trong lĩnh vực giảng dạy.','active','2026-01-21 19:13:32','$2y$12$iKvviR8a52ymQoMiQj4t9uVrS7c3lZ.MpC58Zg9T7swFwwvnZl1m2',NULL,'2026-01-21 19:13:32','2026-01-21 19:13:32'),(4,'Lê Văn C','instructor3@example.com','instructor',NULL,NULL,'Giảng viên có nhiều năm kinh nghiệm trong lĩnh vực giảng dạy.','active','2026-01-21 19:13:32','$2y$12$PBZZ60zma6nsH06CoJHZz.Y35S5IUM.a.CdcvgFTGCNHMM2HeGJ/y',NULL,'2026-01-21 19:13:32','2026-01-21 19:13:32'),(5,'Học viên 1','student1@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:33','$2y$12$4anikVRi9sXIL3ZfAAMaH.sRbyJ/AJMJ/FSX9OBJdfEMf5O4kL49S',NULL,'2026-01-21 19:13:33','2026-01-21 19:13:33'),(6,'Học viên 2','student2@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:33','$2y$12$cBe8sy1nRa/hxDRJuOvKVer8ojJYRaShdHJ8T8Sly0ONp5afxPH3C',NULL,'2026-01-21 19:13:33','2026-01-21 19:13:33'),(7,'Học viên 3','student3@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:33','$2y$12$.D5WPwBcd6gny7MY58paD.uaLzc2bCe0Fvn7jRnf68thhmjgTJC/K',NULL,'2026-01-21 19:13:33','2026-01-21 19:13:33'),(8,'Học viên 4','student4@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:34','$2y$12$5ACgi/hZDgRUu.niBZCwc.OyfO5z.kvsP08KTLVYzt./IddH6qQk6',NULL,'2026-01-21 19:13:34','2026-01-21 19:13:34'),(9,'Học viên 5','student5@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:34','$2y$12$Xg8Pq0owP1cesXdJjHHdLONGeK.KwrEO11qOl8Lc.1PBSCOuwpwai',NULL,'2026-01-21 19:13:34','2026-01-21 19:13:34'),(10,'Học viên 6','student6@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:34','$2y$12$OdHeoJV7q.p.NP8NwZTeweqKru7.hdIEXVvZNF9kyu3QcLVjbvGLO',NULL,'2026-01-21 19:13:34','2026-01-21 19:13:34'),(11,'Học viên 7','student7@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:34','$2y$12$MV5DoMbgGSOCQhQNA7DHnePzb.9Zrvoh1eD4HNWjimMBCJj60HXHK',NULL,'2026-01-21 19:13:34','2026-01-21 19:13:34'),(12,'Học viên 8','student8@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:35','$2y$12$ftr.QtNp3dTZzX5j35RQrOhEaQAoEveApNzvl/4C0IgcSBOBPKjqG',NULL,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(13,'Học viên 9','student9@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:35','$2y$12$aBpL3ai9DkPo/yMSowDg.eZsL2GbOkax6fAcOmRSY4LFu/G/sDXLy',NULL,'2026-01-21 19:13:35','2026-01-21 19:13:35'),(14,'Học viên 10','student10@example.com','student',NULL,NULL,NULL,'active','2026-01-21 19:13:35','$2y$12$WUQPYyRM.yN9kg0fvsteTuTPpffv8ml.p9VEEQCvugwrxQbeiDDR6',NULL,'2026-01-21 19:13:35','2026-01-21 19:13:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-22  9:13:52
