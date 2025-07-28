<?php
// Backend API Entry Point
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/src/config/database.php';

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Remove 'backend' from path if present
if (strpos($path, 'backend') === 0) {
    $path = substr($path, 8);
}

// Route to appropriate controller
switch ($path) {
    case 'login':
        require_once __DIR__ . '/src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
    case 'register':
        require_once __DIR__ . '/src/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;
    case 'barang':
        require_once __DIR__ . '/src/controllers/BarangController.php';
        $controller = new BarangController();
        $controller->handleRequest();
        break;
    case 'request':
        require_once __DIR__ . '/src/controllers/RequestController.php';
        $controller = new RequestController();
        $controller->handleRequest();
        break;
    case 'laporan':
        require_once __DIR__ . '/src/controllers/LaporanController.php';
        $controller = new LaporanController();
        $controller->handleRequest();
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}