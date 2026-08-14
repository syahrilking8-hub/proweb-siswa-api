<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->userModel = new UserModel();
    }

    public function dashboard() {
        $students = $this->userModel->getAllStudents();
        $currentUser = $_SESSION['user'];
        
        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function users() {
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $users = method_exists($this->userModel, 'getAllUsers') 
            ? $this->userModel->getAllUsers() 
            : [];
            
        require_once __DIR__ . '/../views/users_list.php';
    }

    // HELPER: Penanganan Upload File Foto
    private function handleUploadFoto() {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadFileDir = __DIR__ . '/../public/uploads/';
                
                // Buat folder uploads jika belum ada
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                // Beri nama unik agar tidak bentrok
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    return $newFileName;
                }
            }
        }
        return '';
    }

    // TAMBAH DATA SISWA (STORE)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nis           = $_POST['nis'] ?? '';
            $nama          = $_POST['nama'] ?? '';
            $alamat        = $_POST['alamat'] ?? '';
            $tempat_lahir  = $_POST['tempat_lahir'] ?? '';
            $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
            $hobi          = $_POST['hobi'] ?? '';
            $cita_cita     = $_POST['cita_cita'] ?? '';

            // Proses Foto
            $foto = $this->handleUploadFoto();

            $this->userModel->createStudent($nis, $nama, $alamat, $tempat_lahir, $tanggal_lahir, $hobi, $cita_cita, $foto);
            header('Location: index.php?action=dashboard');
            exit;
        }
    }

    // EDIT DATA SISWA (UPDATE)
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id            = $_POST['id'] ?? '';
            $nis           = $_POST['nis'] ?? '';
            $nama          = $_POST['nama'] ?? '';
            $alamat        = $_POST['alamat'] ?? '';
            $tempat_lahir  = $_POST['tempat_lahir'] ?? '';
            $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
            $hobi          = $_POST['hobi'] ?? '';
            $cita_cita     = $_POST['cita_cita'] ?? '';

            // Proses Foto jika ada file baru yang diunggah
            $foto = $this->handleUploadFoto();

            $this->userModel->updateStudent($id, $nis, $nama, $alamat, $tempat_lahir, $tanggal_lahir, $hobi, $cita_cita, $foto);
            header('Location: index.php?action=dashboard');
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id) {
            $this->userModel->deleteStudent($id);
        }
        header('Location: index.php?action=dashboard');
        exit;
    }
}
