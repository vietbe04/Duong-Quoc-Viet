<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use App\Models\QuizQuestion;
use Illuminate\Console\Command;

class SeedQuizQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-quiz-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed quiz questions for all lessons';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lessons = Lesson::all();
        $count = 0;

        foreach ($lessons as $lesson) {
            // Check if this lesson already has quiz questions
            if ($lesson->quizQuestions()->count() > 0) {
                $this->info("Lesson '{$lesson->title}' already has " . $lesson->quizQuestions()->count() . " questions");
                continue;
            }

            $lessonTitle = $lesson->title;
            $quizTemplates = [
                [
                    'question' => "Khái niệm cơ bản của {$lessonTitle} là?",
                    'option_a' => 'Khái niệm 1',
                    'option_b' => 'Khái niệm 2 - Đúng',
                    'option_c' => 'Khái niệm 3',
                    'option_d' => 'Khái niệm 4',
                    'correct_answer' => 'b',
                    'explanation' => 'Đây là khái niệm cơ bản được giáo viên giải thích trong bài học.',
                ],
                [
                    'question' => "Cách thực hành đúng nhất trong {$lessonTitle} là?",
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
                $count++;
            }

            $this->line("✓ Added 3 questions to lesson '{$lesson->title}'");
        }

        $this->info("\nTotal questions seeded: {$count}");
    }
}
