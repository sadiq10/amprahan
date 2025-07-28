# Panduan Instalasi Aplikasi Amprahan

## Metode Instalasi

### 1. Instalasi Cepat (Recommended)

#### Windows

1. Install XAMPP dari https://www.apachefriends.org/
2. Install Node.js dari https://nodejs.org/
3. Copy folder aplikasi ke `C:\xampp\htdocs\amprahan`
4. Double click file `install.bat`
5. Ikuti instruksi yang muncul

#### Linux/Mac

1. Install Apache, MySQL, PHP, dan Node.js
2. Copy folder aplikasi ke `/var/www/html/amprahan`
3. Buka terminal di folder aplikasi
4. Jalankan: `chmod +x install.sh && ./install.sh`

### 2. Instalasi dengan Docker (Advanced)

#### Prerequisites

- Docker Desktop
- Docker Compose

#### Langkah Instalasi

1. Copy folder aplikasi ke komputer target
2. Buka terminal di folder aplikasi
3. Jalankan:

   ```bash
   # Windows
   deploy.bat

   # Linux/Mac
   chmod +x deploy.sh && ./deploy.sh
   ```

### 3. Instalasi Manual

#### Setup Database

```bash
# 1. Buat database MySQL
mysql -u root -p
CREATE DATABASE amprahan;
exit

# 2. Import schema
mysql -u root -p amprahan < database/amprahan_schema.sql

# 3. Import data awal
mysql -u root -p amprahan < database/add_users.sql
```

#### Setup Backend

```bash
# 1. Copy folder backend ke web server
cp -r backend /var/www/html/

# 2. Set permissions
chmod -R 755 /var/www/html/backend
chown -R www-data:www-data /var/www/html/backend

# 3. Konfigurasi database
# Edit file backend/src/config/database.php
```

#### Setup Frontend

```bash
# 1. Install dependencies
cd frontend
npm install

# 2. Build untuk production
npm run build

# 3. Copy build ke web server
cp -r build /var/www/html/amprahan-frontend
```

## Konfigurasi

### Environment Variables

Copy file `env.example` ke `.env` dan sesuaikan:

```bash
# Frontend
REACT_APP_API_URL=http://localhost/amprahan/backend

# Database
DB_HOST=localhost
DB_NAME=amprahan
DB_USER=root
DB_PASS=

# JWT Secret
JWT_SECRET=your-secret-key-here
```

### Web Server Configuration

#### Apache (.htaccess)

File `.htaccess` sudah dibuat otomatis oleh script setup.

#### Nginx

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/amprahan/backend;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Troubleshooting

### Error Database Connection

```
Connection error: SQLSTATE[HY000] [1045] Access denied
```

**Solusi:**

1. Periksa username dan password MySQL
2. Pastikan user memiliki akses ke database
3. Jalankan: `mysql -u root -p` untuk test koneksi

### Error Frontend Build

```
Module not found: Can't resolve 'react'
```

**Solusi:**

1. Jalankan: `npm install` di folder frontend
2. Hapus folder `node_modules` dan `package-lock.json`
3. Jalankan: `npm install` lagi

### Error API 404

```
404 Not Found
```

**Solusi:**

1. Pastikan mod_rewrite Apache aktif
2. Periksa file `.htaccess` di folder backend
3. Pastikan URL routing benar

### Error CORS

```
Access to fetch at 'http://localhost/backend' from origin 'http://localhost:3000' has been blocked by CORS policy
```

**Solusi:**

1. Periksa header CORS di backend
2. Pastikan domain frontend dan backend sesuai
3. Periksa konfigurasi `REACT_APP_API_URL`

## Maintenance

### Backup Database

```bash
# Backup
mysqldump -u root -p amprahan > backup_$(date +%Y%m%d).sql

# Restore
mysql -u root -p amprahan < backup_20231201.sql
```

### Update Aplikasi

1. Backup database
2. Copy file baru
3. Jalankan migration jika ada
4. Test aplikasi

### Log Files

- Apache: `/var/log/apache2/error.log`
- PHP: `/var/log/php_errors.log`
- React: Console browser

## Security

### Production Checklist

- [ ] Ganti password default
- [ ] Set JWT_SECRET yang kuat
- [ ] Enable HTTPS
- [ ] Set file permissions yang benar
- [ ] Backup database secara berkala
- [ ] Update dependencies secara rutin

### Firewall Configuration

```bash
# Allow HTTP/HTTPS
sudo ufw allow 80
sudo ufw allow 443

# Allow MySQL (jika remote)
sudo ufw allow 3306
```

## Support

Jika mengalami masalah:

1. Periksa log error
2. Pastikan semua persyaratan terpenuhi
3. Coba instalasi manual
4. Hubungi tim development

### Contact

- Email: support@amprahan.com
- Documentation: https://docs.amprahan.com
- Issues: https://github.com/amprahan/issues
