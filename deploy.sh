#!/bin/bash

echo "========================================"
echo "    DEPLOY APLIKASI AMPRAHAN"
echo "========================================"
echo

echo "Memeriksa Docker..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "ERROR: Docker tidak ditemukan!"
    echo "Silakan install Docker terlebih dahulu."
    exit 1
fi

# Check if Docker Compose is available
if ! command -v docker-compose &> /dev/null; then
    echo "ERROR: Docker Compose tidak ditemukan!"
    echo "Silakan install Docker Compose terlebih dahulu."
    exit 1
fi

echo "✓ Docker ditemukan"
echo "✓ Docker Compose ditemukan"
echo

echo "Memulai deployment dengan Docker Compose..."
docker-compose up -d

if [ $? -ne 0 ]; then
    echo
    echo "ERROR: Deployment gagal!"
    exit 1
fi

echo
echo "========================================"
echo "    DEPLOYMENT SELESAI!"
echo "========================================"
echo
echo "Aplikasi berhasil di-deploy!"
echo
echo "Akses aplikasi:"
echo "- Frontend: http://localhost:3000"
echo "- Backend API: http://localhost:8000"
echo "- Database: localhost:3306"
echo
echo "Default admin account:"
echo "- Username: admin"
echo "- Password: admin123"
echo
echo "Perintah Docker yang berguna:"
echo "- docker-compose logs -f    (lihat log)"
echo "- docker-compose down       (hentikan aplikasi)"
echo "- docker-compose restart    (restart aplikasi)"
echo 