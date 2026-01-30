<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Get quiz questions for a lesson
     */
    public function getQuestions($lessonId)
    {
        $lesson = Lesson::with('quizQuestions')->findOrFail($lessonId);
        
        // Remove correct_answer from response
        $questions = $lesson->quizQuestions->map(function($question) {
            return [
                'id' => $question->id,
                'question' => $question->question,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'order' => $question->order,
            ];
        });
        
        return response()->json([
            'success' => true,
            'questions' => $questions,
        ]);
    }
    
    /**
     * Submit quiz answers and get results
     */
    public function submitAnswers(Request $request, $lessonId)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:a,b,c,d',
        ]);
        
        $lesson = Lesson::with('quizQuestions')->findOrFail($lessonId);
        $answers = $request->answers;
        
        $results = [];
        $correctCount = 0;
        $totalQuestions = $lesson->quizQuestions->count();
        
        foreach ($lesson->quizQuestions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_answer;
            
            if ($isCorrect) {
                $correctCount++;
            }
            
            $results[] = [
                'question_id' => $question->id,
                'question' => $question->question,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
            ];
        }
        
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $score >= 70; // 70% to pass
        
        return response()->json([
            'success' => true,
            'score' => $score,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'passed' => $passed,
            'results' => $results,
        ]);
    }
}
