<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'video_url',
        'video_type',
        'content',
        'duration',
        'order',
        'is_preview',
        'status',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'order' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
            
            // Auto set order
            if (empty($lesson->order)) {
                $maxOrder = Lesson::where('course_id', $lesson->course_id)->max('order');
                $lesson->order = ($maxOrder ?? 0) + 1;
            }
        });
    }

    /**
     * Course of this lesson
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Progress records for this lesson
     */
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Quiz questions for this lesson
     */
    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    /**
     * Check if lesson is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if lesson is preview
     */
    public function isPreview(): bool
    {
        return $this->is_preview;
    }

    /**
     * Scope for active lessons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for preview lessons
     */
    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:00', $hours, $minutes);
        }
        return sprintf('%d:%02d', $minutes, 0);
    }

    /**
     * Get video embed URL
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        switch ($this->video_type) {
            case 'youtube':
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
                if (!empty($matches[1])) {
                    return 'https://www.youtube.com/embed/' . $matches[1];
                }
                break;
            case 'vimeo':
                preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches);
                if (!empty($matches[1])) {
                    return 'https://player.vimeo.com/video/' . $matches[1];
                }
                break;
            case 'upload':
                return asset('storage/' . $this->video_url);
        }

        return $this->video_url;
    }

    /**
     * Check if user has completed this lesson
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->progress()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }
}
