<?php

use Illuminate\Support\Facades\Route;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

// Instructor Controllers
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\LessonController as InstructorLessonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================================
// PUBLIC ROUTES
// ========================================

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Chi tiết khóa học
Route::get('/courses/{slug}', [HomeController::class, 'courseDetail'])->name('courses.detail');

// ========================================
// AUTHENTICATION ROUTES
// ========================================

Route::middleware('guest')->group(function () {
    // Đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Đăng ký
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Đăng xuất (hỗ trợ cả GET và POST để tương thích)
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

// ========================================
// STUDENT ROUTES (Authenticated Users)
// ========================================

Route::middleware(['auth'])->group(function () {
    // Giỏ hàng
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'count'])->name('count');
    });

    // Thanh toán
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success');
    });

    // Học khóa học
    Route::prefix('learn')->name('learn.')->group(function () {
        Route::get('/{courseSlug}', [LearningController::class, 'course'])->name('course');
        Route::get('/{courseSlug}/{lessonSlug}', [LearningController::class, 'lesson'])->name('lesson');
        Route::post('/complete/{lessonId}', [LearningController::class, 'markComplete'])->name('complete');
        Route::post('/incomplete/{lessonId}', [LearningController::class, 'markIncomplete'])->name('incomplete');
    });

    // Trang cá nhân
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('password');
        Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [ProfileController::class, 'orderDetail'])->name('orders.detail');
    });

    // Đánh giá khóa học
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/{courseId}', [ReviewController::class, 'store'])->name('store');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
    });
});

// ========================================
// ADMIN ROUTES
// ========================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::resource('categories', AdminCategoryController::class);

    // Quản lý khóa học
    Route::resource('courses', AdminCourseController::class);

    // Quản lý bài học (nested resource)
    Route::resource('courses.lessons', AdminLessonController::class);
    Route::post('courses/{course}/lessons/order', [AdminLessonController::class, 'updateOrder'])
        ->name('courses.lessons.order');

    // Quản lý người dùng
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    // Quản lý đơn hàng
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    // Quản lý đánh giá
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])
        ->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])
        ->name('reviews.reject');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])
        ->name('reviews.destroy');
});

// ========================================
// INSTRUCTOR ROUTES
// ========================================

use App\Http\Controllers\Instructor\QuizQuestionController;

Route::middleware(['auth', 'instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    // Dashboard
    Route::get('/', [InstructorDashboardController::class, 'index'])->name('dashboard');

    // Quản lý khóa học của instructor
    Route::resource('courses', InstructorCourseController::class);

    // Quản lý bài học
    Route::resource('courses.lessons', InstructorLessonController::class);
    Route::post('courses/{course}/lessons/order', [InstructorLessonController::class, 'updateOrder'])
        ->name('courses.lessons.order');
    
    // Quản lý quiz questions
    Route::resource('courses.lessons.quiz', QuizQuestionController::class);
});

// ========================================
// API ROUTES
// ========================================

use App\Http\Controllers\Api\QuizController;

// DEBUG ROUTE - Remove in production
Route::get('api/debug/lesson/{lesson}', function($lesson) {
    $lessonModel = \App\Models\Lesson::find($lesson);
    if (!$lessonModel) return response()->json(['error' => 'Lesson not found'], 404);
    return response()->json([
        'lesson_id' => $lessonModel->id,
        'lesson_title' => $lessonModel->title,
        'quiz_questions_count' => $lessonModel->quizQuestions()->count(),
        'quiz_questions_sample' => $lessonModel->quizQuestions()->select('id', 'question', 'lesson_id')->limit(3)->get(),
    ]);
});

Route::prefix('api')->middleware('auth')->group(function () {
    // Quiz API
    Route::get('lessons/{lesson}/quiz', [QuizController::class, 'getQuestions'])->name('api.quiz.questions');
    Route::post('lessons/{lesson}/quiz', [QuizController::class, 'submitAnswers'])->name('api.quiz.submit');
});