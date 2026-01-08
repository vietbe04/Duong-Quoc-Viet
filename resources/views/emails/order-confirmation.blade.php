<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .order-info { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .items-table th, .items-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .items-table th { background: #f5f5f5; }
        .total { font-weight: bold; font-size: 18px; color: #007bff; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xác nhận đơn hàng</h1>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
            
            <div class="order-info">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method_label }}</p>
                <p><strong>Trạng thái:</strong> {{ $order->status_label }}</p>
            </div>
            
            <div class="order-info">
                <h3>Thông tin giao hàng</h3>
                <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                @if($order->notes)
                <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                @endif
            </div>
            
            <h3>Chi tiết đơn hàng</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->total, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Tạm tính:</strong></td>
                        <td>{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Phí vận chuyển:</strong></td>
                        <td>{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Giảm giá:</strong></td>
                        <td>-{{ number_format($order->discount, 0, ',', '.') }} đ</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="text-align: right;" class="total">Tổng cộng:</td>
                        <td class="total">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                    </tr>
                </tfoot>
            </table>
            
            <p>Nếu bạn có bất kỳ câu hỏi nào về đơn hàng, vui lòng liên hệ với chúng tôi.</p>
            <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>
