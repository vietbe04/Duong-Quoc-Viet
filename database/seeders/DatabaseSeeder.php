<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        // Create Instructor Users
        $instructors = [];
        $instructorData = [
            ['name' => 'Nguyễn Văn A', 'email' => 'instructor1@example.com'],
            ['name' => 'Trần Thị B', 'email' => 'instructor2@example.com'],
            ['name' => 'Lê Văn C', 'email' => 'instructor3@example.com'],
        ];
        
        foreach ($instructorData as $data) {
            $instructors[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'bio' => 'Giảng viên có nhiều năm kinh nghiệm trong lĩnh vực giảng dạy.',
                'email_verified_at' => now(),
            ]);
        }
        
        // Create Student Users
        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $students[] = User::create([
                'name' => "Học viên {$i}",
                'email' => "student{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }
        
        // Create Categories
        $categories = [
            ['name' => 'Lập trình Web', 'description' => 'Các khóa học về phát triển web'],
            ['name' => 'Lập trình Mobile', 'description' => 'Các khóa học về phát triển ứng dụng di động'],
            ['name' => 'Data Science', 'description' => 'Khoa học dữ liệu và Machine Learning'],
            ['name' => 'DevOps', 'description' => 'CI/CD, Docker, Kubernetes'],
            ['name' => 'UI/UX Design', 'description' => 'Thiết kế giao diện người dùng'],
            ['name' => 'Kinh doanh', 'description' => 'Các khóa học về kinh doanh và marketing'],
        ];
        
        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'status' => 'active',
            ]);
        }
        
        // Create Courses
        $coursesData = [
            [
                'title' => 'Laravel từ cơ bản đến nâng cao',
                'short_description' => 'Học Laravel framework một cách toàn diện, từ các khái niệm cơ bản đến nâng cao.',
                'category_index' => 0,
                'instructor_index' => 0,
                'price' => 1500000,
                'sale_price' => 990000,
            ],
            [
                'title' => 'ReactJS cho người mới bắt đầu',
                'short_description' => 'Xây dựng ứng dụng web hiện đại với ReactJS.',
                'category_index' => 0,
                'instructor_index' => 1,
                'price' => 1200000,
                'sale_price' => null,
            ],
            [
                'title' => 'Flutter Mobile App Development',
                'short_description' => 'Phát triển ứng dụng mobile đa nền tảng với Flutter.',
                'category_index' => 1,
                'instructor_index' => 2,
                'price' => 1800000,
                'sale_price' => 1290000,
            ],
            [
                'title' => 'Machine Learning cơ bản',
                'short_description' => 'Nhập môn Machine Learning với Python.',
                'category_index' => 2,
                'instructor_index' => 0,
                'price' => 2000000,
                'sale_price' => 1500000,
            ],
            [
                'title' => 'Docker & Kubernetes thực chiến',
                'short_description' => 'Containerization và orchestration cho ứng dụng.',
                'category_index' => 3,
                'instructor_index' => 1,
                'price' => 1600000,
                'sale_price' => null,
            ],
            [
                'title' => 'Figma UI/UX Design',
                'short_description' => 'Thiết kế giao diện chuyên nghiệp với Figma.',
                'category_index' => 4,
                'instructor_index' => 2,
                'price' => 800000,
                'sale_price' => 590000,
            ],
        ];
        
        foreach ($coursesData as $courseData) {
            $course = Course::create([
                'title' => $courseData['title'],
                'slug' => Str::slug($courseData['title']),
                'short_description' => $courseData['short_description'],
                'content' => "Nội dung chi tiết của khóa học {$courseData['title']}. Bạn sẽ học được nhiều kiến thức và kỹ năng thực tế.",
                'category_id' => $categoryModels[$courseData['category_index']]->id,
                'instructor_id' => $instructors[$courseData['instructor_index']]->id,
                'price' => $courseData['price'],
                'sale_price' => $courseData['sale_price'],
                'status' => 'published',
            ]);
            
            // Create Lessons for each course
            $this->createLessonsForCourse($course, $courseData['title']);
        }
        
        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Instructor: instructor1@example.com / password');
        $this->command->info('Student: student1@example.com / password');
    }
    
    /**
     * Create lessons for a specific course
     */
    private function createLessonsForCourse($course, $courseTitle)
    {
        $lessons = [];
        
        if (str_contains($courseTitle, 'Laravel')) {
            $lessons = [
                ['title' => 'Giới thiệu về Laravel', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=MFh0Fd7BsjE', 'duration' => 15],
                ['title' => 'Cài đặt môi trường Laravel', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=BXiHvgrJfkg', 'duration' => 20],
                ['title' => 'Routing trong Laravel', 'type' => 'text', 'duration' => 10],
                ['title' => 'Controllers và Views', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=gUEZwdnzL4Q', 'duration' => 25],
                ['title' => 'Database Migrations', 'type' => 'text', 'duration' => 15],
                ['title' => 'Eloquent ORM', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE', 'duration' => 30],
                ['title' => 'Blade Templates', 'type' => 'text', 'duration' => 12],
                ['title' => 'Xây dựng dự án thực tế', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY', 'duration' => 45],
            ];
        } elseif (str_contains($courseTitle, 'React')) {
            $lessons = [
                ['title' => 'React là gì?', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Tn6-PIqc4UM', 'duration' => 12],
                ['title' => 'Cài đặt React với Create React App', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=w7ejDZ8SWv8', 'duration' => 15],
                ['title' => 'JSX và Components', 'type' => 'text', 'duration' => 10],
                ['title' => 'Props và State', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=IYvD9oBCuJI', 'duration' => 20],
                ['title' => 'Hooks trong React', 'type' => 'text', 'duration' => 18],
                ['title' => 'React Router', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Law7wfdg_ls', 'duration' => 22],
                ['title' => 'Context API', 'type' => 'text', 'duration' => 15],
                ['title' => 'Dự án Todo App', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=pCA4qpQDZD8', 'duration' => 40],
            ];
        } elseif (str_contains($courseTitle, 'Flutter')) {
            $lessons = [
                ['title' => 'Giới thiệu Flutter', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=1ukSR1GRtMU', 'duration' => 15],
                ['title' => 'Cài đặt Flutter SDK', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=fmPmrJGbb6w', 'duration' => 18],
                ['title' => 'Dart Programming cơ bản', 'type' => 'text', 'duration' => 20],
                ['title' => 'Widgets trong Flutter', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=C5lpPjoivaw', 'duration' => 25],
                ['title' => 'Layout và Navigation', 'type' => 'text', 'duration' => 15],
                ['title' => 'State Management với Provider', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=d_m5csmrf7I', 'duration' => 30],
                ['title' => 'Kết nối API', 'type' => 'text', 'duration' => 20],
                ['title' => 'Build ứng dụng hoàn chỉnh', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=wLqlLSvF3Lo', 'duration' => 50],
            ];
        } elseif (str_contains($courseTitle, 'Machine Learning')) {
            $lessons = [
                ['title' => 'Machine Learning là gì?', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=ukzFI9rgwfU', 'duration' => 18],
                ['title' => 'Python cho Machine Learning', 'type' => 'text', 'duration' => 15],
                ['title' => 'NumPy và Pandas', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=vmEHCJofslg', 'duration' => 25],
                ['title' => 'Linear Regression', 'type' => 'text', 'duration' => 20],
                ['title' => 'Classification Algorithms', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=vsWrXfO3wWw', 'duration' => 30],
                ['title' => 'Neural Networks', 'type' => 'text', 'duration' => 25],
                ['title' => 'Deep Learning với TensorFlow', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=tPYj3fFJGjk', 'duration' => 35],
                ['title' => 'Dự án dự đoán giá nhà', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Wqmtf9SA_kk', 'duration' => 45],
            ];
        } elseif (str_contains($courseTitle, 'Docker')) {
            $lessons = [
                ['title' => 'Container hóa là gì?', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=rOTqprHv1YE', 'duration' => 15],
                ['title' => 'Cài đặt Docker', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=gAkwW2tuIqE', 'duration' => 12],
                ['title' => 'Docker Images và Containers', 'type' => 'text', 'duration' => 18],
                ['title' => 'Dockerfile', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=WmcdMiyqfZs', 'duration' => 22],
                ['title' => 'Docker Compose', 'type' => 'text', 'duration' => 20],
                ['title' => 'Giới thiệu Kubernetes', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=X48VuDVv0do', 'duration' => 25],
                ['title' => 'Deploy ứng dụng lên K8s', 'type' => 'text', 'duration' => 30],
                ['title' => 'CI/CD Pipeline', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=scEDHsr3APg', 'duration' => 40],
            ];
        } else {
            $lessons = [
                ['title' => 'Giới thiệu Figma', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=FTFaQWZBqQ8', 'duration' => 12],
                ['title' => 'Interface cơ bản', 'type' => 'text', 'duration' => 10],
                ['title' => 'Làm việc với Shapes', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Cx2dkpBxst8', 'duration' => 20],
                ['title' => 'Typography và Colors', 'type' => 'text', 'duration' => 15],
                ['title' => 'Components và Variants', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=k8NpeRMYN_Y', 'duration' => 25],
                ['title' => 'Auto Layout', 'type' => 'text', 'duration' => 18],
                ['title' => 'Prototyping', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=iBkXf6u8_M4', 'duration' => 22],
                ['title' => 'Thiết kế Mobile App', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=PeGfX7W1mJk', 'duration' => 50],
            ];
        }
        
        foreach ($lessons as $index => $lessonData) {
            $content = $lessonData['type'] === 'text' 
                ? "# {$lessonData['title']}\n\nĐây là bài học dạng văn bản.\n\n## Nội dung chính\n\nTrong bài học này, bạn sẽ học được:\n- Khái niệm cơ bản\n- Cách thực hành\n- Các ví dụ thực tế\n\n## Bài tập\n\nHãy thực hành những gì bạn đã học trong bài này."
                : "Xem video để học {$lessonData['title']}";
                
            $lesson = Lesson::create([
                'course_id' => $course->id,
                'title' => $lessonData['title'],
                'slug' => Str::slug($lessonData['title']) . '-' . ($index + 1),
                'content' => $content,
                'video_url' => $lessonData['type'] === 'video' ? $lessonData['url'] : null,
                'video_type' => $lessonData['type'] === 'video' ? 'youtube' : 'text',
                'duration' => $lessonData['duration'],
                'order' => $index + 1,
                'is_preview' => $index === 0 ? true : false,
                'status' => 'active',
            ]);
            
            // Add quiz questions for each lesson
            $this->createQuizForLesson($lesson, $lessonData['title']);
        }
    }
    
    /**
     * Create quiz questions for a lesson
     */
    private function createQuizForLesson($lesson, $lessonTitle)
    {
        // Create 3 quiz questions per lesson
        $quizTemplates = [
            [
                'question' => "Khái niệm chính trong bài '{$lessonTitle}' là gì?",
                'option_a' => 'Khái niệm A - Đúng',
                'option_b' => 'Khái niệm B',
                'option_c' => 'Khái niệm C',
                'option_d' => 'Khái niệm D',
                'correct_answer' => 'a',
                'explanation' => 'Đây là khái niệm chính được đề cập trong bài học.',
            ],
            [
                'question' => "Trong {$lessonTitle}, cách thực hành đúng nhất là?",
                'option_a' => 'Cách thực hành 1',
                'option_b' => 'Cách thực hành 2 - Đúng',
                'option_c' => 'Cách thực hành 3',
                'option_d' => 'Cách thực hành 4',
                'correct_answer' => 'b',
                'explanation' => 'Đây là cách thực hành được khuyến nghị trong bài học.',
            ],
            [
                'question' => "Ứng dụng thực tế của {$lessonTitle} là?",
                'option_a' => 'Ứng dụng 1',
                'option_b' => 'Ứng dụng 2',
                'option_c' => 'Ứng dụng 3 - Đúng',
                'option_d' => 'Ứng dụng 4',
                'correct_answer' => 'c',
                'explanation' => 'Đây là ứng dụng thực tế quan trọng nhất của bài học.',
            ],
        ];
        
        foreach ($quizTemplates as $index => $quiz) {
            QuizQuestion::create([
                'lesson_id' => $lesson->id,
                'question' => $quiz['question'],
                'option_a' => $quiz['option_a'],
                'option_b' => $quiz['option_b'],
                'option_c' => $quiz['option_c'],
                'option_d' => $quiz['option_d'],
                'correct_answer' => $quiz['correct_answer'],
                'explanation' => $quiz['explanation'],
                'order' => $index + 1,
            ]);
        }
    }
}
