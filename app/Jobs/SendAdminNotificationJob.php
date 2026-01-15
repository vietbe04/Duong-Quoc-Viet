<?php

namespace App\Jobs;

use App\Mail\NewOrderNotification;
use App\Models\EmailLog;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Send Admin Notification Job - Gửi thông báo đơn hàng mới cho admin
 */
class SendAdminNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 3;
    public $backoff = [10, 30, 60];
    public $priority = 10;

    protected Order $order;
    protected string $adminEmail;

    public function __construct(int $orderId, string $adminEmail)
    {
        $this->onQueue('emails');
        $this->order = Order::with('items')->findOrFail($orderId);
        $this->adminEmail = $adminEmail;
    }

    public function handle(): void
    {
        $emailLog = EmailLog::create([
            'email' => $this->adminEmail,
            'subject' => "Thông báo đơn hàng mới #{$this->order->order_number}",
            'status' => 'pending',
        ]);

        try {
            Log::channel('email')->info('Gửi thông báo đơn hàng mới cho admin', [
                'order_id' => $this->order->id,
                'admin_email' => $this->adminEmail,
            ]);

            Mail::to($this->adminEmail)->send(new NewOrderNotification($this->order));

            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::channel('email')->info('Thông báo admin gửi thành công', [
                'order_id' => $this->order->id,
                'admin_email' => $this->adminEmail,
            ]);

        } catch (Throwable $e) {
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::channel('email')->error('Thông báo admin gửi thất bại', [
                'order_id' => $this->order->id,
                'admin_email' => $this->adminEmail,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        EmailLog::where('email', $this->adminEmail)
            ->latest()
            ->first()
            ?->update([
                'status' => 'failed_permanently',
                'error_message' => $e->getMessage(),
            ]);

        Log::channel('email')->critical('Thông báo admin thất bại vĩnh viễn', [
            'order_id' => $this->order->id,
            'admin_email' => $this->adminEmail,
            'error' => $e->getMessage(),
        ]);
    }
}
