<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Tự động xóa ảnh khi xóa bài viết
        static::deleting(function ($post) {
            $imageService = app(\App\Services\ImageService::class);
            
            // Xóa ảnh đơn (cũ)
            if ($post->image) {
                $imageService->delete($post->image);
            }
            
            // Xóa tất cả ảnh trong mảng images
            if ($post->images && is_array($post->images)) {
                $imageService->deleteMultiple($post->images);
            }
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'image',
        'images',
        'description',
        'content',
        'status',
        'published_at',
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'active')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-post.jpg');
    }

    /**
     * Lấy tất cả URLs của ảnh
     * 
     * @return array
     */
    public function getImageUrls(): array
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        $imageService = app(\App\Services\ImageService::class);
        return array_map(function($path) use ($imageService) {
            return $imageService->getUrl($path);
        }, $this->images);
    }

    /**
     * Lấy tất cả URLs của thumbnail
     * 
     * @return array
     */
    public function getThumbnailUrls(): array
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        $imageService = app(\App\Services\ImageService::class);
        return array_map(function($path) use ($imageService) {
            return $imageService->getThumbnailUrl($path);
        }, $this->images);
    }
}
