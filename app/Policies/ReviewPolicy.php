<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $course): bool
    {
        // User must have purchased the course
        return $user->hasPurchased($course);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->isAdmin() || $review->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->isAdmin() || $review->user_id === $user->id;
    }

    /**
     * Determine whether the user can approve reviews.
     */
    public function approve(User $user, Review $review): bool
    {
        return $user->isAdmin();
    }
}
