@echo off
echo ========================================
echo    DEPLOY APLIKASI AMPRAHAN
echo ========================================
echo.

echo Memeriksa Docker...

REM Check if Docker is installed
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker tidak ditemukan!
    echo Silakan install Docker Desktop terlebih dahulu.
    pause
    exit /b 1
)

REM Check if Docker Compose is available
docker-compose --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker Compose tidak ditemukan!
    echo Silakan install Docker Compose terlebih dahulu.
    pause
    exit /b 1
)

echo ✓ Docker ditemukan
echo ✓ Docker Compose ditemukan
echo.

echo Memulai deployment dengan Docker Compose...
docker-compose up -d

if %errorlevel% neq 0 (
    echo.
    echo ERROR: Deployment gagal!
    pause
    exit /b 1
)

echo.
echo ========================================
echo    DEPLOYMENT SELESAI!
echo ========================================
echo.
echo Aplikasi berhasil di-deploy!
echo.
echo Akses aplikasi:
echo - Frontend: http://localhost:3000
echo - Backend API: http://localhost:8000
echo - Database: localhost:3306
echo.
echo Default admin account:
echo - Username: admin
echo - Password: admin123
echo.
echo Perintah Docker yang berguna:
echo - docker-compose logs -f    (lihat log)
echo - docker-compose down       (hentikan aplikasi)
echo - docker-compose restart    (restart aplikasi)
echo.
echo Tekan tombol apa saja untuk keluar...
pause >nul 