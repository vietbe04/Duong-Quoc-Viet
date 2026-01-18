<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderConfirmationEmailJob;
use App\Jobs\SendAdminNotificationJob;
use App\Models\Order;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Tạo thanh toán VNPay
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        // Kiểm tra đơn hàng đã thanh toán chưa
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Đơn hàng này đã được thanh toán!');
        }

        $paymentData = [
            'order_id' => $order->order_number,
            'order_info' => 'Thanh toan don hang ' . $order->order_number,
            'amount' => (int) $order->total,
            'ip_address' => $request->ip(),
        ];

        $paymentUrl = $this->vnpayService->createPaymentUrl($paymentData);

        return redirect()->away($paymentUrl);
    }

    /**
     * Xử lý kết quả trả về từ VNPay
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function return(Request $request)
    {
        $vnpayData = $request->all();
        $result = $this->vnpayService->validateReturn($vnpayData);

        Log::info('VNPay Return', $result);

        if (!$result['is_valid']) {
            return view('frontend.vnpay.result', [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ! Giao dịch bị nghi ngờ giả mạo.',
                'order' => null,
            ]);
        }

        // Tìm đơn hàng theo order_number
        $order = Order::where('order_number', $result['order_id'])->first();

        if (!$order) {
            return view('frontend.vnpay.result', [
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng!',
                'order' => null,
            ]);
        }

        if ($this->vnpayService->isSuccessful($result['response_code'])) {
            // Cập nhật trạng thái thanh toán
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            // Gửi email xác nhận
            if (class_exists(SendOrderConfirmationEmailJob::class)) {
                SendOrderConfirmationEmailJob::dispatch($order->id);
            }
            
            // Gửi thông báo cho admin
            $adminEmail = config('mail.admin_email', config('mail.from.address'));
            if (class_exists(SendAdminNotificationJob::class) && $adminEmail) {
                SendAdminNotificationJob::dispatch($order->id, $adminEmail);
            }

            // Xóa giỏ hàng
            session()->forget('cart');

            return view('frontend.vnpay.result', [
                'success' => true,
                'message' => $result['message'],
                'order' => $order,
                'transaction_no' => $result['transaction_no'],
                'bank_code' => $result['bank_code'],
                'amount' => $result['amount'],
                'pay_date' => $result['pay_date'],
            ]);
        } else {
            return view('frontend.vnpay.result', [
                'success' => false,
                'message' => $result['message'],
                'order' => $order,
                'response_code' => $result['response_code'],
            ]);
        }
    }

    /**
     * IPN - Instant Payment Notification
     * VNPay sẽ gọi URL này để thông báo kết quả thanh toán
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ipn(Request $request)
    {
        $vnpayData = $request->all();
        $result = $this->vnpayService->validateReturn($vnpayData);

        Log::info('VNPay IPN', $result);

        if (!$result['is_valid']) {
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ]);
        }

        $order = Order::where('order_number', $result['order_id'])->first();

        if (!$order) {
            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found'
            ]);
        }

        // Kiểm tra số tiền
        if ((int) $order->total != (int) $result['amount']) {
            return response()->json([
                'RspCode' => '04',
                'Message' => 'Invalid amount'
            ]);
        }

        // Kiểm tra trạng thái đơn hàng đã được xử lý chưa
        if ($order->payment_status === 'paid') {
            return response()->json([
                'RspCode' => '02',
                'Message' => 'Order already confirmed'
            ]);
        }

        if ($this->vnpayService->isSuccessful($result['response_code'])) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ]);
        }

        return response()->json([
            'RspCode' => '00',
            'Message' => 'Confirm Success'
        ]);
    }
}
