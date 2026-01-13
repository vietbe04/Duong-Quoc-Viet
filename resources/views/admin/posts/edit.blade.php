@extends('admin.layouts.app')

@section('title', 'Sửa bài viết')
@section('page-title', 'Sửa bài viết')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin bài viết</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $post->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug', $post->slug) }}">
                        @error('slug')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả ngắn</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung</label>
                        <textarea name="content" id="content" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cài đặt</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ old('status', $post->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $post->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="published_at">Ngày xuất bản</label>
                        <input type="datetime-local" name="published_at" id="published_at" class="form-control" 
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    {{-- Đã xóa Avatar đơn lẻ cũ theo ý sếp --}}
                    
                    <div class="form-group">
                        <label>Album ảnh hiện có</label>
                        @if($post->images && count($post->images) > 0)
                            <div class="row" id="existing-images">
                                @foreach($post->images as $imagePath)
                                    <div class="col-md-6 mb-3" data-image-path="{{ $imagePath }}">
                                        <div class="position-relative">
                                            <img src="/storage/{{ $imagePath }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                            @if($post->image == $imagePath)
                                                <span class="badge badge-success position-absolute" style="top: 5px; left: 5px;">Ảnh đại diện</span>
                                            @endif
                                            <button type="button" class="btn btn-danger btn-sm position-absolute remove-image" style="top: 5px; right: 20px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="text-center mt-1">
                                                <small class="text-muted">{{ basename($imagePath) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Chưa có ảnh nào trong album</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="images">Thêm ảnh mới vào album</label>
                        <div class="custom-file">
                            <input type="file" name="images[]" id="images" class="custom-file-input" accept="image/*" multiple>
                            <label class="custom-file-label" for="images">Chọn thêm ảnh...</label>
                        </div>
                        <small class="form-text text-muted">Hệ thống sẽ giữ ảnh đầu tiên làm ảnh đại diện nếu ảnh cũ bị xóa.</small>
                        <div id="images-preview" class="mt-3 row"></div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Single image preview
    $("#image").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Multiple images preview
    $("#images").on("change", function() {
        var fileCount = this.files.length;
        var fileName = fileCount > 1 ? fileCount + ' files đã chọn' : $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        // Preview multiple images
        $('#images-preview').empty();
        if (this.files && this.files.length > 0) {
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                let reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#images-preview').append(
                        '<div class="col-md-6 mb-3">' +
                            '<div class="position-relative">' +
                                '<img src="' + e.target.result + '" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">' +
                                '<div class="text-center mt-1"><small class="text-muted">' + file.name + '</small></div>' +
                            '</div>' +
                        '</div>'
                    );
                }
                reader.readAsDataURL(file);
            }
        }
    });

    // Remove existing image
    $('.remove-image').on('click', function() {
        if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
            var imageContainer = $(this).closest('[data-image-path]');
            var imagePath = imageContainer.data('image-path');
            
            // Add hidden input to mark image for deletion
            $('<input>').attr({
                type: 'hidden',
                name: 'remove_images[]',
                value: imagePath
            }).appendTo('form');
            
            // Remove from UI
            imageContainer.fadeOut(300, function() {
                $(this).remove();
            });
        }
    });
});
</script>
@endpush
