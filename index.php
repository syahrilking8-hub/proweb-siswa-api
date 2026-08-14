<?php
session_start();

// Load Controller
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';

// Inisialisasi Controller
$authController = new AuthController();

// Tangkap action dari URL
$action = $_GET['action'] ?? null;

// Jika belum login dan tidak sedang di halaman login/register, paksa ke login
if (!isset($_SESSION['user']) && !in_array($action, ['login', 'register'])) {
    $action = 'login';
} 
// Jika sudah login tapi action kosong, arahkan ke dashboard
elseif (isset($_SESSION['user']) && empty($action)) {
    $action = 'dashboard';
}

switch ($action) {
    // --- ROUTE AUTENTIKASI ---
    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

    case 'logout':
        $authController->logout();
        break;

    // --- ROUTE DASHBOARD & KELOLA DATA ---
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
