<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'bio',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is instructor
     */
    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Courses created by instructor
     */
    public function instructorCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Courses enrolled by student
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['enrolled_at', 'progress', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Alias for enrolledCourses - purchased courses
     */
    public function purchasedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['enrolled_at', 'progress', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * User's cart items
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * User's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * User's lesson progress
     */
    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Check if user has purchased a course
     */
    public function hasPurchased(Course $course): bool
    {
        return $this->enrolledCourses()->where('course_id', $course->id)->exists();
    }

    /**
     * Check if user has course in cart
     */
    public function hasInCart(Course $course): bool
    {
        return $this->cartItems()->where('course_id', $course->id)->exists();
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }
}
