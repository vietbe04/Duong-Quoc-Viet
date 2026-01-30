# SQL Database Setup

## Thông tin

- **Database Name**: webkhoahoc
- **Database Type**: MySQL
- **Sample Data Included**: Yes (Admin, Instructors, Students, Courses, Lessons, etc.)
- **File**: `database/webkhoahoc.sql`

## Cách sử dụng

### Phương pháp 1: Dùng phpMyAdmin

1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Tạo database mới tên `webkhoahoc`
3. Chọn database `webkhoahoc`
4. Click vào tab "Import"
5. Chọn file `database/webkhoahoc.sql`
6. Click "Go" để import

### Phương pháp 2: Dùng Command Line

```bash
# Tạo database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS webkhoahoc;"

# Import SQL file
mysql -u root webkhoahoc < database/webkhoahoc.sql
```

### Phương pháp 3: Dùng Laravel

Nếu bạn đã cấu hình .env với MySQL:

```bash
# Chạy migration và seed
php artisan migrate:fresh --seed
```

## Cấu hình .env

Đảm bảo file `.env` của bạn có cấu hình MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webkhoahoc
DB_USERNAME=root
DB_PASSWORD=
```

## Tài khoản Demo

### Admin

- Email: `admin@example.com`
- Password: `password`

### Instructors

- Email: `instructor1@example.com` (và instructor2, instructor3)
- Password: `password`

### Students

- Email: `student1@example.com` (đến student10)
- Password: `password`

## Dữ liệu Mẫu

Cơ sở dữ liệu bao gồm:

- **Users**: 1 Admin + 3 Instructors + 10 Students
- **Categories**: 6 danh mục (Lập trình web, Mobile, DevOps, Data Science, Design, Marketing)
- **Courses**: 6 khóa học với nội dung thực tế:
    - Lập trình Laravel từ cơ bản đến nâng cao
    - React JS - Xây dựng ứng dụng web hiện đại
    - Flutter - Phát triển ứng dụng di động
    - Machine Learning cơ bản
    - Docker và Kubernetes
    - Thiết kế UI/UX với Figma
- **Lessons**: 48 bài học (8 bài/khóa) bao gồm:
    - Bài học video với URL YouTube giáo dục thực tế
    - Bài học văn bản với nội dung có định dạng
- **Orders**: Dữ liệu mẫu về đơn hàng và ghi danh
- **Reviews**: Đánh giá khóa học với rating

## Cấu trúc Table

- `users` - Người dùng hệ thống
- `categories` - Danh mục khóa học
- `courses` - Các khóa học
- `lessons` - Bài học trong khóa học
- `course_user` - Khóa học đã mua (Many-to-Many)
- `lesson_progress` - Tiến độ học bài
- `carts` - Giỏ hàng
- `orders` - Đơn hàng
- `order_items` - Chi tiết đơn hàng
- `reviews` - Đánh giá khóa học
- `cache` - Cache storage
- `jobs` - Queue jobs
- `sessions` - Session storage
