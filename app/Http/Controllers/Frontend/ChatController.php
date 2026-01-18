<?php

namespace App\Http\Controllers\Frontend;

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
     * Hiển thị trang chat cho khách hàng
     */
    public function index()
    {
        $user = Auth::user();
        
        // Lấy hoặc tạo conversation cho user
        $conversation = Conversation::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'open'],
            ['title' => 'Chat với ' . $user->name]
        );

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('frontend.chat.index', compact('conversation', 'messages'));
    }

    /**
     * Lấy danh sách tin nhắn
     */
    public function getMessages(Conversation $conversation)
    {
        $user = Auth::user();
        
        // Kiểm tra quyền truy cập
        if ($conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($user) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'message' => $message->message,
                    'type' => $message->type,
                    'attachment' => $message->attachment,
                    'is_from_admin' => $message->isFromAdmin(),
                    'is_mine' => $message->user_id === $user->id,
                    'created_at' => $message->created_at->format('H:i'),
                    'created_at_full' => $message->created_at->format('d/m/Y H:i'),
                ];
            });

        // Đánh dấu tin nhắn từ admin là đã đọc
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Gửi tin nhắn
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Kiểm tra quyền
        if ($conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tạo tin nhắn mới
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'type' => 'text',
        ]);

        // Cập nhật thời gian tin nhắn cuối
        $conversation->update(['last_message_at' => now()]);

        // Load relationship
        $message->load('user');

        // Broadcast event (chỉ khi có cấu hình broadcasting)
        if (config('broadcasting.default') !== 'null') {
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                // Log error but don't fail the request
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
                'is_from_admin' => false,
                'is_mine' => true,
                'created_at' => $message->created_at->format('H:i'),
                'created_at_full' => $message->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }
}
