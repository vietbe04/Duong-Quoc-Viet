-- ============================================================
-- SQL DEMO: Toàn bộ hệ thống E-Learning
-- Khóa học, Bài học, Câu hỏi trắc nghiệm, Đánh giá
-- NOTE: Các INSERT đã được chuyển sang `INSERT IGNORE` để
--       bỏ qua những bản ghi trùng (ví dụ: email trùng) khi import
-- ============================================================

-- ============================================================
-- 1. USERS (Người dùng: Admin, Instructor, Student)
-- ============================================================

INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`, `email_verified_at`, `created_at`, `updated_at`) VALUES
-- Admin
('Administrator', 'admin@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyzabcdef', 'admin', NOW(), NOW(), NOW()),

-- Instructors
('Nguyễn Văn A - Giảng viên', 'instructor1@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyzabcdef', 'instructor', NOW(), NOW(), NOW()),
('Trần Thị B - Giảng viên', 'instructor2@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyzabcdef', 'instructor', NOW(), NOW(), NOW()),

-- Students
('Lê Văn C - Học viên', 'student1@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyzabcdef', 'student', NOW(), NOW(), NOW()),
('Phạm Thị D - Học viên', 'student2@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyzabcdef', 'student', NOW(), NOW(), NOW()),
('Hoàng Văn E - Học viên', 'student3@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyzabcdef', 'student', NOW(), NOW(), NOW());

-- ============================================================
-- 2. CATEGORIES (Danh mục)
-- ============================================================

INSERT IGNORE INTO `categories` (`name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
('Lập trình Web', 'lap-trinh-web', 'Các khóa học về phát triển ứng dụng web', 'active', NOW(), NOW()),
('Lập trình Mobile', 'lap-trinh-mobile', 'Phát triển ứng dụng di động', 'active', NOW(), NOW());

-- ============================================================
-- 3. COURSES (Khóa học)
-- ============================================================

INSERT IGNORE INTO `courses` (`category_id`, `instructor_id`, `title`, `slug`, `price`, `sale_price`, `short_description`, `content`, `level`, `status`, `created_at`, `updated_at`) VALUES
-- Khóa học 1: Laravel (Giảng viên 1)
(1, 2, 'Laravel từ Cơ bản đến Nâng cao', 'laravel-co-ban-nang-cao', 1500000, 999000, 
'Học Laravel framework hoàn toàn từ đầu đến nâng cao, bao gồm database, authentication, API', 
'Khóa học này sẽ dạy bạn toàn bộ quy trình phát triển ứng dụng web với Laravel', 
'beginner', 'published', NOW(), NOW()),

-- Khóa học 2: ReactJS (Giảng viên 2)
(1, 3, 'ReactJS cho người mới bắt đầu', 'reactjs-co-ban', 1200000, NULL, 
'Học ReactJS để xây dựng ứng dụng web hiện đại và tương tác',
'Khóa học ngoại hạng về ReactJS với các ví dụ thực tế',
'beginner', 'published', NOW(), NOW());

-- ============================================================
-- 4. LESSONS (Bài học - BÀI HỌC VĂN BẢN)
-- ============================================================

INSERT IGNORE INTO `lessons` (`course_id`, `title`, `slug`, `video_type`, `video_url`, `content`, `duration`, `order`, `is_preview`, `status`, `created_at`, `updated_at`) VALUES
-- Laravel - Bài học văn bản
(1, 'Giới thiệu về Laravel', 'gioi-thieu-ve-laravel', 'text', NULL, 
'## Laravel là gì?

Laravel là một framework PHP hiện đại, được thiết kế để làm cho phát triển web trở nên nhanh chóng và dễ dàng hơn.

### Các tính năng chính:
- **Eloquent ORM**: Lớp trừu tượng database mạnh mẽ
- **Routing**: Hệ thống route linh hoạt
- **Middleware**: Xử lý request một cách an toàn
- **Authentication**: Xác thực người dùng tích hợp sẵn
- **Migrations**: Quản lý schema database
- **Blade Templating**: Template engine mạnh mẽ

### Lợi ích:
1. **Tốc độ phát triển nhanh**
2. **Code sạch và dễ bảo trì**
3. **Bảo mật cao**
4. **Cộng đồng lớn**
5. **Tài liệu chi tiết**

Laravel được sử dụng bởi hàng trăm ngàn developer trên thế giới.',
0, 1, 1, 'active', NOW(), NOW()),

