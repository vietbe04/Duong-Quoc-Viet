@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm mới')
@section('page-title', 'Thêm sản phẩm mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin sản phẩm</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="regular_price">Giá gốc <span class="text-danger">*</span></label>
                                <input type="number" name="regular_price" id="regular_price" class="form-control @error('regular_price') is-invalid @enderror" 
                                       value="{{ old('regular_price', 0) }}" min="0" required>
                                @error('regular_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sale_price">Giá sale</label>
                                <input type="number" name="sale_price" id="sale_price" class="form-control @error('sale_price') is-invalid @enderror" 
                                       value="{{ old('sale_price') }}" min="0">
                                @error('sale_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">Số lượng</label>
                                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                       value="{{ old('quantity', 0) }}" min="0">
                                @error('quantity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
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
                        <label for="content">Nội dung chi tiết</label>
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

                    <div class="form-group">
                        <label for="image">Hình ảnh chính</label>
                        <div class="custom-file">
                            <input type="file" name="image" id="image" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="image">Chọn file...</label>
                        </div>
                        <div id="image-preview" class="mt-2"></div>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <div class="custom-file">
                            <input type="file" name="thumbnail" id="thumbnail" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="thumbnail">Chọn file...</label>
                        </div>
                        <div id="thumbnail-preview" class="mt-2"></div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
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
    $('.summernote').summernote({ height: 300 });

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        
        var previewId = $(this).attr('id') + '-preview';
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    $('#name').on('invalid',function(){
        this.setCustomValidity('Vui lòng nhập tên sản phẩm.');
    })
    $('#name').on('input',function(){
        this.setCustomValidity('');
    })
    $('#regular_price').on('input',function(){
        var value = parseFloat(this.value);
        if (this.value === ''|| isNaN(value)) {
            this.setCustomValidity('Vui lòng nhập giá gốc.');
            this.reportValidity();
        } else if (this.value < 0) {
            this.setCustomValidity('Giá gốc phải lớn hơn hoặc bằng 0.');
            this.reportValidity();  
        } else this.setCustomValidity('');
    })
    $('#name').on('keyup', function() {
        var slug = $(this).val().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        $('#slug').val(slug);
    });
});
</script>
@endpush
