<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'subject',
        'status',
        'error_message',
        'attempts',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
