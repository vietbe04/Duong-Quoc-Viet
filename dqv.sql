-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 30, 2026 lúc 09:12 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dqv`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Lập trình Web', 'lap-trinh-web', 'Các khóa học về phát triển web', NULL, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(2, 'Lập trình Mobile', 'lap-trinh-mobile', 'Các khóa học về phát triển ứng dụng di động', NULL, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(3, 'Data Science', 'data-science', 'Khoa học dữ liệu và Machine Learning', NULL, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(4, 'DevOps', 'devops', 'CI/CD, Docker, Kubernetes', NULL, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(5, 'UI/UX Design', 'uiux-design', 'Thiết kế giao diện người dùng', NULL, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(6, 'Kinh doanh', 'kinh-doanh', 'Các khóa học về kinh doanh và marketing', NULL, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `instructor_id`, `title`, `slug`, `thumbnail`, `price`, `sale_price`, `short_description`, `content`, `level`, `duration`, `status`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Laravel từ cơ bản đến nâng cao', 'laravel-tu-co-ban-den-nang-cao', NULL, 1500000.00, 990000.00, 'Học Laravel framework một cách toàn diện, từ các khái niệm cơ bản đến nâng cao.', 'Nội dung chi tiết của khóa học Laravel từ cơ bản đến nâng cao. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.', 'beginner', 0, 'published', 0, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(3, 2, 4, 'Flutter Mobile App Development', 'flutter-mobile-app-development', NULL, 1800000.00, 1290000.00, 'Phát triển ứng dụng mobile đa nền tảng với Flutter.', 'Nội dung chi tiết của khóa học Flutter Mobile App Development. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.', 'beginner', 0, 'published', 0, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(4, 3, 2, 'Machine Learning cơ bản', 'machine-learning-co-ban', NULL, 2000000.00, 1500000.00, 'Nhập môn Machine Learning với Python.', 'Nội dung chi tiết của khóa học Machine Learning cơ bản. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.', 'beginner', 0, 'published', 0, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(6, 5, 4, 'Figma UI/UX Design', 'figma-uiux-design', NULL, 800000.00, 590000.00, 'Thiết kế giao diện chuyên nghiệp với Figma.', 'Nội dung chi tiết của khóa học Figma UI/UX Design. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.', 'beginner', 0, 'published', 0, '2026-01-21 19:13:35', '2026-01-21 19:13:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_user`
--

