<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
            color: #333333;
        }
        .content h2 {
            color: #667eea;
            margin-top: 0;
        }
        .content p {
            line-height: 1.6;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background-color: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #764ba2;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #666666;
            font-size: 12px;
            border-top: 1px solid #eeeeee;
        }
        .highlight {
            color: #667eea;
            font-weight: bold;
        }
        ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Chào mừng bạn!</h1>
        </div>
        
        <div class="content">
            <h2>Xin chào {{ $user->name }},</h2>
            
            <p>Chúng tôi rất vui khi bạn đã đăng ký thành công tài khoản tại <span class="highlight">ShopOnline</span>!</p>
            
            <p>Dưới đây là thông tin tài khoản của bạn:</p>
            <ul>
                <li><strong>Họ và tên:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</li>
                <li><strong>Ngày đăng ký:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
            </ul>
            
            <p>Tại ShopOnline, bạn có thể:</p>
            <ul>
                <li>✓ Mua sắm hàng nghìn sản phẩm chất lượng</li>
                <li>✓ Quản lý đơn hàng dễ dàng</li>
                <li>✓ Nhận thông báo về khuyến mãi và sản phẩm mới</li>
                <li>✓ Ghi lưu trang sản phẩm yêu thích</li>
            </ul>
            
            <p>Hãy bắt đầu khám phá cộng đồng mua sắm của chúng tôi ngay hôm nay!</p>
            
            <center>
                <a href="{{ route('home') }}" class="button">Truy cập ShopOnline</a>
            </center>
            
            <p style="margin-top: 30px; font-size: 13px; color: #999999;">
                Nếu bạn gặp bất kỳ vấn đề nào, vui lòng liên hệ với đội hỗ trợ khách hàng của chúng tôi.
            </p>
        </div>
        
        <div class="footer">
            <p>© 2026 ShopOnline. Tất cả quyền được bảo lưu.</p>
            <p>Email này được gửi tới {{ $user->email }} vì bạn đã đăng ký tài khoản tại ShopOnline</p>
        </div>
    </div>
</body>
</html>
