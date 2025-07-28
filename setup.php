<?php
/**
 * Setup Script untuk Aplikasi Amprahan
 * Jalankan script ini untuk setup awal aplikasi
 */

echo "=== SETUP APLIKASI AMPRAHAN ===\n\n";

// Fungsi untuk membaca input user
function readInput($prompt) {
    echo $prompt;
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return trim($line);
}

// Fungsi untuk membuat file konfigurasi
function createConfigFile($host, $dbname, $username, $password) {
    $configContent = "<?php
// backend/src/config/database.php

class Database {
    private \$host = '{$host}';
    private \$db_name = '{$dbname}';
    private \$username = '{$username}';
    private \$password = '{$password}';
    public \$conn;

    public function getConnection() {
        \$this->conn = null;
        try {
            \$this->conn = new PDO(
                \"mysql:host=\" . \$this->host . \";dbname=\" . \$this->db_name,
                \$this->username,
                \$this->password
            );
            \$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException \$exception) {
            echo \"Connection error: \" . \$exception->getMessage();
        }
        return \$this->conn;
    }
}";

    $configDir = __DIR__ . '/backend/src/config';
    if (!is_dir($configDir)) {
        mkdir($configDir, 0755, true);
    }
    
    file_put_contents($configDir . '/database.php', $configContent);
    echo "✓ File konfigurasi database berhasil dibuat\n";
}

// Fungsi untuk setup database
function setupDatabase($host, $username, $password, $dbname) {
    try {
        // Koneksi ke MySQL tanpa database
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Buat database jika belum ada
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Database '$dbname' berhasil dibuat\n";
        
        // Pilih database
        $pdo->exec("USE `$dbname`");
        
        // Baca dan jalankan schema SQL
        $schemaFile = __DIR__ . '/database/amprahan_schema.sql';
        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            $pdo->exec($sql);
            echo "✓ Schema database berhasil dibuat\n";
        }
        
        // Tambah data awal jika ada
        $initialDataFile = __DIR__ . '/database/add_users.sql';
        if (file_exists($initialDataFile)) {
            $sql = file_get_contents($initialDataFile);
            $pdo->exec($sql);
            echo "✓ Data awal berhasil ditambahkan\n";
        }
        
        return true;
    } catch (PDOException $e) {
        echo "✗ Error setup database: " . $e->getMessage() . "\n";
        return false;
    }
}

// Fungsi untuk membuat file .htaccess
function createHtaccess() {
    $htaccessContent = "RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# CORS Headers
Header always set Access-Control-Allow-Origin \"*\"
Header always set Access-Control-Allow-Methods \"GET, POST, PUT, DELETE, OPTIONS\"
Header always set Access-Control-Allow-Headers \"Content-Type, Authorization\"

# Handle preflight requests
RewriteCond %{REQUEST_METHOD} OPTIONS
RewriteRule ^(.*)$ $1 [R=200,L]";

    $backendDir = __DIR__ . '/backend';
    if (!is_dir($backendDir)) {
        mkdir($backendDir, 0755, true);
    }
    
    file_put_contents($backendDir . '/.htaccess', $htaccessContent);
    echo "✓ File .htaccess berhasil dibuat\n";
}

// Fungsi untuk membuat file index.php di backend
function createBackendIndex() {
    $indexContent = "<?php
// Backend API Entry Point
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if (\$_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/src/config/database.php';

// Simple routing
\$request_uri = \$_SERVER['REQUEST_URI'];
\$path = parse_url(\$request_uri, PHP_URL_PATH);
\$path = trim(\$path, '/');

// Remove 'backend' from path if present
if (strpos(\$path, 'backend') === 0) {
    \$path = substr(\$path, 8);
}

// Route to appropriate controller
switch (\$path) {
    case 'login':
        require_once __DIR__ . '/src/controllers/AuthController.php';
        \$controller = new AuthController();
        \$controller->login();
        break;
    case 'register':
        require_once __DIR__ . '/src/controllers/AuthController.php';
        \$controller = new AuthController();
        \$controller->register();
        break;
    case 'barang':
        require_once __DIR__ . '/src/controllers/BarangController.php';
        \$controller = new BarangController();
        \$controller->handleRequest();
        break;
    case 'request':
        require_once __DIR__ . '/src/controllers/RequestController.php';
        \$controller = new RequestController();
        \$controller->handleRequest();
        break;
    case 'laporan':
        require_once __DIR__ . '/src/controllers/LaporanController.php';
        \$controller = new LaporanController();
        \$controller->handleRequest();
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}";

    $backendDir = __DIR__ . '/backend';
    if (!is_dir($backendDir)) {
        mkdir($backendDir, 0755, true);
    }
    
    file_put_contents($backendDir . '/index.php', $indexContent);
    echo "✓ File index.php backend berhasil dibuat\n";
}

// Main setup process
echo "Konfigurasi Database MySQL:\n";
$host = readInput("Host MySQL (default: localhost): ");
if (empty($host)) $host = 'localhost';

$username = readInput("Username MySQL (default: root): ");
if (empty($username)) $username = 'root';

$password = readInput("Password MySQL (kosongkan jika tidak ada): ");

$dbname = readInput("Nama Database (default: amprahan): ");
if (empty($dbname)) $dbname = 'amprahan';

echo "\nMemulai setup...\n";

// Setup database
if (setupDatabase($host, $username, $password, $dbname)) {
    // Buat file konfigurasi
    createConfigFile($host, $dbname, $username, $password);
    
    // Buat file .htaccess
    createHtaccess();
    
    // Buat file index.php backend
    createBackendIndex();
    
    echo "\n=== SETUP SELESAI ===\n";
    echo "Aplikasi berhasil disetup!\n\n";
    echo "Langkah selanjutnya:\n";
    echo "1. Pastikan XAMPP/Apache berjalan\n";
    echo "2. Buka terminal di folder frontend dan jalankan: npm install\n";
    echo "3. Jalankan frontend dengan: npm start\n";
    echo "4. Akses aplikasi di: http://localhost:3000\n";
    echo "5. Backend API tersedia di: http://localhost/amprahan/backend/\n\n";
    
    echo "Default admin account:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
} else {
    echo "\n✗ Setup gagal! Periksa konfigurasi database Anda.\n";
}
?> 