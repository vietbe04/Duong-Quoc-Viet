<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'slug',
        'thumbnail',
        'price',
        'sale_price',
        'short_description',
        'content',
        'level',
        'duration',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        static::updating(function ($course) {
            if ($course->isDirty('title') && !$course->isDirty('slug')) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    /**
     * Category of the course
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Instructor of the course
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Lessons in this course
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Active lessons
     */
    public function activeLessons()
    {
        return $this->hasMany(Lesson::class)->where('status', 'active')->orderBy('order');
    }

    /**
     * Students enrolled in this course
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['enrolled_at', 'progress', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Reviews for this course
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Approved reviews
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    /**
     * Get the final price
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if course has discount
     */
    public function hasDiscount(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentAttribute(): int
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Check if course is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Scope for published courses
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured courses
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/default-course.png');
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total students count
     */
    public function getTotalStudentsAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get total lessons count
     */
    public function getTotalLessonsAttribute(): int
    {
        return $this->activeLessons()->count();
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . ' giờ ' . $minutes . ' phút';
        }
        return $minutes . ' phút';
    }

    /**
     * Get level text in Vietnamese
     */
    public function getLevelTextAttribute(): string
    {
        return match($this->level) {
            'beginner' => 'Cơ bản',
            'intermediate' => 'Trung cấp',
            'advanced' => 'Nâng cao',
            default => 'Cơ bản',
        };
    }
}
