<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;      // 5 phút
    public $tries = 3;          // Retry 3 lần
    public $backoff = [10, 30, 60]; // Retry delay: 10s, 30s, 60s
    public $priority = 10;      // Ưu tiên cao

    protected User $user;

    public function __construct(int $userId)
    {
        $this->onQueue('emails');
        $this->user = User::findOrFail($userId);
    }

    /**
     * Execute the job
     */
    public function handle(): void
    {
        $emailLog = EmailLog::create([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'subject' => 'Chào mừng đến với ShopOnline!',
            'status' => 'pending',
            'attempts' => 0,
        ]);

        try {
            Log::channel('email')->info('Bắt đầu gửi email chào mừng', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
            ]);

            Mail::to($this->user->email)->send(new WelcomeEmail($this->user));

            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now(),
                'attempts' => $this->attempts(),
            ]);

            Log::channel('email')->info('Email chào mừng gửi thành công', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
            ]);

        } catch (Throwable $e) {
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'attempts' => $this->attempts(),
            ]);

            Log::channel('email')->error('Email chào mừng gửi thất bại', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle job failed completely
     */
    public function failed(Throwable $e): void
    {
        EmailLog::where('user_id', $this->user->id)
            ->where('email', $this->user->email)
            ->latest()
            ->first()
            ?->update([
                'status' => 'failed_permanently',
                'error_message' => "Job thất bại sau {$this->tries} lần thử: {$e->getMessage()}",
            ]);

        Log::channel('email')->critical('Email chào mừng thất bại vĩnh viễn', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'error' => $e->getMessage(),
        ]);
    }
}
