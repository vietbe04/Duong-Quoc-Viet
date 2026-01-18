<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Hiển thị danh sách conversations cho Admin
     */
    public function index()
    {
        if (!hasPermission('view-chats')) {
            abort(403, 'Bạn không có quyền xem tin nhắn chat.');
        }

        $conversations = Conversation::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.chat.index', compact('conversations'));
    }

    /**
     * Hiển thị conversation cụ thể
     */
    public function show(Conversation $conversation)
    {
        if (!hasPermission('view-chats')) {
            abort(403, 'Bạn không có quyền xem tin nhắn chat.');
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Đánh dấu là admin đang phụ trách
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => Auth::id()]);
        }

        // Đánh dấu tin nhắn từ user là đã đọc
        $conversation->messages()
            ->where('user_id', $conversation->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Lấy danh sách conversations để hiển thị sidebar
        $conversations = Conversation::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.chat.show', compact('conversation', 'messages', 'conversations'));
    }

    /**
     * Lấy danh sách tin nhắn (API)
     */
    public function getMessages(Conversation $conversation)
    {
        $admin = Auth::user();
        
        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($admin) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'message' => $message->message,
                    'type' => $message->type,
                    'attachment' => $message->attachment,
                    'is_from_admin' => $message->isFromAdmin(),
                    'is_mine' => $message->user_id === $admin->id,
                    'created_at' => $message->created_at->format('H:i'),
                    'created_at_full' => $message->created_at->format('d/m/Y H:i'),
                ];
            });

        // Đánh dấu tin nhắn từ user là đã đọc
        $conversation->messages()
            ->where('user_id', $conversation->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Gửi tin nhắn từ Admin
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        if (!hasPermission('reply-chats')) {
            return response()->json(['error' => 'Không có quyền'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $admin = Auth::user();

        // Tạo tin nhắn mới
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'message' => $request->message,
            'type' => 'text',
        ]);

        // Cập nhật thời gian tin nhắn cuối và admin phụ trách
        $conversation->update([
            'last_message_at' => now(),
            'admin_id' => $admin->id,
        ]);

        // Load relationship
        $message->load('user');

        // Broadcast event (chỉ khi có cấu hình broadcasting)
        if (config('broadcasting.default') !== 'null') {
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Broadcasting failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'user_name' => $message->user->name,
                'message' => $message->message,
                'type' => $message->type,
                'is_from_admin' => true,
                'is_mine' => true,
                'created_at' => $message->created_at->format('H:i'),
                'created_at_full' => $message->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }

    /**
     * Đóng conversation
     */
    public function close(Conversation $conversation)
    {
        if (!hasPermission('manage-chats')) {
            return response()->json(['error' => 'Không có quyền'], 403);
        }

        $conversation->update(['status' => 'closed']);

        return response()->json(['success' => true, 'message' => 'Đã đóng cuộc hội thoại']);
    }

    /**
     * Mở lại conversation
     */
    public function reopen(Conversation $conversation)
    {
        if (!hasPermission('manage-chats')) {
            return response()->json(['error' => 'Không có quyền'], 403);
        }

        $conversation->update(['status' => 'open']);

        return response()->json(['success' => true, 'message' => 'Đã mở lại cuộc hội thoại']);
    }

    /**
     * Lấy số tin nhắn chưa đọc
     */
    public function unreadCount()
    {
        $count = Message::whereHas('conversation', function ($query) {
            $query->where('status', 'open');
        })
            ->whereHas('user', function ($query) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['admin', 'super-admin']);
                });
            })
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
