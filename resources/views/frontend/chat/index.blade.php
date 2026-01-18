@extends('layouts.app')

@section('title', 'Chat với Admin')

@push('styles')
<style>
    .chat-container {
        height: calc(100vh - 200px);
        min-height: 500px;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .chat-header {
        padding: 15px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
    }

    .chat-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message.other .message-content {
        background: #e9ecef;
        color: #333;
        border-bottom-left-radius: 4px;
    }

    .message-meta {
        font-size: 11px;
        color: #888;
        margin-top: 4px;
    }

    .message.mine .message-meta {
        text-align: right;
    }

    .message-sender {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 3px;
        color: #667eea;
    }

    .chat-input {
        padding: 15px 20px;
        border-top: 1px solid #eee;
        background: #fff;
        border-radius: 0 0 10px 10px;
    }

    .chat-input .form-control {
        border-radius: 25px;
        padding: 10px 20px;
        border: 1px solid #ddd;
    }

    .chat-input .form-control:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        border-color: #667eea;
    }

    .chat-input .btn-send {
        border-radius: 50%;
        width: 45px;
        height: 45px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .chat-input .btn-send:hover {
        transform: scale(1.05);
    }

    .chat-input .btn-send:disabled {
        opacity: 0.6;
        transform: none;
    }

    .empty-chat {
        text-align: center;
        padding: 50px;
        color: #888;
    }

    .empty-chat i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #667eea;
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .connection-status {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(255,255,255,0.2);
        color: #2ecc71;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="chat-container" id="chatContainer"
                 data-conversation-id="{{ $conversation->id }}"
                 data-user-id="{{ auth()->id() }}"
                 data-last-message-id="{{ $messages->isNotEmpty() ? $messages->last()->id : 0 }}">
                <div class="chat-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5><i class="fas fa-comments me-2"></i> Chat với hỗ trợ viên</h5>
                        <small class="opacity-75">Chúng tôi sẵn sàng hỗ trợ bạn</small>
                    </div>
                    <span class="connection-status" id="connectionStatus">
                        <i class="fas fa-check-circle me-1"></i> Sẵn sàng
                    </span>
                </div>

                <div class="chat-messages" id="chatMessages">
                    @if($messages->isEmpty())
                        <div class="empty-chat" id="emptyChat">
                            <i class="fas fa-comment-dots"></i>
                            <h5>Bắt đầu cuộc trò chuyện</h5>
                            <p>Gửi tin nhắn để được hỗ trợ từ đội ngũ chăm sóc khách hàng của chúng tôi.</p>
                        </div>
                    @else
                        @foreach($messages as $message)
                            <div class="message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}" data-id="{{ $message->id }}">
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
                        @endforeach
                    @endif
                </div>

                <div class="chat-input">
                    <form id="chatForm" class="d-flex gap-2">
                        <input type="text" 
                               class="form-control" 
                               id="messageInput" 
                               placeholder="Nhập tin nhắn..." 
                               autocomplete="off"
                               maxlength="1000">
                        <button type="submit" class="btn btn-primary btn-send" id="sendBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    var container = document.getElementById('chatContainer');
    var conversationId = parseInt(container.dataset.conversationId);
    var userId = parseInt(container.dataset.userId);
    var lastMessageId = parseInt(container.dataset.lastMessageId) || 0;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    var chatMessages = document.getElementById('chatMessages');
    var chatForm = document.getElementById('chatForm');
    var messageInput = document.getElementById('messageInput');
    var sendBtn = document.getElementById('sendBtn');
    var emptyChat = document.getElementById('emptyChat');

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    scrollToBottom();

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function addMessage(data, isMine) {
        if (emptyChat) {
            emptyChat.style.display = 'none';
        }

        if (document.querySelector('.message[data-id="' + data.id + '"]')) {
            return;
        }

        var messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + (isMine ? 'mine' : 'other');
        messageDiv.setAttribute('data-id', data.id);
        
        var html = '';
        if (!isMine && data.user_name) {
            html += '<span class="message-sender">' + escapeHtml(data.user_name) + '</span>';
        }
        html += '<div class="message-content">' + escapeHtml(data.message) + '</div>';
        html += '<span class="message-meta">' + (data.created_at || '') + '</span>';
        
        messageDiv.innerHTML = html;
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
        
        if (data.id > lastMessageId) {
            lastMessageId = data.id;
        }
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var message = messageInput.value.trim();
        if (!message) return;

        sendBtn.disabled = true;
        messageInput.disabled = true;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/chat/' + conversationId + '/send', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Accept', 'application/json');
        
        xhr.onload = function() {
            messageInput.disabled = false;
            messageInput.focus();
            
            if (xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        addMessage(data.message, true);
                        messageInput.value = '';
                        sendBtn.disabled = true;
                    } else {
                        alert('Lỗi: ' + (data.error || 'Không thể gửi tin nhắn'));
                        sendBtn.disabled = false;
                    }
                } catch (e) {
                    console.error('Parse error:', e);
                    sendBtn.disabled = false;
                }
            } else {
                alert('Lỗi kết nối (HTTP ' + xhr.status + ')');
                sendBtn.disabled = false;
            }
        };
        
        xhr.onerror = function() {
            messageInput.disabled = false;
            sendBtn.disabled = false;
            alert('Lỗi kết nối mạng');
        };
        
        xhr.send(JSON.stringify({ message: message }));
    });

    messageInput.addEventListener('input', function() {
        sendBtn.disabled = this.value.trim() === '';
    });
    
    sendBtn.disabled = messageInput.value.trim() === '';

    function pollMessages() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/chat/' + conversationId + '/messages', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var messages = JSON.parse(xhr.responseText);
                    messages.forEach(function(msg) {
                        if (msg.id > lastMessageId && !msg.is_mine) {
                            addMessage(msg, false);
                        }
                        if (msg.id > lastMessageId) {
                            lastMessageId = msg.id;
                        }
                    });
                } catch (e) {
                    console.error('Poll parse error:', e);
                }
            }
        };
        
        xhr.send();
    }

    setInterval(pollMessages, 3000);
    messageInput.focus();
})();
</script>
@endpush