CREATE TABLE `course_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `progress` int(11) NOT NULL DEFAULT 0 COMMENT 'Progress percentage',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `course_user`
--

INSERT INTO `course_user` (`id`, `user_id`, `course_id`, `enrolled_at`, `progress`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2026-01-21 19:29:10', 0, NULL, '2026-01-21 19:29:10', '2026-01-21 19:29:10'),
(2, 2, 1, '2026-01-21 20:03:22', 0, NULL, '2026-01-21 20:03:22', '2026-01-21 20:03:22'),
(3, 1, 1, '2026-01-21 23:38:34', 0, NULL, '2026-01-21 23:38:34', '2026-01-21 23:38:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `slug`, `video_url`, `video_type`, `content`, `duration`, `order`, `is_preview`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Giới thiệu về Laravel', 'gioi-thieu-ve-laravel-1', 'https://www.youtube.com/watch?v=MFh0Fd7BsjE', 'youtube', 'Xem video để học Giới thiệu về Laravel', 15, 1, 1, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(2, 1, 'Cài đặt môi trường Laravel', 'cai-dat-moi-truong-laravel-2', 'https://www.youtube.com/watch?v=BXiHvgrJfkg', 'youtube', 'Xem video để học Cài đặt môi trường Laravel', 20, 2, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(3, 1, 'Routing trong Laravel', 'routing-trong-laravel-3', NULL, 'text', '# Routing trong Laravel\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 10, 3, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(4, 1, 'Controllers và Views', 'controllers-va-views-4', 'https://www.youtube.com/watch?v=gUEZwdnzL4Q', 'youtube', 'Xem video để học Controllers và Views', 25, 4, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(5, 1, 'Database Migrations', 'database-migrations-5', NULL, 'text', '# Database Migrations\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 15, 5, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(6, 1, 'Eloquent ORM', 'eloquent-orm-6', 'https://www.youtube.com/watch?v=ImtZ5yENzgE', 'youtube', 'Xem video để học Eloquent ORM', 30, 6, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(7, 1, 'Blade Templates', 'blade-templates-7', NULL, 'text', '# Blade Templates\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 12, 7, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(8, 1, 'Xây dựng dự án thực tế', 'xay-dung-du-an-thuc-te-8', 'https://www.youtube.com/watch?v=MYyJ4PuL4pY', 'youtube', 'Xem video để học Xây dựng dự án thực tế', 45, 8, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(17, 3, 'Giới thiệu Flutter', 'gioi-thieu-flutter-1', 'https://www.youtube.com/watch?v=1ukSR1GRtMU', 'youtube', 'Xem video để học Giới thiệu Flutter', 15, 1, 1, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(18, 3, 'Cài đặt Flutter SDK', 'cai-dat-flutter-sdk-2', 'https://www.youtube.com/watch?v=fmPmrJGbb6w', 'youtube', 'Xem video để học Cài đặt Flutter SDK', 18, 2, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(19, 3, 'Dart Programming cơ bản', 'dart-programming-co-ban-3', NULL, 'text', '# Dart Programming cơ bản\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 20, 3, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(20, 3, 'Widgets trong Flutter', 'widgets-trong-flutter-4', 'https://www.youtube.com/watch?v=C5lpPjoivaw', 'youtube', 'Xem video để học Widgets trong Flutter', 25, 4, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(21, 3, 'Layout và Navigation', 'layout-va-navigation-5', NULL, 'text', '# Layout và Navigation\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 15, 5, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(22, 3, 'State Management với Provider', 'state-management-voi-provider-6', 'https://www.youtube.com/watch?v=d_m5csmrf7I', 'youtube', 'Xem video để học State Management với Provider', 30, 6, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(23, 3, 'Kết nối API', 'ket-noi-api-7', NULL, 'text', '# Kết nối API\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 20, 7, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(24, 3, 'Build ứng dụng hoàn chỉnh', 'build-ung-dung-hoan-chinh-8', 'https://www.youtube.com/watch?v=wLqlLSvF3Lo', 'youtube', 'Xem video để học Build ứng dụng hoàn chỉnh', 50, 8, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(25, 4, 'Machine Learning là gì?', 'machine-learning-la-gi-1', 'https://www.youtube.com/watch?v=ukzFI9rgwfU', 'youtube', 'Xem video để học Machine Learning là gì?', 18, 1, 1, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(26, 4, 'Python cho Machine Learning', 'python-cho-machine-learning-2', NULL, 'text', '# Python cho Machine Learning\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 15, 2, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(27, 4, 'NumPy và Pandas', 'numpy-va-pandas-3', 'https://www.youtube.com/watch?v=vmEHCJofslg', 'youtube', 'Xem video để học NumPy và Pandas', 25, 3, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(28, 4, 'Linear Regression', 'linear-regression-4', NULL, 'text', '# Linear Regression\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 20, 4, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(29, 4, 'Classification Algorithms', 'classification-algorithms-5', 'https://www.youtube.com/watch?v=vsWrXfO3wWw', 'youtube', 'Xem video để học Classification Algorithms', 30, 5, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(30, 4, 'Neural Networks', 'neural-networks-6', NULL, 'text', '# Neural Networks\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 25, 6, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(31, 4, 'Deep Learning với TensorFlow', 'deep-learning-voi-tensorflow-7', 'https://www.youtube.com/watch?v=tPYj3fFJGjk', 'youtube', 'Xem video để học Deep Learning với TensorFlow', 35, 7, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(32, 4, 'Dự án dự đoán giá nhà', 'du-an-du-doan-gia-nha-8', 'https://www.youtube.com/watch?v=Wqmtf9SA_kk', 'youtube', 'Xem video để học Dự án dự đoán giá nhà', 45, 8, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(41, 6, 'Giới thiệu Figma', 'gioi-thieu-figma-1', 'https://www.youtube.com/watch?v=FTFaQWZBqQ8', 'youtube', 'Xem video để học Giới thiệu Figma', 12, 1, 1, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(42, 6, 'Interface cơ bản', 'interface-co-ban-2', NULL, 'text', '# Interface cơ bản\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 10, 2, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(43, 6, 'Làm việc với Shapes', 'lam-viec-voi-shapes-3', 'https://www.youtube.com/watch?v=Cx2dkpBxst8', 'youtube', 'Xem video để học Làm việc với Shapes', 20, 3, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(44, 6, 'Typography và Colors', 'typography-va-colors-4', NULL, 'text', '# Typography và Colors\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 15, 4, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(45, 6, 'Components và Variants', 'components-va-variants-5', 'https://www.youtube.com/watch?v=k8NpeRMYN_Y', 'youtube', 'Xem video để học Components và Variants', 25, 5, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(46, 6, 'Auto Layout', 'auto-layout-6', NULL, 'text', '# Auto Layout\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này.', 18, 6, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(47, 6, 'Prototyping', 'prototyping-7', 'https://www.youtube.com/watch?v=iBkXf6u8_M4', 'youtube', 'Xem video để học Prototyping', 22, 7, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(48, 6, 'Thiết kế Mobile App', 'thiet-ke-mobile-app-8', 'https://www.youtube.com/watch?v=PeGfX7W1mJk', 'youtube', 'Xem video để học Thiết kế Mobile App', 50, 8, 0, 'active', '2026-01-21 19:13:35', '2026-01-21 19:13:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lesson_progress`
--

