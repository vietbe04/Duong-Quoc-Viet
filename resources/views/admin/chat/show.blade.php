@extends('admin.layouts.app')

{{-- 
    Blade Template: Admin Chat Show View
    This file uses Blade template syntax with JavaScript.
    Variables like CONVERSATION_ID, USER_ID, CSRF_TOKEN, MESSAGE_COUNT are injected at render time.
    Pylance warnings about {!! !!} syntax can be ignored - these are valid Blade directives.
--}}

@section('title', 'Chat - ' . $conversation->user->name)
@section('page-title', 'Chat với ' . $conversation->user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.chat.index') }}">Chat</a></li>
    <li class="breadcrumb-item active">{{ $conversation->user->name }}</li>
@endsection

@push('styles')
<style>
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 250px);
        min-height: 500px;
    }

    .chat-sidebar {
        width: 300px;
        border-right: 1px solid #dee2e6;
        overflow-y: auto;
        background: #fff;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f4f6f9;
    }

    .conversation-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .conversation-item {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background 0.2s;
    }

    .conversation-item:hover {
        background: #f8f9fa;
    }

    .conversation-item.active {
        background: #e3f2fd;
        border-left: 3px solid #007bff;
    }

    .conversation-item .user-name {
        font-weight: 600;
        margin-bottom: 3px;
    }

    .conversation-item .last-message {
        font-size: 13px;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-item .time {
        font-size: 11px;
        color: #aaa;
    }

    .chat-header {
        padding: 15px 20px;
        background: #fff;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-header .user-info h5 {
        margin: 0;
        font-weight: 600;
    }

    .chat-header .user-info small {
        color: #888;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .message {
        max-width: 70%;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }

    .message.mine {
        margin-left: auto;
        align-items: flex-end;
    }

    .message.other {
        margin-right: auto;
        align-items: flex-start;
    }

    .message-content {
        padding: 10px 15px;
        border-radius: 18px;
        word-wrap: break-word;
    }

    .message.mine .message-content {
        background: #007bff;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message.other .message-content {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .message-meta {
        font-size: 11px;
        color: #888;
        margin-top: 4px;
    }

    .message-sender {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 3px;
        color: #666;
    }

    .chat-input {
        padding: 15px 20px;
        background: #fff;
        border-top: 1px solid #dee2e6;
    }

    .chat-input .form-control {
        border-radius: 25px;
        padding: 10px 20px;
    }

    .chat-input .btn-send {
        border-radius: 50%;
        width: 45px;
        height: 45px;
        padding: 0;
    }

    .unread-badge {
        background: #dc3545;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 5px;
    }

    /* Scrollbar */
    .chat-messages::-webkit-scrollbar,
    .chat-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track,
    .chat-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb,
    .chat-sidebar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
<div class="card" data-conversation-id="{{ $conversation->id }}" data-user-id="{{ auth()->id() }}" data-csrf-token="{{ csrf_token() }}" data-message-count="{{ $messages->count() }}">
    <div class="card-body p-0">
        <div class="chat-wrapper">
            <!-- Sidebar với danh sách conversations -->
            <div class="chat-sidebar d-none d-md-block">
                <div class="p-3 bg-light border-bottom">
                    <strong><i class="fas fa-list me-2"></i> Cuộc hội thoại</strong>
                </div>
                <ul class="conversation-list">
                    @foreach($conversations as $conv)
                    <li class="conversation-item {{ $conv->id === $conversation->id ? 'active' : '' }}"
                        data-conv-id="{!! $conv->id !!}"
                        data-conv-url="{!! route('admin.chat.show', $conv) !!}"
                        onclick="window.location=this.dataset.convUrl">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="user-name">
                                {{ $conv->user->name }}
                                @php
                                    $unread = $conv->messages()
                                        ->where('user_id', $conv->user_id)
                                        ->where('is_read', false)
                                        ->count();
                                @endphp
                                @if($unread > 0)
                                    <span class="unread-badge">{{ $unread }}</span>
                                @endif
                            </div>
                            <span class="time">
                                {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}
                            </span>
                        </div>
                        <div class="last-message">
                            @if($conv->latestMessage->isNotEmpty())
                                {{ Str::limit($conv->latestMessage->first()->message, 30) }}
                            @else
                                <em>Chưa có tin nhắn</em>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Main chat area -->
            <div class="chat-main">
                <div class="chat-header">
                    <div class="user-info">
                        <h5>{{ $conversation->user->name }}</h5>
                        <small>{{ $conversation->user->email }}</small>
                    </div>
                    <div class="chat-actions">
                        @if($conversation->status === 'open')
                            <button class="btn btn-sm btn-warning" onclick="closeConversation()">
                                <i class="fas fa-times-circle"></i> Đóng
                            </button>
                        @else
                            <button class="btn btn-sm btn-success" onclick="reopenConversation()">
                                <i class="fas fa-check-circle"></i> Mở lại
                            </button>
                        @endif
                        <a href="{{ route('admin.chat.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    @forelse($messages as $message)
                        <div class="message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}">
                            @if($message->user_id !== auth()->id())
                                <span class="message-sender">{{ $message->user->name }}</span>
                            @endif
                            <div class="message-content">
                                {{ $message->message }}
                            </div>
                            <span class="message-meta" title="{{ $message->created_at->format('d/m/Y H:i') }}">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted" id="emptyChat">
                            <i class="fas fa-comment-dots fa-3x mb-3"></i>
                            <p>Chưa có tin nhắn nào trong cuộc hội thoại này.</p>
                        </div>
                    @endforelse
                </div>

                <div class="chat-input">
                    <form id="chatForm" class="d-flex gap-2">
                        @csrf
                        <input type="text" 
                               class="form-control" 
                               id="messageInput" 
                               placeholder="Nhập tin nhắn..." 
                               autocomplete="off"
                               maxlength="1000"
                               {{ $conversation->status !== 'open' ? 'disabled' : '' }}>
                        <button type="submit" class="btn btn-primary btn-send" id="sendBtn" 
                                {{ $conversation->status !== 'open' ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    @if($conversation->status !== 'open')
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle"></i> Cuộc hội thoại đã đóng. Mở lại để tiếp tục trả lời.
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Blade variables for JavaScript --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get data from card element
    const cardElement = document.querySelector('[data-conversation-id]');
    const conversationId = parseInt(cardElement.dataset.conversationId);
    const userId = parseInt(cardElement.dataset.userId);
    const csrfToken = cardElement.dataset.csrfToken;
    const initialMessageCount = parseInt(cardElement.dataset.messageCount);
    
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const emptyChat = document.getElementById('emptyChat');

    // Enable send button when there's input
    messageInput.addEventListener('input', function() {
        sendBtn.disabled = this.value.trim() === '';
    });

    // Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    scrollToBottom();

    // Add message to UI
    function addMessage(data, isMine = false) {
        if (emptyChat) {
            emptyChat.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isMine ? 'mine' : 'other'}`;
        
        let html = '';
        if (!isMine) {
            html += `<span class="message-sender">${escapeHtml(data.user_name)}</span>`;
        }
        html += `
            <div class="message-content">${escapeHtml(data.message)}</div>
            <span class="message-meta" title="${data.created_at_full}">${data.created_at}</span>
        `;
        
        messageDiv.innerHTML = html;
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Send message
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        sendBtn.disabled = true;
        messageInput.disabled = true;

        try {
            const response = await fetch(`/admin/chat/${conversationId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            if (data.success) {
                addMessage(data.message, true);
                messageInput.value = '';
            } else {
                alert('Có lỗi xảy ra: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Không thể gửi tin nhắn. Vui lòng thử lại.');
        } finally {
            messageInput.disabled = false;
            sendBtn.disabled = false;
            messageInput.focus();
        }
    });

    // Polling for new messages
    let lastMessageCount = initialMessageCount;
    
    setInterval(async function() {
        try {
            const response = await fetch(`/admin/chat/${conversationId}/messages`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            const messages = await response.json();
            
            if (messages.length > lastMessageCount) {
                // New messages arrived
                const newMessages = messages.slice(lastMessageCount);
                newMessages.forEach(msg => {
                    if (msg.user_id !== userId) {
                        addMessage(msg, false);
                    }
                });
                lastMessageCount = messages.length;
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 3000);

    // Focus input
    messageInput.focus();
});

// Close conversation
function closeConversation() {
    if (!confirm('Bạn có chắc muốn đóng cuộc hội thoại này?')) return;
    
    const conversationId = document.querySelector('[data-conversation-id]').dataset.conversationId;
    const csrfToken = document.querySelector('[data-csrf-token]').dataset.csrfToken;
    
    fetch(`/admin/chat/${conversationId}/close`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Reopen conversation
function reopenConversation() {
    const conversationId = document.querySelector('[data-conversation-id]').dataset.conversationId;
    const csrfToken = document.querySelector('[data-csrf-token]').dataset.csrfToken;
    
    fetch(`/admin/chat/${conversationId}/reopen`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
