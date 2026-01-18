<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected $manager;
    protected $uploadPath = 'uploads';
    protected $thumbnailPath = 'uploads/thumbnails';
    protected $thumbnailSize = 300;

    public function __construct()
    {
        if (!extension_loaded('gd')) {
            $iniPath = php_ini_loaded_file();
            $exts = implode(', ', array_slice(get_loaded_extensions(), 0, 10)) . '...';
            throw new \Exception("LỖI HỆ THỐNG: GD Extension chưa được load! \n" . 
                                 "- File php.ini đang dùng: $iniPath \n" . 
                                 "- Các extension đang load: $exts \n" . 
                                 "- Hướng dẫn: Tắt Terminal cũ, mở cái mới rồi chạy lại 'php artisan serve'");
        }
        
        // Kiểm tra class GdImage (cần cho PHP 8+)
        if (!class_exists('\GdImage') && PHP_VERSION_ID >= 80000) {
            throw new \Exception('GD được load nhưng thiếu class GdImage. Có thể bản PHP này bị lỗi build.');
        }

        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload nhiều ảnh và tạo thumbnail
     * 
     * @param array $files Mảng các file upload
     * @param string $slug Slug để tạo tên file unique
     * @return array Mảng chứa đường dẫn các ảnh đã upload
     */
    public function uploadMultiple(array $files, string $slug): array
    {
        $uploadedImages = [];

        foreach ($files as $file) {
            $imagePath = $this->uploadSingle($file, $slug);
            if ($imagePath) {
                $uploadedImages[] = $imagePath;
            }
        }

        return $uploadedImages;
    }

    /**
     * Upload một ảnh và tạo thumbnail
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $slug
     * @return string|null Đường dẫn ảnh đã upload
     */
    public function uploadSingle($file, string $slug): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Tạo tên file unique: slug-timestamp-random.ext
        $timestamp = time();
        $random = Str::random(6);
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($slug) . '-' . $timestamp . '-' . $random . '.' . $extension;

        // Lưu ảnh gốc
        $path = $this->uploadPath . '/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($file));

        // Tạo thumbnail 300x300
        $this->createThumbnail($file, $filename);

        return $path;
    }

    /**
     * Upload ảnh và trả về đường dẫn Thumbnail 300x300
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $slug
     * @return string|null Đường dẫn thumbnail
     */
    public function uploadThumbnailOnly($file, string $slug): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $timestamp = time();
        $random = Str::random(6);
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($slug) . '-' . $timestamp . '-' . $random . '.' . $extension;

        // Vẫn lưu ảnh gốc để dự phòng
        $path = $this->uploadPath . '/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($file));

        // Tạo thumbnail 300x300
        $this->createThumbnail($file, $filename);

        // Trả về đường dẫn thumbnail
        return $this->thumbnailPath . '/' . $filename;
    }

    /**
     * Tạo thumbnail 300x300
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $filename
     * @return void
     */
    protected function createThumbnail($file, string $filename): void
    {
        try {
            // Đọc ảnh
            $image = $this->manager->read($file);

            // Resize và crop về 300x300 (giữ tỷ lệ và crop phần thừa)
            $image->cover($this->thumbnailSize, $this->thumbnailSize);

            // Lưu thumbnail
            $thumbnailPath = storage_path('app/public/' . $this->thumbnailPath . '/' . $filename);
            
            // Tạo thư mục nếu chưa tồn tại
            $thumbnailDir = dirname($thumbnailPath);
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $image->save($thumbnailPath);
        } catch (\Exception $e) {
            Log::error('Lỗi tạo thumbnail: ' . $e->getMessage());
        }
    }

    /**
     * Xóa ảnh và thumbnail
     * 
     * @param string $imagePath Đường dẫn ảnh cần xóa
     * @return void
     */
    public function delete(string $imagePath): void
    {
        // 1. Xóa trực tiếp đường dẫn được truyền vào (Xử lý được cả ảnh cũ 'posts/...')
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        $filename = basename($imagePath);
        $originalPath = $this->uploadPath . '/' . $filename;
        $thumbnailPath = $this->thumbnailPath . '/' . $filename;

        // 2. Xóa ảnh gốc trong uploads/ (nếu khác với path trên)
        if ($imagePath !== $originalPath && Storage::disk('public')->exists($originalPath)) {
            Storage::disk('public')->delete($originalPath);
        }

        // 3. Xóa thumbnail trong thumbnails/ (nếu khác với path trên)
        if ($imagePath !== $thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }

    /**
     * Xóa nhiều ảnh
     * 
     * @param array $imagePaths Mảng đường dẫn ảnh cần xóa
     * @return void
     */
    public function deleteMultiple(array $imagePaths): void
    {
        foreach ($imagePaths as $imagePath) {
            $this->delete($imagePath);
        }
    }

    /**
     * Lấy URL đầy đủ của ảnh
     * 
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return asset('storage/' . $path);
    }

    /**
     * Lấy URL của thumbnail
     * 
     * @param string $imagePath
     * @return string
     */
    public function getThumbnailUrl(string $imagePath): string
    {
        $filename = basename($imagePath);
        return asset('storage/' . $this->thumbnailPath . '/' . $filename);
    }
}