INSERT INTO `lesson_progress` (`id`, `user_id`, `lesson_id`, `is_completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 0, NULL, '2026-01-21 20:19:07', '2026-01-21 20:20:35'),
(2, 2, 2, 1, '2026-01-21 20:19:20', '2026-01-21 20:19:20', '2026-01-21 20:19:20'),
(3, 1, 8, 1, '2026-01-21 23:39:39', '2026-01-21 23:39:39', '2026-01-21 23:39:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_update_users_table', 1),
(5, '2024_01_01_000002_create_categories_table', 1),
(6, '2024_01_01_000003_create_courses_table', 1),
(7, '2024_01_01_000004_create_lessons_table', 1),
(8, '2024_01_01_000005_create_course_user_table', 1),
(9, '2024_01_01_000006_create_lesson_progress_table', 1),
(10, '2024_01_01_000007_create_carts_table', 1),
(11, '2024_01_01_000008_create_orders_table', 1),
(12, '2024_01_01_000009_create_order_items_table', 1),
(13, '2024_01_01_000010_create_reviews_table', 1),
(14, '2026_01_22_020756_create_quiz_questions_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cod','bank_transfer','momo','vnpay') NOT NULL DEFAULT 'cod',
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `subtotal`, `discount`, `total`, `payment_method`, `status`, `note`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 'ORD-69718B76D5EA0', 5, 0.00, 0.00, 990000.00, 'cod', 'completed', NULL, NULL, '2026-01-21 19:29:10', '2026-01-21 19:29:10'),
(2, 'ORD-6971937AE6311', 2, 0.00, 0.00, 990000.00, 'cod', 'completed', NULL, NULL, '2026-01-21 20:03:22', '2026-01-21 20:03:22'),
(3, 'ORD-6971C5EABC42A', 1, 0.00, 0.00, 990000.00, 'cod', 'completed', NULL, NULL, '2026-01-21 23:38:34', '2026-01-21 23:38:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `course_id`, `course_title`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 990000.00, '2026-01-21 19:29:10', '2026-01-21 19:29:10'),
(2, 2, 1, NULL, 990000.00, '2026-01-21 20:03:22', '2026-01-21 20:03:22'),
(3, 3, 1, NULL, 990000.00, '2026-01-21 23:38:34', '2026-01-21 23:38:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_answer` enum('a','b','c','d') NOT NULL,
  `explanation` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `lesson_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `explanation`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Khái niệm chính trong bài \'Giới thiệu về Laravel\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(2, 1, 'Trong Giới thiệu về Laravel, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(3, 1, 'Ứng dụng thực tế của Giới thiệu về Laravel là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(4, 2, 'Khái niệm chính trong bài \'Cài đặt môi trường Laravel\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(5, 2, 'Trong Cài đặt môi trường Laravel, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(6, 2, 'Ứng dụng thực tế của Cài đặt môi trường Laravel là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(7, 3, 'Khái niệm chính trong bài \'Routing trong Laravel\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(8, 3, 'Trong Routing trong Laravel, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(9, 3, 'Ứng dụng thực tế của Routing trong Laravel là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(10, 4, 'Khái niệm chính trong bài \'Controllers và Views\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(11, 4, 'Trong Controllers và Views, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(12, 4, 'Ứng dụng thực tế của Controllers và Views là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(13, 5, 'Khái niệm chính trong bài \'Database Migrations\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(14, 5, 'Trong Database Migrations, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(15, 5, 'Ứng dụng thực tế của Database Migrations là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(16, 6, 'Khái niệm chính trong bài \'Eloquent ORM\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(17, 6, 'Trong Eloquent ORM, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(18, 6, 'Ứng dụng thực tế của Eloquent ORM là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(19, 7, 'Khái niệm chính trong bài \'Blade Templates\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(20, 7, 'Trong Blade Templates, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(21, 7, 'Ứng dụng thực tế của Blade Templates là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(22, 8, 'Khái niệm chính trong bài \'Xây dựng dự án thực tế\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(23, 8, 'Trong Xây dựng dự án thực tế, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(24, 8, 'Ứng dụng thực tế của Xây dựng dự án thực tế là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(49, 17, 'Khái niệm chính trong bài \'Giới thiệu Flutter\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(50, 17, 'Trong Giới thiệu Flutter, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(51, 17, 'Ứng dụng thực tế của Giới thiệu Flutter là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(52, 18, 'Khái niệm chính trong bài \'Cài đặt Flutter SDK\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(53, 18, 'Trong Cài đặt Flutter SDK, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(54, 18, 'Ứng dụng thực tế của Cài đặt Flutter SDK là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(55, 19, 'Khái niệm chính trong bài \'Dart Programming cơ bản\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(56, 19, 'Trong Dart Programming cơ bản, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(57, 19, 'Ứng dụng thực tế của Dart Programming cơ bản là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(58, 20, 'Khái niệm chính trong bài \'Widgets trong Flutter\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(59, 20, 'Trong Widgets trong Flutter, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(60, 20, 'Ứng dụng thực tế của Widgets trong Flutter là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(61, 21, 'Khái niệm chính trong bài \'Layout và Navigation\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(62, 21, 'Trong Layout và Navigation, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(63, 21, 'Ứng dụng thực tế của Layout và Navigation là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(64, 22, 'Khái niệm chính trong bài \'State Management với Provider\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(65, 22, 'Trong State Management với Provider, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(66, 22, 'Ứng dụng thực tế của State Management với Provider là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(67, 23, 'Khái niệm chính trong bài \'Kết nối API\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(68, 23, 'Trong Kết nối API, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(69, 23, 'Ứng dụng thực tế của Kết nối API là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(70, 24, 'Khái niệm chính trong bài \'Build ứng dụng hoàn chỉnh\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(71, 24, 'Trong Build ứng dụng hoàn chỉnh, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(72, 24, 'Ứng dụng thực tế của Build ứng dụng hoàn chỉnh là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(73, 25, 'Khái niệm chính trong bài \'Machine Learning là gì?\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(74, 25, 'Trong Machine Learning là gì?, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(75, 25, 'Ứng dụng thực tế của Machine Learning là gì? là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(76, 26, 'Khái niệm chính trong bài \'Python cho Machine Learning\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(77, 26, 'Trong Python cho Machine Learning, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(78, 26, 'Ứng dụng thực tế của Python cho Machine Learning là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(79, 27, 'Khái niệm chính trong bài \'NumPy và Pandas\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(80, 27, 'Trong NumPy và Pandas, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(81, 27, 'Ứng dụng thực tế của NumPy và Pandas là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(82, 28, 'Khái niệm chính trong bài \'Linear Regression\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(83, 28, 'Trong Linear Regression, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(84, 28, 'Ứng dụng thực tế của Linear Regression là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(85, 29, 'Khái niệm chính trong bài \'Classification Algorithms\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(86, 29, 'Trong Classification Algorithms, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(87, 29, 'Ứng dụng thực tế của Classification Algorithms là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(88, 30, 'Khái niệm chính trong bài \'Neural Networks\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(89, 30, 'Trong Neural Networks, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(90, 30, 'Ứng dụng thực tế của Neural Networks là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(91, 31, 'Khái niệm chính trong bài \'Deep Learning với TensorFlow\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(92, 31, 'Trong Deep Learning với TensorFlow, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(93, 31, 'Ứng dụng thực tế của Deep Learning với TensorFlow là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(94, 32, 'Khái niệm chính trong bài \'Dự án dự đoán giá nhà\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(95, 32, 'Trong Dự án dự đoán giá nhà, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(96, 32, 'Ứng dụng thực tế của Dự án dự đoán giá nhà là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(121, 41, 'Khái niệm chính trong bài \'Giới thiệu Figma\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(122, 41, 'Trong Giới thiệu Figma, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(123, 41, 'Ứng dụng thực tế của Giới thiệu Figma là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(124, 42, 'Khái niệm chính trong bài \'Interface cơ bản\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(125, 42, 'Trong Interface cơ bản, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(126, 42, 'Ứng dụng thực tế của Interface cơ bản là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(127, 43, 'Khái niệm chính trong bài \'Làm việc với Shapes\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(128, 43, 'Trong Làm việc với Shapes, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(129, 43, 'Ứng dụng thực tế của Làm việc với Shapes là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(130, 44, 'Khái niệm chính trong bài \'Typography và Colors\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(131, 44, 'Trong Typography và Colors, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(132, 44, 'Ứng dụng thực tế của Typography và Colors là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(133, 45, 'Khái niệm chính trong bài \'Components và Variants\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(134, 45, 'Trong Components và Variants, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(135, 45, 'Ứng dụng thực tế của Components và Variants là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(136, 46, 'Khái niệm chính trong bài \'Auto Layout\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(137, 46, 'Trong Auto Layout, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(138, 46, 'Ứng dụng thực tế của Auto Layout là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(139, 47, 'Khái niệm chính trong bài \'Prototyping\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(140, 47, 'Trong Prototyping, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(141, 47, 'Ứng dụng thực tế của Prototyping là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(142, 48, 'Khái niệm chính trong bài \'Thiết kế Mobile App\' là gì?', 'Khái niệm A - Đúng', 'Khái niệm B', 'Khái niệm C', 'Khái niệm D', 'a', 'Đây là khái niệm chính được đề cập trong bài học.', 1, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(143, 48, 'Trong Thiết kế Mobile App, cách thực hành đúng nhất là?', 'Cách thực hành 1', 'Cách thực hành 2 - Đúng', 'Cách thực hành 3', 'Cách thực hành 4', 'b', 'Đây là cách thực hành được khuyến nghị trong bài học.', 2, '2026-01-21 19:13:35', '2026-01-21 19:13:35'),
(144, 48, 'Ứng dụng thực tế của Thiết kế Mobile App là?', 'Ứng dụng 1', 'Ứng dụng 2', 'Ứng dụng 3 - Đúng', 'Ứng dụng 4', 'c', 'Đây là ứng dụng thực tế quan trọng nhất của bài học.', 3, '2026-01-21 19:13:35', '2026-01-21 19:13:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `course_id`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 'phùng độ123456', 'approved', '2026-01-21 23:46:57', '2026-01-22 00:02:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('DRJtjj4ZHkysT2ntfAdjbKhHr5KBcTRcFX2y9BVG', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM2JDdDVJY01Mbkt0TnBldEI1OXoyYURwU0FZSU50Q3cyc0VvZVBQWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1769760673),
('E1su0ZPZ7Taq8fuHYcZXdVOhAhWZjy2PSiiuDsMf', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNWxaOHlWMTJMR1VubUNsS0VxY24ySFlGSVlzWXNXOW5KVkxIT0k0bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb3Vyc2VzL2xhcmF2ZWwtdHUtY28tYmFuLWRlbi1uYW5nLWNhbyI7czo1OiJyb3V0ZSI7czoxNDoiY291cnNlcy5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1769065325);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `avatar`, `phone`, `bio`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@example.com', 'admin', NULL, NULL, NULL, 'active', '2026-01-21 19:13:31', '$2y$12$PiVJQeIz670I/0oDQqnJjOMDqNyp7r9eMy8NnDL1LZvFpo2ek6jL.', NULL, '2026-01-21 19:13:31', '2026-01-21 19:13:31'),
(2, 'Nguyễn Văn A', 'instructor1@example.com', 'instructor', NULL, NULL, 'Giảng viên có nhiều năm kinh nghiệm trong lĩnh vực giảng dạy.', 'active', '2026-01-21 19:13:31', '$2y$12$CYSAr.NNl5KA.zoPaftIYuXAhiPQSef8QnKiwHK5CHf5I6WS3IKNa', NULL, '2026-01-21 19:13:31', '2026-01-21 19:13:31'),
(4, 'Lê Văn C', 'instructor3@example.com', 'instructor', NULL, NULL, 'Giảng viên có nhiều năm kinh nghiệm trong lĩnh vực giảng dạy.', 'active', '2026-01-21 19:13:32', '$2y$12$PBZZ60zma6nsH06CoJHZz.Y35S5IUM.a.CdcvgFTGCNHMM2HeGJ/y', NULL, '2026-01-21 19:13:32', '2026-01-21 19:13:32'),
(5, 'Học viên 1', 'student1@example.com', 'student', NULL, NULL, NULL, 'active', '2026-01-21 19:13:33', '$2y$12$4anikVRi9sXIL3ZfAAMaH.sRbyJ/AJMJ/FSX9OBJdfEMf5O4kL49S', NULL, '2026-01-21 19:13:33', '2026-01-21 19:13:33'),
(6, 'Học viên 2', 'student2@example.com', 'student', NULL, NULL, NULL, 'active', '2026-01-21 19:13:33', '$2y$12$cBe8sy1nRa/hxDRJuOvKVer8ojJYRaShdHJ8T8Sly0ONp5afxPH3C', NULL, '2026-01-21 19:13:33', '2026-01-21 19:13:33'),
(7, 'Học viên 3', 'student3@example.com', 'student', NULL, NULL, NULL, 'active', '2026-01-21 19:13:33', '$2y$12$.D5WPwBcd6gny7MY58paD.uaLzc2bCe0Fvn7jRnf68thhmjgTJC/K', NULL, '2026-01-21 19:13:33', '2026-01-21 19:13:33');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_user_id_course_id_unique` (`user_id`,`course_id`),
  ADD KEY `carts_course_id_foreign` (`course_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_category_id_foreign` (`category_id`),
  ADD KEY `courses_instructor_id_foreign` (`instructor_id`);

--
-- Chỉ mục cho bảng `course_user`
--
ALTER TABLE `course_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_user_user_id_course_id_unique` (`user_id`,`course_id`),
  ADD KEY `course_user_course_id_foreign` (`course_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lessons_course_id_slug_unique` (`course_id`,`slug`);

--
-- Chỉ mục cho bảng `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lesson_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  ADD KEY `lesson_progress_lesson_id_foreign` (`lesson_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_course_id_foreign` (`course_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_questions_lesson_id_foreign` (`lesson_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_course_id_unique` (`user_id`,`course_id`),
  ADD KEY `reviews_course_id_foreign` (`course_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `course_user`
--
ALTER TABLE `course_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_user`
--
ALTER TABLE `course_user`
  ADD CONSTRAINT `course_user_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
