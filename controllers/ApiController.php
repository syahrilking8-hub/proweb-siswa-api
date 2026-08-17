<?php
require_once __DIR__ . '/../models/UserModel.php';

class ApiController {
    private $userModel;

    public function __construct() {
        // HAPUS header() dari sini biar tidak merusak halaman HTML biasa!
        $this->userModel = new UserModel();
    }

    // HELPER: Send JSON Response
    private function jsonResponse($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    // HELPER: Upload Foto khusus API
    private function handleUploadFoto() {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadFileDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    return $newFileName;
                }
            }
        }
        return '';
    }

    // GET DATA SISWA (RESPONSE JSON)
    public function getStudents() {
        try {
            $students = $this->userModel->getAllStudents();
            $this->jsonResponse([
                'status' => 'success',
                'data' => $students
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // TAMBAH SISWA (RESPONSE JSON)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nis           = $_POST['nis'] ?? '';
            $nama          = $_POST['nama'] ?? '';
            $alamat        = $_POST['alamat'] ?? '';
            $tempat_lahir  = $_POST['tempat_lahir'] ?? '';
            $tanggal_lahir = $_POST['tanggal_lahir'] ?? $_POST['tgl_lahir'] ?? '';
            $hobi          = $_POST['hobi'] ?? '';
            $cita_cita     = $_POST['cita_cita'] ?? '';

            $foto = $this->handleUploadFoto();

            $success = $this->userModel->createStudent($nis, $nama, $alamat, $tempat_lahir, $tanggal_lahir, $hobi, $cita_cita, $foto);

            if ($success) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Data siswa berhasil ditambahkan!'
                ]);
            } else {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan data ke database.'
                ]);
            }
        }
    }

    // UPDATE SISWA (RESPONSE JSON)
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id            = $_POST['id'] ?? '';
            $nis           = $_POST['nis'] ?? '';
            $nama          = $_POST['nama'] ?? '';
            $alamat        = $_POST['alamat'] ?? '';
            $tempat_lahir  = $_POST['tempat_lahir'] ?? '';
            $tanggal_lahir = $_POST['tanggal_lahir'] ?? $_POST['tgl_lahir'] ?? '';
            $hobi          = $_POST['hobi'] ?? '';
            $cita_cita     = $_POST['cita_cita'] ?? '';

            $foto = $this->handleUploadFoto();

            $success = $this->userModel->updateStudent($id, $nis, $nama, $alamat, $tempat_lahir, $tanggal_lahir, $hobi, $cita_cita, $foto);

            if ($success) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Data siswa berhasil diperbarui!'
                ]);
            } else {
                $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui data.'
                ]);
            }
        }
    }

    // DELETE SISWA (RESPONSE JSON)
    public function delete() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id) {
            $success = $this->userModel->deleteStudent($id);
            if ($success) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Data siswa berhasil dihapus!'
                ]);
            }
        }
        $this->jsonResponse([
            'status' => 'error',
            'message' => 'ID tidak ditemukan atau gagal dihapus.'
        ]);
    }
}
