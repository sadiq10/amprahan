@echo off
echo ========================================
echo    SETUP APLIKASI AMPRAHAN
echo ========================================
echo.

echo Memeriksa persyaratan sistem...

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP tidak ditemukan!
    echo Silakan install XAMPP atau PHP terlebih dahulu.
    pause
    exit /b 1
)

REM Check if Node.js is installed
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Node.js tidak ditemukan!
    echo Silakan install Node.js terlebih dahulu dari https://nodejs.org/
    pause
    exit /b 1
)

echo ✓ PHP ditemukan
echo ✓ Node.js ditemukan
echo.

echo Memulai setup database...
php setup.php

if %errorlevel% neq 0 (
    echo.
    echo ERROR: Setup database gagal!
    pause
    exit /b 1
)

echo.
echo Setup frontend dependencies...
cd frontend
npm install

if %errorlevel% neq 0 (
    echo.
    echo ERROR: Install dependencies frontend gagal!
    pause
    exit /b 1
)

cd ..

echo.
echo ========================================
echo    SETUP SELESAI!
echo ========================================
echo.
echo Langkah selanjutnya:
echo 1. Pastikan XAMPP/Apache dan MySQL berjalan
echo 2. Buka terminal baru dan jalankan:
echo    cd frontend
echo    npm start
echo 3. Akses aplikasi di: http://localhost:3000
echo 4. Login dengan:
echo    Username: admin
echo    Password: admin123
echo.
echo Tekan tombol apa saja untuk keluar...
pause >nul 