# Aplikasi Amprahan

Aplikasi manajemen permintaan barang untuk kantin dengan sistem role admin, user, dan distributor.

## Persyaratan Sistem

- **PHP** 7.4 atau lebih tinggi
- **Node.js** 14 atau lebih tinggi
- **MySQL** 5.7 atau lebih tinggi
- **Apache** (XAMPP/WAMP/LAMP)
- **Composer** (opsional, untuk dependency management)

## Instalasi Cepat

### Windows

1. Pastikan XAMPP sudah terinstall dan berjalan
2. Double click file `install.bat`
3. Ikuti instruksi yang muncul

### Linux/Mac

1. Pastikan Apache, MySQL, dan PHP sudah terinstall
2. Buka terminal di folder aplikasi
3. Jalankan: `chmod +x install.sh && ./install.sh`

## Instalasi Manual

### 1. Setup Database

```bash
php setup.php
```

### 2. Setup Frontend

```bash
cd frontend
npm install
npm start
```

### 3. Akses Aplikasi

- Frontend: http://localhost:3000
- Backend API: http://localhost/amprahan/backend/

## Struktur Aplikasi

```
amprahan/
├── backend/                 # Backend PHP API
│   ├── src/
│   │   ├── config/         # Konfigurasi database
│   │   ├── controllers/    # Controller untuk API
│   │   ├── models/         # Model data
│   │   └── routes/         # Routing
│   └── index.php           # Entry point API
├── frontend/               # Frontend React
│   ├── src/
│   │   ├── components/     # React components
│   │   ├── pages/         # Halaman aplikasi
│   │   └── api/           # API calls
│   └── package.json
├── database/              # File SQL database
├── setup.php             # Script setup database
├── install.bat           # Script install Windows
├── install.sh            # Script install Linux/Mac
└── README.md
```

## Fitur Aplikasi

### Role Admin

- Manajemen user dan distributor
- Monitoring semua request
- Laporan lengkap
- Manajemen barang

### Role User

- Request barang
- Lihat status request
- Riwayat request

### Role Distributor

- Lihat request yang ditugaskan
- Update status request
- Laporan distribusi

## Default Account

**Admin:**

- Username: `admin`
- Password: `admin123`

## API Endpoints

### Authentication

- `POST /backend/login` - Login user
- `POST /backend/register` - Register user baru

### Barang

- `GET /backend/barang` - Ambil semua barang
- `POST /backend/barang` - Tambah barang baru
- `PUT /backend/barang/{id}` - Update barang
- `DELETE /backend/barang/{id}` - Hapus barang

### Request

- `GET /backend/request` - Ambil semua request
- `POST /backend/request` - Buat request baru
- `PUT /backend/request/{id}` - Update status request

### Laporan

- `GET /backend/laporan` - Ambil laporan

## Troubleshooting

### Error Database Connection

1. Pastikan MySQL berjalan
2. Periksa konfigurasi di `backend/src/config/database.php`
3. Pastikan database `amprahan` sudah dibuat

### Error Frontend

1. Pastikan Node.js terinstall
2. Jalankan `npm install` di folder frontend
3. Periksa apakah port 3000 sudah digunakan

### Error API

1. Pastikan Apache berjalan
2. Periksa file `.htaccess` di folder backend
3. Pastikan mod_rewrite Apache aktif

## Development

### Menambah Fitur Baru

1. Buat controller baru di `backend/src/controllers/`
2. Tambah routing di `backend/index.php`
3. Buat komponen React di `frontend/src/components/`
4. Tambah halaman di `frontend/src/pages/`

### Database Migration

1. Edit file SQL di folder `database/`
2. Jalankan ulang `setup.php` untuk reset database
3. Atau jalankan query manual di MySQL

## Support

Untuk bantuan lebih lanjut, silakan hubungi tim development.
