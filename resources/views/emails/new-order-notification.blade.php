<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng mới</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .order-info { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .items-table th, .items-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .items-table th { background: #f5f5f5; }
        .total { font-weight: bold; font-size: 18px; color: #28a745; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Đơn hàng mới!</h1>
        </div>
        
        <div class="content">
            <p><strong>Có đơn hàng mới cần xử lý!</strong></p>
            
            <div class="order-info">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method_label }}</p>
                <p><strong>Tổng tiền:</strong> <span class="total">{{ number_format($order->total, 0, ',', '.') }} đ</span></p>
            </div>
            
            <div class="order-info">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
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
            </table>
            
            <p style="text-align: center; margin-top: 20px;">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">Xem chi tiết đơn hàng</a>
            </p>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống quản trị.</p>
        </div>
    </div>
</body>
</html>
