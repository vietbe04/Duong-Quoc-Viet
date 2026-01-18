@extends('admin.layouts.app')

@section('title', 'Quản lý Chat')
@section('page-title', 'Quản lý Chat')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Chat</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-comments me-2"></i> Danh sách cuộc hội thoại</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Khách hàng</th>
                    <th>Tiêu đề</th>
                    <th>Tin nhắn cuối</th>
                    <th>Trạng thái</th>
                    <th>Admin phụ trách</th>
                    <th style="width: 120px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $conversation)
                <tr>
                    <td>{{ $conversation->id }}</td>
                    <td>
                        <strong>{{ $conversation->user->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $conversation->user->email }}</small>
                    </td>
                    <td>{{ $conversation->title ?: 'Chưa có tiêu đề' }}</td>
                    <td>
                        @if($conversation->latestMessage->isNotEmpty())
                            <small>
                                {{ Str::limit($conversation->latestMessage->first()->message, 50) }}
                                <br>
                                <span class="text-muted">{{ $conversation->latestMessage->first()->created_at->diffForHumans() }}</span>
                            </small>
                        @else
                            <span class="text-muted">Chưa có tin nhắn</span>
                        @endif
                    </td>
                    <td>
                        @if($conversation->status === 'open')
                            <span class="badge bg-success">Đang mở</span>
                        @elseif($conversation->status === 'pending')
                            <span class="badge bg-warning">Chờ xử lý</span>
                        @else
                            <span class="badge bg-secondary">Đã đóng</span>
                        @endif
                        
                        @php
                            $unread = $conversation->messages()
                                ->where('user_id', $conversation->user_id)
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($unread > 0)
                            <span class="badge bg-danger">{{ $unread }} mới</span>
                        @endif
                    </td>
                    <td>
                        @if($conversation->admin)
                            {{ $conversation->admin->name }}
                        @else
                            <span class="text-muted">Chưa có</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.chat.show', $conversation) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-comments fa-3x text-muted mb-3 d-block"></i>
                        Chưa có cuộc hội thoại nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($conversations->hasPages())
    <div class="card-footer">
        {{ $conversations->links() }}
    </div>
    @endif
</div>
@endsection
