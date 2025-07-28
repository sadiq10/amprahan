#!/bin/bash

echo "========================================"
echo "    SETUP APLIKASI AMPRAHAN"
echo "========================================"
echo

echo "Memeriksa persyaratan sistem..."

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "ERROR: PHP tidak ditemukan!"
    echo "Silakan install PHP terlebih dahulu."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "ERROR: Node.js tidak ditemukan!"
    echo "Silakan install Node.js terlebih dahulu dari https://nodejs.org/"
    exit 1
fi

echo "✓ PHP ditemukan"
echo "✓ Node.js ditemukan"
echo

echo "Memulai setup database..."
php setup.php

if [ $? -ne 0 ]; then
    echo
    echo "ERROR: Setup database gagal!"
    exit 1
fi

echo
echo "Setup frontend dependencies..."
cd frontend
npm install

if [ $? -ne 0 ]; then
    echo
    echo "ERROR: Install dependencies frontend gagal!"
    exit 1
fi

cd ..

echo
echo "========================================"
echo "    SETUP SELESAI!"
echo "========================================"
echo
echo "Langkah selanjutnya:"
echo "1. Pastikan Apache dan MySQL berjalan"
echo "2. Buka terminal baru dan jalankan:"
echo "   cd frontend"
echo "   npm start"
echo "3. Akses aplikasi di: http://localhost:3000"
echo "4. Login dengan:"
echo "   Username: admin"
echo "   Password: admin123"
echo 