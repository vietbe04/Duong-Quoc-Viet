@echo off
REM Script chạy Laravel Development Server + Queue Worker + Redis Server
REM Chạy tất cả trong các cửa sổ riêng biệt

echo ====================================
echo Laravel Development Environment
echo ====================================
echo Thời gian bắt đầu: %date% %time%
echo.

REM Di chuyển tới thư mục project
cd /d C:\xampp\htdocs\Duong-Quoc-Viet

REM Kiểm tra Redis đã chạy chưa
tasklist | findstr /i "redis-server" >nul
if errorlevel 1 (
    echo Khởi động Redis Server...
    start "Redis Server" "C:\xampp\redis\redis-server.exe" --port 6379
    timeout /t 3 /nobreak
    echo Redis Server đã khởi động
) else (
    echo Redis Server đang chạy
)
echo.

REM Khởi động Laravel Development Server
echo Khởi động Laravel Development Server...
start "Laravel Dev Server" php artisan serve --host=localhost --port=8000
timeout /t 2

REM Khởi động Queue Worker
echo Khởi động Laravel Queue Worker...
start "Queue Worker" php artisan queue:work --queue=emails --tries=3 --timeout=300 --memory=128 --sleep=3

echo.
echo ====================================
echo Tất cả dịch vụ đã được khởi động!
echo - Laravel Dev Server: http://localhost:8000
echo - Redis Server: Port 6379
echo - Queue Worker: Listening to emails queue
echo ====================================
echo.
echo Nhấn phím bất kỳ để đóng cửa sổ này...
pause
