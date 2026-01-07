<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin người dùng</title>
</head>
<body>
@php
    $selected = collect($users)->firstWhere('id', (int)$id);
@endphp

@if($selected)
    <h1>Thông tin người dùng</h1>
    <p><strong>ID:</strong> {{ $selected['id'] }}</p>
    <p><strong>Họ và tên:</strong> {{ $selected['name'] }}</p>
    <p><strong>Giới tính:</strong> {{ $selected['gender'] }}</p>
@else
    <h1>Người dùng không tìm thấy</h1>
    <p>Không có thông tin cho ID: {{ $id }}</p>
@endif

</body>
</html>