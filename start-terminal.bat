@echo off
REM Script chạy Laravel Development Server + Queue Worker + Redis Server
REM Chạy Queue Worker ở background, Laravel Dev Server ở foreground

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
    start /B "" "C:\xampp\redis\redis-server.exe" --port 6379
    timeout /t 2 /nobreak
    echo Redis Server đã khởi động
) else (
    echo Redis Server đang chạy
)

echo.
echo Khởi động Queue Worker ở background...
start /B php artisan queue:work --queue=emails --tries=3 --timeout=300 --memory=128 --sleep=3
timeout /t 1

echo ====================================
echo Laravel Development Server
echo http://localhost:8000
echo ====================================
echo Queue Worker chạy ở background
echo Nhấn Ctrl+C để dừng
echo.

REM Chạy Laravel serve ở foreground
php artisan serve --host=localhost --port=8000

