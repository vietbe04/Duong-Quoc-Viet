<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
    ];

    /**
     * User who owns this cart item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Course in cart
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
