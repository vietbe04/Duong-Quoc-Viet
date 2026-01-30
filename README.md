# 🎓 Modern E-Learning Platform - Laravel 12

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Một nền tảng học trực tuyến hiện đại, mạnh mẽ được xây dựng trên hệ sinh thái Laravel. Dự án đã được hiện đại hóa toàn diện với thiết kế **Premium Glassmorphism**, tối ưu hóa trải nghiệm người dùng (UX) và tích hợp các tính năng học tập thông minh.

---

## ✨ Điểm Nổi Bật (Modernization Highlights)

Hệ thống vừa được nâng cấp toàn diện về giao diện và hiệu năng:
- **💎 Premium Design System:** Giao diện phong cách Glassmorphism hiện đại, trong suốt và tinh tế.
- **🖋️ Typography:** Sử dụng font **Inter** cao cấp cho khả năng hiển thị văn bản sắc nét và dễ đọc.
- **📱 Ultra Responsive:** Tối ưu hóa hiển thị hoàn hảo trên mọi thiết bị (Mobile, Tablet, Desktop).
- **🕹️ immersive Learning Hub:** Giao diện học tập kiểu "Split-pane" chuyên nghiệp, tập trung hoàn toàn vào nội dung bài giảng.
- **⚡ Optimized Performance:** Hệ thống assets được quản lý và tối ưu thông qua Laravel Vite.

---

## 📋 Tính Năng Chính (Core Features)

### 👥 Phân Quyền Người Dùng
- **Quản trị viên (Admin):** Kiểm soát toàn bộ hệ thống qua Dashboard hiện đại: quản lý khóa học, người dùng, đơn hàng và đánh giá.
- **Giảng viên (Instructor):** Soạn thảo bài giảng, quản lý nội dung khóa học và theo dõi thống kê doanh thu.
- **Học viên (Student):** Khám phá khóa học, mua hàng, theo dõi tiến trình học tập và kiểm tra kiến thức qua Quiz.

### 📚 Tính Năng Học Tập
- **Video Player:** Trình phát video tích hợp mượt mà (hỗ trợ Youtube & Video local).
- **Interactive Quiz:** Hệ thống kiểm tra kiến thức tự động sau mỗi bài học để mở khóa bài tiếp theo.
- **Progress Tracking:** Theo dõi tỷ lệ hoàn thành khóa học theo thời gian thực.
- **Review System:** Đánh giá và phản hồi chất lượng khóa học một cách minh bạch.

---

## � Công Nghệ Sử Dụng (Tech Stack)

### Backend
- **Framework:** Laravel 12.x
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0 / MariaDB

### Frontend
- **Blade Template:** Công cụ rendering mạnh mẽ của Laravel.
- **Styling:** CSS3 (Custom Glassmorphism) & Bootstrap 5.3.
- **Assets:** Vite (Build tool thế hệ mới).
- **Components:** Font Awesome 6, jQuery, SweetAlert2.

---

## 🚀 Hướng Dẫn Cài Đặt (Installation)

### 1. Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### 2. Các bước thiết lập

```bash
# Clone dự án
git clone https://github.com/your-username/duong-quoc-viet.git
cd duong-quoc-viet

# Cài đặt PHP dependencies
composer install

# Cài đặt Frontend dependencies
npm install

# Tạo file cấu hình
cp .env.example .env
php artisan key:generate

# Cấu hình Database trong file .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# Chạy Migration & Seeder (Tạo dữ liệu mẫu)
php artisan migrate --seed

# Tạo liên kết storage
php artisan storage:link

# Khởi chạy dự án
php artisan serve
npm run dev
```

Truy cập: `http://localhost:8000`

---

## 🧪 Tài Khoản Thử Nghiệm (Demo Accounts)

| Vai trò | Email | Mật khẩu |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `password` |
| **Instructor** | `instructor@example.com` | `password` |
| **Student** | `student@example.com` | `password` |

---

## 📁 Cấu Trúc Dự Án (Project Structure)

```text
app/
├── Http/Controllers/Admin/      # Quản trị hệ thống
├── Http/Controllers/Instructor/ # Quản lý bài giảng
├── Http/Controllers/           # Xử lý Logic công khai & Học viên
├── Models/                      # Định nghĩa thực thể dữ liệu
└── Policies/                    # Chính sách phân quyền

resources/
├── views/
│   ├── layouts/     # Layouts (App, Admin, Auth)
│   ├── courses/     # Chi tiết & Danh sách khóa học
│   ├── learning/    # Interface học tập (Lesson, Quiz)
│   ├── profile/     # Dashboard cá nhân
│   └── home.blade.php # Trang chủ hiện đại
└── css/app.css      # Cấu hình Design System
```

---

## 📄 Giấy Phép (License)

Dự án này được phát hành dưới giấy phép **MIT**.

---
*Phát triển bởi Dương Quốc Việt - 2026*
