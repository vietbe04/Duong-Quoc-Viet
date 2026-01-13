@extends('admin.layouts.app')

@section('title', 'Chi tiết sản phẩm')
@section('page-title', 'Chi tiết sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $product->name }}</h3>
            </div>
            <div class="card-body">
                @if($product->image)
                    <div class="mb-3">
                        <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}">
                    </div>
                @endif
                
                <div class="mb-3">
                    <h4>Giá: 
                        @if($product->sale_price)
                            <span class="text-muted text-decoration-line-through">{{ number_format($product->regular_price, 0, ',', '.') }} đ</span>
                            <span class="text-danger">{{ number_format($product->sale_price, 0, ',', '.') }} đ</span>
                        @else
                            {{ number_format($product->regular_price, 0, ',', '.') }} đ
                        @endif
                    </h4>
                </div>
                
                @if($product->description)
                    <div class="lead mb-3">{{ $product->description }}</div>
                @endif
                
                <div class="content">
                    {!! $product->content !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td>{{ $product->slug }}</td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Số lượng:</th>
                        <td>{{ $product->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($product->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
