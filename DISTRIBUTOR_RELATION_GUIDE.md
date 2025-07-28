# Panduan Relasi Distributor-User

## Overview

Sistem ini telah diperbarui untuk membuat relasi yang tepat antara tabel `distributors` dan tabel `users` dengan role distributor. Relasi ini memungkinkan manajemen distributor yang lebih terstruktur dan konsisten.

## Struktur Database

### Tabel `distributors`

```sql
CREATE TABLE distributors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,                    -- Foreign key ke users.id
    nama VARCHAR(255) NOT NULL,
    perusahaan VARCHAR(255),
    kontak VARCHAR(100),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
);
```

### Tabel `users` (Updated)

```sql
ALTER TABLE users ADD COLUMN distributor_id INT NULL;
ALTER TABLE users ADD CONSTRAINT fk_users_distributor
    FOREIGN KEY (distributor_id) REFERENCES distributors(id)
    ON DELETE SET NULL ON UPDATE CASCADE;
```

## Relasi Database

### Relasi One-to-One

- Setiap distributor memiliki satu user account
- Setiap user dengan role 'distributor' memiliki satu record distributor
- Relasi bidirectional: `distributors.user_id` ↔ `users.distributor_id`

### Foreign Key Constraints

1. **distributors.user_id** → **users.id**

   - ON DELETE SET NULL: Jika user dihapus, distributor tetap ada tapi user_id jadi NULL
   - ON UPDATE CASCADE: Jika user.id berubah, distributor.user_id ikut berubah

2. **users.distributor_id** → **distributors.id**
   - ON DELETE SET NULL: Jika distributor dihapus, user tetap ada tapi distributor_id jadi NULL
   - ON UPDATE CASCADE: Jika distributor.id berubah, user.distributor_id ikut berubah

## Setup Database

### 1. Jalankan Script SQL

```bash
# Buka phpMyAdmin atau MySQL client
mysql -u root -p amprahan < backend/create_distributor_relation.sql
```

### 2. Verifikasi Struktur

```sql
-- Cek struktur tabel
DESCRIBE distributors;
DESCRIBE users;

-- Cek relasi yang sudah dibuat
SELECT
    d.id as distributor_id,
    d.nama as distributor_nama,
    u.id as user_id,
    u.username,
    u.role
FROM distributors d
LEFT JOIN users u ON d.user_id = u.id
ORDER BY d.id;
```

## API Endpoints

### Distributor Management API (`/backend/distributors.php`)

#### GET /distributors

Mengambil semua distributor dengan informasi user

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nama": "John Doe",
      "perusahaan": "PT Maju Jaya",
      "kontak": "08123456789",
      "alamat": "Jl. Sudirman No. 123",
      "user_id": 5,
      "username": "johndoe",
      "role": "distributor"
    }
  ]
}
```

#### POST /distributors

Membuat distributor baru dengan user account

```json
{
  "username": "newdistributor",
  "password": "password123",
  "nama": "Jane Smith",
  "perusahaan": "PT Sukses Mandiri",
  "kontak": "08123456789",
  "alamat": "Jl. Thamrin No. 456"
}
```

#### PUT /distributors/{id}

Update distributor (password opsional)

```json
{
  "nama": "Jane Smith Updated",
  "perusahaan": "PT Sukses Mandiri Baru",
  "kontak": "08123456789",
  "alamat": "Jl. Thamrin No. 789",
  "password": "newpassword123" // Opsional
}
```

#### DELETE /distributors/{id}

Hapus distributor dan user account terkait

## Frontend Implementation

### API Functions (`/frontend/src/api/distributors.js`)

```javascript
// Get all distributors
export const getAllDistributors = async () => { ... };

// Create new distributor
export const createDistributor = async (distributorData) => { ... };

// Update distributor
export const updateDistributor = async (id, distributorData) => { ... };

// Delete distributor
export const deleteDistributor = async (id) => { ... };
```

### Distributor Management Page

- Menggunakan endpoint `/distributors.php` yang baru
- Menampilkan data distributor dengan informasi user
- CRUD operations dengan validasi

## Workflow Distributor

### 1. Pembuatan Distributor Baru

1. Admin mengisi form distributor (username, password, nama, perusahaan, dll)
2. Sistem membuat user account dengan role 'distributor'
3. Sistem membuat record distributor
4. Sistem menghubungkan kedua record dengan foreign key

### 2. Login Distributor

1. Distributor login menggunakan username/password dari user account
2. Sistem mengecek role = 'distributor'
3. Sistem mengambil data distributor terkait

### 3. Update Distributor

1. Admin dapat update data distributor
2. Password update bersifat opsional
3. Data user dan distributor diupdate secara konsisten

### 4. Hapus Distributor

1. Admin menghapus distributor
2. Sistem menghapus user account terkait
3. Sistem menghapus record distributor

## Keuntungan Relasi Ini

### 1. Data Consistency

- Satu sumber kebenaran untuk data distributor
- Relasi yang jelas antara user account dan data distributor

### 2. Security

- Password dan authentication terpisah dari data bisnis
- Role-based access control yang jelas

### 3. Flexibility

- Distributor bisa ada tanpa user account (untuk data historis)
- User account bisa ada tanpa distributor (untuk kasus khusus)

### 4. Maintainability

- Struktur database yang normal
- Mudah untuk backup dan restore
- Mudah untuk scaling

## Troubleshooting

### Error: Foreign Key Constraint Failed

```sql
-- Cek data yang tidak konsisten
SELECT u.id, u.username, u.role, u.distributor_id, d.id as dist_id, d.user_id
FROM users u
LEFT JOIN distributors d ON u.distributor_id = d.id
WHERE u.role = 'distributor' AND (u.distributor_id IS NULL OR d.user_id != u.id);
```

### Error: Duplicate Entry

```sql
-- Cek username yang duplikat
SELECT username, COUNT(*) as count
FROM users
WHERE role = 'distributor'
GROUP BY username
HAVING count > 1;
```

### Data Migration Issues

```sql
-- Reset relasi jika ada masalah
UPDATE users SET distributor_id = NULL WHERE role = 'distributor';
UPDATE distributors SET user_id = NULL;

-- Rebuild relasi
UPDATE users u
JOIN distributors d ON u.id = d.user_id
SET u.distributor_id = d.id
WHERE u.role = 'distributor';
```

## Best Practices

### 1. Data Validation

- Selalu validasi input sebelum insert/update
- Gunakan transaction untuk operasi yang melibatkan multiple tabel

### 2. Error Handling

- Tangani error foreign key constraint
- Berikan feedback yang jelas ke user

### 3. Performance

- Gunakan index pada foreign key columns
- Optimasi query dengan JOIN yang tepat

### 4. Security

- Hash password sebelum disimpan
- Validasi role dan permission
- Sanitasi input untuk mencegah SQL injection