(1, 'Cài đặt môi trường Laravel', 'cai-dat-moi-truong-laravel', 'text', NULL, 
'## Cài đặt Laravel

### Bước 1: Yêu cầu hệ thống
- PHP 8.1 hoặc cao hơn
- Composer
- MySQL 5.7 hoặc SQLite

### Bước 2: Tạo dự án Laravel
```bash
composer create-project laravel/laravel laravel-demo
cd laravel-demo
```

### Bước 3: Cấu hình .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 4: Chạy dự án
```bash
php artisan serve
```

Truy cập http://127.0.0.1:8000',
0, 2, 1, 'active', NOW(), NOW()),

-- ============================================================
-- 5. LESSONS (Bài học - BÀI HỌC VIDEO)
-- ============================================================

(1, 'Routing trong Laravel', 'routing-trong-laravel', 'video', 'https://www.youtube.com/watch?v=Uyei2iDA4Hs', 
'Trong bài này, chúng ta sẽ học về hệ thống routing trong Laravel - cách định nghĩa các route, xử lý request, và truyền tham số.',
45, 3, 0, 'active', NOW(), NOW()),

(1, 'Controllers và Views', 'controllers-va-views', 'video', 'https://www.youtube.com/watch?v=MDvfg84pZHw', 
'Bài học về cách tạo Controllers, xử lý logic business, và truyền dữ liệu đến Views.',
50, 4, 0, 'active', NOW(), NOW()),

-- ReactJS - Bài học
(2, 'Giới thiệu React', 'gioi-thieu-react', 'text', NULL, 
'## ReactJS là gì?

React là một JavaScript library để xây dựng giao diện người dùng với các thành phần (Components).

### Điểm mạnh:
- **Component-based**: Xây dựng UI từ các thành phần tái sử dụng
- **Virtual DOM**: Cập nhật hiệu quả
- **Unidirectional data flow**: Dữ liệu chảy một chiều
- **Large ecosystem**: Nhiều thư viện hỗ trợ

### Cài đặt:
```bash
npx create-react-app my-app
cd my-app
npm start
```',
0, 1, 1, 'active', NOW(), NOW()),

(2, 'React Components và JSX', 'react-components-jsx', 'video', 'https://www.youtube.com/watch?v=7YCVHnItKTY', 
'Tìm hiểu về Components, JSX syntax, và cách tạo component trong React.',
55, 2, 0, 'active', NOW(), NOW());

-- ============================================================
-- 6. QUIZ QUESTIONS (Câu hỏi trắc nghiệm)
-- ============================================================

