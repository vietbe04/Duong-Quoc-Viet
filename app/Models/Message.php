<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'type',
        'attachment',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Cuộc trò chuyện chứa tin nhắn
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Người gửi tin nhắn
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Đánh dấu đã đọc
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Kiểm tra tin nhắn có phải từ admin không
     */
    public function isFromAdmin(): bool
    {
        return $this->user->hasRole('admin') || $this->user->hasRole('super-admin');
    }

    /**
     * Scope: Tin nhắn chưa đọc
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
