<?php
session_start();

// Load Controller
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/ApiController.php';

$authController = new AuthController();
$action = $_GET['action'] ?? null;

$apiActions = ['api_students', 'api_store', 'api_update', 'api_delete'];

// Cek Proteksi Session khusus untuk Web HTML biasa
if (!in_array($action, $apiActions)) {
    if (!isset($_SESSION['user']) && !in_array($action, ['login', 'register'])) {
        $action = 'login';
    } elseif (isset($_SESSION['user']) && empty($action)) {
        $action = 'dashboard';
    }
}

switch ($action) {
    // --- ROUTE API ANDROID ---
    case 'api_students':
        (new ApiController())->getStudents();
        break;

    case 'api_store':
        (new ApiController())->store();
        break;

    case 'api_update':
        (new ApiController())->update();
        break;

    case 'api_delete':
        (new ApiController())->delete();
        break;

    // --- ROUTE WEB HTML ---
    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        (new UserController())->dashboard();
        break;

    case 'users':
        (new UserController())->users();
        break;

    case 'store':
        (new UserController())->store();
        break;

    case 'update':
        (new UserController())->update();
        break;

    case 'delete':
        (new UserController())->delete();
        break;

    default:
        if (isset($_SESSION['user'])) {
            (new UserController())->dashboard();
        } else {
            $authController->login();
        }
        break;
}
