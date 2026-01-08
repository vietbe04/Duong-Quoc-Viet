<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Không có quyền truy cập</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        .error-page {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f6f9;
        }
        .error-content {
            text-align: center;
            padding: 20px;
        }
        .error-content h1 {
            font-size: 5rem;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .error-content h4 {
            color: #6c757d;
            margin-bottom: 20px;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="error-page">
        <div class="error-content">
            <h1><i class="fas fa-exclamation-triangle text-warning"></i> 403</h1>
            <h4>Không có quyền truy cập</h4>
            <p>{{ $message ?? 'Bạn không có quyền truy cập vào trang này.' }}</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-back">
                <i class="fas fa-home mr-2"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>
</body>
</html>