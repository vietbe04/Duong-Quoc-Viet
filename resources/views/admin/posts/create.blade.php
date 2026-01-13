@extends('admin.layouts.app')

@section('title', 'Thêm bài viết mới')
@section('page-title', 'Thêm bài viết mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài viết</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug') }}" placeholder="Tự động tạo nếu để trống">
                        @error('slug')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả ngắn</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung</label>
                        <textarea name="content" id="content" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="published_at">Ngày xuất bản</label>
                        <input type="datetime-local" name="published_at" id="published_at" class="form-control" 
                               value="{{ old('published_at') }}">
                    </div>

                    {{-- Đã xóa Avatar cũ theo ý sếp --}}
                    <div class="form-group">
                        <label for="images">Hình ảnh album bài viết <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="images[]" id="images" class="custom-file-input" accept="image/*" multiple required>
                            <label class="custom-file-label" for="images">Chọn các ảnh cho bài viết...</label>
                        </div>
                        <small class="form-text text-muted">Ảnh đầu tiên sẽ tự động được chọn làm Ảnh đại diện (Avatar) 300x300.</small>
                        <div id="images-preview" class="mt-3 row"></div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
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

    // Custom file input
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        // Preview image
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
                        '<div class="col-md-3 mb-3">' +
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

    // Auto generate slug
    $('#name').on('keyup', function() {
        var slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });
});
</script>
@endpush