-- Câu hỏi cho bài "Giới thiệu về Laravel"
INSERT IGNORE INTO `quiz_questions` (`lesson_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `explanation`, `order`, `created_at`, `updated_at`) VALUES

(1, 'Laravel được phát triển bởi ai?', 
'Rasmus Lerdorf', 'Taylor Otwell', 'Monty Widenius', 'Guido van Rossum', 
'b', 'Laravel được tạo ra bởi Taylor Otwell vào năm 2011', 1, NOW(), NOW()),

(1, 'Phiên bản Laravel hiện tại (2026) là gì?', 
'Laravel 9', 'Laravel 10', 'Laravel 11', 'Laravel 12', 
'd', 'Laravel 12 là phiên bản mới nhất vào năm 2026', 2, NOW(), NOW()),

(1, 'ORM nào được Laravel sử dụng?', 
'Doctrine', 'Eloquent', 'Propel', 'CakePHP', 
'b', 'Eloquent ORM là lớp trừu tượng database của Laravel', 3, NOW(), NOW()),

-- Câu hỏi cho bài "Cài đặt môi trường Laravel"
(2, 'Phiên bản PHP tối thiểu để cài Laravel là?', 
'PHP 7.0', 'PHP 7.4', 'PHP 8.0', 'PHP 8.1', 
'd', 'Laravel 12 yêu cầu PHP 8.1 trở lên', 1, NOW(), NOW()),

(2, 'Công cụ quản lý package cho PHP là gì?', 
'NPM', 'Composer', 'Pip', 'Maven', 
'b', 'Composer là trình quản lý dependencies cho PHP', 2, NOW(), NOW()),

(2, 'Cách chạy Laravel development server?', 
'php run', 'php artisan serve', 'npm start', 'php server', 
'b', 'Lệnh php artisan serve chạy development server', 3, NOW(), NOW()),

-- Câu hỏi cho bài "Routing trong Laravel"
(3, 'Route HTTP nào được sử dụng để lấy dữ liệu?', 
'POST', 'PUT', 'GET', 'DELETE', 
'c', 'GET method được dùng để lấy dữ liệu từ server', 1, NOW(), NOW()),

(3, 'Cách định nghĩa route trong Laravel?', 
'Route::create()', 'Route::define()', 'Route::get()', 'Route::register()', 
'c', 'Route::get(), Route::post(), v.v. để định nghĩa routes', 2, NOW(), NOW()),

-- Câu hỏi cho bài "Controllers và Views"
(4, 'Controller trong MVC có vai trò gì?', 
'Quản lý giao diện', 'Xử lý logic business', 'Lưu trữ dữ liệu', 'Hiển thị dữ liệu', 
'b', 'Controller xử lý logic và điều phối request', 1, NOW(), NOW()),

(4, 'View được sử dụng để làm gì?', 
'Xử lý dữ liệu', 'Hiển thị giao diện', 'Kết nối database', 'Quản lý route', 
'b', 'View là lớp trình bày dữ liệu cho người dùng', 2, NOW(), NOW()),

-- Câu hỏi cho ReactJS
(5, 'Component trong React là gì?', 
'Một biến', 'Một hàm hoặc class tái sử dụng', 'Một database', 'Một server', 
'b', 'Component là một khối code tái sử dụng trong React', 1, NOW(), NOW()),

(5, 'JSX là gì?', 
'JavaScript XML - cú pháp mở rộng của JavaScript', 'Một framework mới', 'Một trình biên dịch', 'Một thư viện', 
'a', 'JSX cho phép viết HTML-like syntax trong JavaScript', 2, NOW(), NOW());

-- ============================================================
-- 7. ORDERS (Đơn hàng - Học viên mua khóa học)
-- ============================================================

INSERT IGNORE INTO `orders` (`user_id`, `total_amount`, `status`, `payment_method`, `created_at`, `updated_at`) VALUES
(4, 999000, 'completed', 'credit_card', NOW(), NOW()),  -- Student 1 mua khóa Laravel
(4, 1200000, 'completed', 'credit_card', NOW(), NOW()),  -- Student 1 mua khóa ReactJS
(5, 999000, 'completed', 'bank_transfer', NOW(), NOW()),  -- Student 2 mua khóa Laravel
(6, 1200000, 'completed', 'credit_card', NOW(), NOW());  -- Student 3 mua khóa ReactJS

-- ============================================================
-- 8. ORDER ITEMS (Chi tiết đơn hàng)
-- ============================================================

INSERT IGNORE INTO `order_items` (`order_id`, `course_id`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 999000, NOW(), NOW()),   -- Order 1: Laravel course
(2, 2, 1200000, NOW(), NOW()),  -- Order 2: ReactJS course
(3, 1, 999000, NOW(), NOW()),   -- Order 3: Laravel course
(4, 2, 1200000, NOW(), NOW());  -- Order 4: ReactJS course

-- ============================================================
-- 9. COURSE_USER (Ghi danh học viên vào khóa học)
-- ============================================================

INSERT IGNORE INTO `course_user` (`user_id`, `course_id`, `enrolled_at`, `created_at`, `updated_at`) VALUES
(4, 1, NOW(), NOW(), NOW()),  -- Student 1 ghi danh Laravel
(4, 2, NOW(), NOW(), NOW()),  -- Student 1 ghi danh ReactJS
(5, 1, NOW(), NOW(), NOW()),  -- Student 2 ghi danh Laravel
(6, 2, NOW(), NOW(), NOW());  -- Student 3 ghi danh ReactJS

-- ============================================================
-- 10. LESSON PROGRESS (Tiến độ học của học viên)
-- ============================================================

INSERT IGNORE INTO `lesson_progress` (`user_id`, `lesson_id`, `is_completed`, `completed_at`, `created_at`, `updated_at`) VALUES
-- Student 1 tiến độ khóa Laravel
(4, 1, 1, NOW(), NOW(), NOW()),  -- Hoàn thành bài 1
(4, 2, 1, NOW(), NOW(), NOW()),  -- Hoàn thành bài 2
(4, 3, 1, NOW(), NOW(), NOW()),  -- Hoàn thành bài 3
(4, 4, 0, NULL, NOW(), NOW()),   -- Chưa hoàn thành bài 4

-- Student 2 tiến độ khóa Laravel
(5, 1, 1, NOW(), NOW(), NOW()),  -- Hoàn thành bài 1
(5, 2, 0, NULL, NOW(), NOW()),   -- Chưa hoàn thành bài 2

-- Student 1 tiến độ khóa ReactJS
(4, 5, 1, NOW(), NOW(), NOW()),  -- Hoàn thành bài 1
(4, 6, 0, NULL, NOW(), NOW()),   -- Chưa hoàn thành bài 2

-- Student 3 tiến độ khóa ReactJS
(6, 5, 0, NULL, NOW(), NOW()),   -- Chưa hoàn thành bài 1
(6, 6, 0, NULL, NOW(), NOW());   -- Chưa hoàn thành bài 2

-- ============================================================
-- 11. REVIEWS (Đánh giá khóa học)
-- ============================================================

INSERT IGNORE INTO `reviews` (`user_id`, `course_id`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
-- Đánh giá khóa Laravel
(4, 1, 5, 'Khóa học rất tuyệt vời! Giảng viên giải thích chi tiết, ví dụ thực tế rất hữu ích. Tôi đã học được rất nhiều kiến thức mới về Laravel. Khóa học xứng đáng với mức giá.', 'approved', NOW(), NOW()),
(5, 1, 4, 'Nội dung khóa học tốt, video chất lượng cao. Một số phần hơi nhanh, cần có thêm bài tập thực hành.', 'approved', NOW(), NOW()),

-- Đánh giá khóa ReactJS
(4, 2, 5, 'Tuyệt vời! Khóa học này đã giúp tôi hiểu rõ về React. Từ JSX đến Hooks, tất cả đều được giải thích rõ ràng.', 'approved', NOW(), NOW()),
(6, 2, 3, 'Khóa học ổn nhưng cần cập nhật thêm các công nghệ mới như Next.js.', 'pending', NOW(), NOW());

-- ============================================================
-- SUMMARY:
-- ============================================================
-- 
-- ✅ USERS:
--    - 1 Admin, 2 Instructors, 3 Students
--
-- ✅ COURSES:
--    - Laravel course (Instructor 1) - Giá: 999,000 VND
--    - ReactJS course (Instructor 2) - Giá: 1,200,000 VND
--
-- ✅ LESSONS:
--    - Văn bản: "Giới thiệu Laravel", "Cài đặt môi trường", "Giới thiệu React"
--    - Video: "Routing", "Controllers & Views", "React Components"
--
-- ✅ QUIZ QUESTIONS:
--    - 12 câu hỏi trắc nghiệm (3 câu/bài học)
--    - Có giải thích chi tiết
--
-- ✅ ORDERS:
--    - 4 đơn hàng từ các học viên
--    - Tất cả đã được thanh toán
--
-- ✅ LESSON PROGRESS:
--    - Học viên 1: Hoàn thành 75% khóa Laravel, 50% ReactJS
--    - Học viên 2: Hoàn thành 50% khóa Laravel
--    - Học viên 3: Mới bắt đầu khóa ReactJS
--
-- ✅ REVIEWS:
--    - 4 đánh giá (2 khóa Laravel, 2 khóa ReactJS)
--    - 3 đã được duyệt, 1 đang chờ
--    - Rating từ 3-5 sao
-- 
-- ============================================================
