<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationEmail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $order = Order::with(['user', 'items.product'])->find($this->orderId);

            if (!$order) {
                Log::error("SendOrderConfirmationEmailJob: Không tìm thấy đơn hàng #{$this->orderId}");
                return;
            }

            // Lấy email từ customer_email
            $email = $order->customer_email;
            
            if (!$email) {
                Log::error("SendOrderConfirmationEmailJob: Đơn hàng #{$this->orderId} không có email");
                return;
            }

            Log::info("Bắt đầu gửi email xác nhận đơn hàng #{$order->order_number} đến {$email}");

            Mail::to($email)->send(new OrderConfirmationEmail($order));

            Log::info("Đã gửi email xác nhận đơn hàng #{$order->order_number} đến {$email}");

        } catch (\Exception $e) {
            Log::error("SendOrderConfirmationEmailJob failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SendOrderConfirmationEmailJob failed permanently for order #{$this->orderId}: " . $exception->getMessage());
    }
}
