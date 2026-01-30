<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lesson $lesson): bool
    {
        // Preview lessons can be viewed by anyone
        if ($lesson->isPreview()) {
            return true;
        }

        // Admin can always view
        if ($user->isAdmin()) {
            return true;
        }

        // Instructor can view their own course lessons
        if ($lesson->course->instructor_id === $user->id) {
            return true;
        }

        // Student must have purchased the course
        return $user->hasPurchased($lesson->course);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin() || $lesson->course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin() || $lesson->course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can mark the lesson as complete.
     */
    public function complete(User $user, Lesson $lesson): bool
    {
        return $user->hasPurchased($lesson->course);
    }
}
