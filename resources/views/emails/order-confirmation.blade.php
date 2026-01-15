<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0 10px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .items-table thead {
            background-color: #667eea;
            color: #fff;
        }
        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .items-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .items-table .text-right {
            text-align: right;
        }
        .total-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f0f4ff;
            border-radius: 5px;
            text-align: right;
        }
        .total-row {
            display: flex;
            justify-content: flex-end;
            padding: 8px 0;
            font-size: 14px;
        }
        .total-row.main {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            border-top: 2px solid #667eea;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #cfe2ff;
            color: #084298;
        }
        .status-shipped {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-delivered {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #842029;
        }
        .cta-button {
            display: inline-block;
            background-color: #667eea;
            color: #fff;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #5568d3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Đơn hàng xác nhận</h1>
            <p>Cảm ơn bạn đã mua hàng tại ShopOnline</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Đơn hàng của bạn đã được xác nhận và sẽ sớm được xử lý. Dưới đây là chi tiết đơn hàng của bạn:</p>

            <!-- Order Info -->
            <div class="section-title">📋 Thông tin đơn hàng</div>
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Mã đơn hàng:</span>
                    <span class="value">#{{ $order->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Ngày đặt hàng:</span>
                    <span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Trạng thái:</span>
                    <span>
                        @php
                            $statusClass = 'status-' . $order->status;
                            $statusText = match($order->status) {
                                'pending' => 'Chờ xử lý',
                                'processing' => 'Đang xử lý',
                                'shipped' => 'Đã gửi',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Hủy',
                                default => $order->status
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="section-title">📦 Sản phẩm đã đặt</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-right">Số lượng</th>
                        <th class="text-right">Giá</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name ?? 'Sản phẩm không xác định' }}</strong>
                            @if($item->product && $item->product->sku)
                                <br><small style="color: #999;">SKU: {{ $item->product->sku }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                        <td class="text-right" style="font-weight: bold;">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} ₫</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total -->
            <div class="total-section">
                @php
                    $subtotal = $order->items->sum(fn($item) => $item->quantity * $item->price);
                    $shipping = $order->shipping_fee ?? 0;
                    $discount = $order->discount ?? 0;
                    $total = $subtotal + $shipping - $discount;
                @endphp
                <div class="total-row">
                    <span>Tổng cộng:</span>
                    <span style="margin-left: 20px;">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                </div>
                @if($shipping > 0)
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span style="margin-left: 20px;">{{ number_format($shipping, 0, ',', '.') }} ₫</span>
                </div>
                @endif
                @if($discount > 0)
                <div class="total-row">
                    <span>Giảm giá:</span>
                    <span style="margin-left: 20px; color: green;">-{{ number_format($discount, 0, ',', '.') }} ₫</span>
                </div>
                @endif
                <div class="total-row main">
                    <span>Tổng thanh toán:</span>
                    <span style="margin-left: 20px;">{{ number_format($total, 0, ',', '.') }} ₫</span>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="section-title">🚚 Địa chỉ giao hàng</div>
            <div class="info-box">
                <div class="info-row">
                    <span class="label">Người nhận:</span>
                    <span class="value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Điện thoại:</span>
                    <span class="value">{{ $order->customer_phone ?? $order->user->phone ?? 'Chưa cập nhật' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Địa chỉ:</span>
                    <span class="value">{{ $order->shipping_address ?? $order->user->address ?? 'Chưa cập nhật' }}</span>
                </div>
            </div>

            <!-- Action Button -->
            <center>
                <a href="{{ env('APP_URL') }}/orders/{{ $order->id }}" class="cta-button">
                    Xem chi tiết đơn hàng
                </a>
            </center>

            <!-- Next Steps -->
            <div class="section-title">📌 Bước tiếp theo</div>
            <p>
                ✓ Đơn hàng của bạn đang được chuẩn bị để gửi<br>
                ✓ Bạn sẽ nhận được email khi hàng được gửi<br>
                ✓ Kiểm tra trạng thái đơn hàng bất cứ lúc nào tại website của chúng tôi<br>
                ✓ Nếu có bất kỳ câu hỏi nào, hãy liên hệ với chúng tôi
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>ShopOnline</strong> - Mua sắm trực tuyến</p>
            <p>Email: contact@shoponline.vn | Điện thoại: 1900 1234</p>
            <p>© {{ date('Y') }} ShopOnline. Tất cả quyền được bảo lưu.</p>
            <p style="font-size: 11px; color: #999;">Đây là email tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>
