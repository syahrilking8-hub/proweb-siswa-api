<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    public function login() {
        if (isset($_SESSION['user'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $selectedRole = $_POST['role'] ?? '';

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['role'] === $selectedRole) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role']
                    ];
                    header('Location: index.php?action=dashboard');
                    exit;
                } else {
                    $error = "Role yang dipilih tidak sesuai dengan akun Anda!";
                }
            } else {
                $error = "Username atau password salah!";
            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

    // --- FITUR REGISTER WEB ---
    public function register() {
        if (isset($_SESSION['user'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'user';

            if (!empty($username) && !empty($password)) {
                $existingUser = $this->userModel->getUserByUsername($username);
                if ($existingUser) {
                    $error = "Username sudah digunakan!";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $created = $this->userModel->createUser($username, $hashedPassword, $role);
                    if ($created) {
                        $success = "Registrasi berhasil! Silakan login.";
                    } else {
                        $error = "Gagal memproses registrasi!";
                    }
                }
            } else {
                $error = "Username dan Password wajib diisi!";
            }
        }

        require_once __DIR__ . '/../views/registrasi.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
