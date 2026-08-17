<?php
session_start();

// Load Controller
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/ApiController.php'; // 1. Load ApiController Baru

// Inisialisasi Controller
$authController = new AuthController();
$apiController  = new ApiController(); // 2. Inisialisasi ApiController

// Tangkap action dari URL
$action = $_GET['action'] ?? null;

// Daftar route khusus API Android (Bebas dari Proteksi Session Web)
$apiActions = ['api_students', 'api_store', 'api_update', 'api_delete'];

// 3. Cek Proteksi Session khusus untuk Web HTML biasa
if (!in_array($action, $apiActions)) {
    if (!isset($_SESSION['user']) && !in_array($action, ['login', 'register'])) {
        $action = 'login';
    } elseif (isset($_SESSION['user']) && empty($action)) {
        $action = 'dashboard';
    }
}

switch ($action) {
    // --- ROUTE API ANDROID (RESPONSE JSON MURNI) ---
    case 'api_students':
        $apiController->getStudents();
        break;

    case 'api_store':
        $apiController->store();
        break;

    case 'api_update':
        $apiController->update();
        break;

    case 'api_delete':
        $apiController->delete();
        break;

    // --- ROUTE AUTENTIKASI WEB ---
    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

    case 'logout':
        $authController->logout();
        break;

    // --- ROUTE DASHBOARD & KELOLA DATA WEB (HTML) ---
    case 'dashboard':
        $userController = new UserController();
        $userController->dashboard();
        break;

    case 'users':
        $userController = new UserController();
        $userController->users();
        break;

    case 'store':
        $userController = new UserController();
        $userController->store();
        break;

    case 'update':
        $userController = new UserController();
        $userController->update();
        break;

    case 'delete':
        $userController = new UserController();
        $userController->delete();
        break;

    default:
        if (isset($_SESSION['user'])) {
            $userController = new UserController();
            $userController->dashboard();
        } else {
            $authController->login();
        }
        break;
}
