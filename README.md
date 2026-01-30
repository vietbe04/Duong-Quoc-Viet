# E-Learning Website - Laravel

Website học trực tuyến được xây dựng với Laravel, cho phép người dùng đăng ký, mua khóa học và học trực tuyến.

## 📋 Tính năng

### Vai trò người dùng

- **Admin**: Quản lý toàn bộ hệ thống (users, courses, orders, reviews)
- **Instructor**: Tạo và quản lý khóa học của mình
- **Student**: Mua khóa học, học trực tuyến, đánh giá

### Chức năng chính

- 🔐 Đăng ký/Đăng nhập với phân quyền
- 📚 Quản lý danh mục và khóa học
- 🎬 Hệ thống bài học với video
- 🛒 Giỏ hàng và thanh toán
- 📊 Theo dõi tiến độ học tập
- ⭐ Đánh giá khóa học
- 👤 Quản lý hồ sơ cá nhân

## 🛠 Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js >= 16.x

## 🚀 Cài đặt

### 1. Clone dự án

```bash
git clone <repository-url>
cd webkhoahoc
```

### 2. Cài đặt dependencies

```bash
composer install
npm install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Cấu hình database

Mở file `.env` và cập nhật thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webkhoahoc
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Chạy migrations và seeder

```bash
php artisan migrate
php artisan db:seed
```

### 6. Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### 7. Chạy ứng dụng

```bash
php artisan serve
```

Truy cập: http://localhost:8000

## 👤 Tài khoản demo

| Vai trò    | Email                   | Mật khẩu |
| ---------- | ----------------------- | -------- |
| Admin      | admin@example.com       | password |
| Instructor | instructor1@example.com | password |
| Student    | student1@example.com    | password |

## 📁 Cấu trúc thư mục

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Xử lý đăng nhập, đăng ký
│   │   ├── Admin/          # Controllers cho Admin
│   │   ├── Instructor/     # Controllers cho Giảng viên
│   │   └── ...             # Controllers công khai
│   └── Middleware/         # Middleware phân quyền
├── Models/                 # Eloquent Models
└── Policies/               # Authorization Policies

resources/
├── views/
│   ├── admin/             # Views cho Admin Panel
│   ├── instructor/        # Views cho Instructor Panel
│   ├── auth/              # Views đăng nhập/đăng ký
│   ├── cart/              # Giỏ hàng
│   ├── checkout/          # Thanh toán
│   ├── courses/           # Chi tiết khóa học
│   ├── learning/          # Giao diện học tập
│   ├── profile/           # Hồ sơ cá nhân
│   └── layouts/           # Layout templates
```

## 🗃️ Database Schema

### Các bảng chính:

- **users**: Người dùng (admin, instructor, student)
- **categories**: Danh mục khóa học
- **courses**: Khóa học
- **lessons**: Bài học
- **course_user**: Đăng ký khóa học (pivot)
- **lesson_progress**: Tiến độ học tập
- **orders**: Đơn hàng
- **order_items**: Chi tiết đơn hàng
- **carts**: Giỏ hàng
- **reviews**: Đánh giá

## 🔧 Công nghệ sử dụng

- **Backend**: Laravel 10+
- **Frontend**: Blade Template, Bootstrap 5
- **Database**: MySQL
- **Icons**: Font Awesome 6
- **JavaScript**: jQuery

## 📝 Routes chính

### Public

- `GET /` - Trang chủ
- `GET /courses/{slug}` - Chi tiết khóa học

### Auth

- `GET /login` - Đăng nhập
- `GET /register` - Đăng ký
- `POST /logout` - Đăng xuất

### Student (yêu cầu đăng nhập)

- `GET /cart` - Giỏ hàng
- `GET /checkout` - Thanh toán
- `GET /learning/{course}` - Vào học
- `GET /profile` - Hồ sơ cá nhân

### Admin (prefix: /admin)

- Dashboard, Categories, Courses, Lessons, Users, Orders, Reviews

### Instructor (prefix: /instructor)

- Dashboard, Courses, Lessons

## 📄 License

MIT License

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
