<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        // Everyone can view published courses
        if ($course->isPublished()) {
            return true;
        }

        // Admin and instructor (owner) can view draft courses
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can learn the course.
     */
    public function learn(User $user, Course $course): bool
    {
        // Admin can always access
        if ($user->isAdmin()) {
            return true;
        }

        // Instructor can access their own course
        if ($course->instructor_id === $user->id) {
            return true;
        }

        // Student must have purchased the course
        return $user->hasPurchased($course);
    }

    /**
     * Determine whether the user can manage lessons.
     */
    public function manageLessons(User $user, Course $course): bool
    {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }
}
