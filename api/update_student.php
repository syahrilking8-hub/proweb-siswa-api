<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../models/UserModel.php';

$input = json_decode(file_get_contents('php://input'), true);

$id            = $_POST['id'] ?? $input['id'] ?? null;
$nis           = $_POST['nis'] ?? $input['nis'] ?? '';
$nama          = $_POST['nama'] ?? $input['nama'] ?? '';
$alamat        = $_POST['alamat'] ?? $input['alamat'] ?? '';
$tempat_lahir  = $_POST['tempat_lahir'] ?? $input['tempat_lahir'] ?? '';
$tanggal_lahir = $_POST['tanggal_lahir'] ?? $input['tanggal_lahir'] ?? '';
$hobi          = $_POST['hobi'] ?? $input['hobi'] ?? '';
$cita_cita     = $_POST['cita_cita'] ?? $input['cita_cita'] ?? '';
$foto          = '';

$userModel = new UserModel();

// Jika user tidak mengupload foto baru saat edit, ambil foto lama dari database agar tidak hilang
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    $existingStudent = $userModel->getStudentById($id);
    $foto = $existingStudent['foto'] ?? '';
}

// --- LOGIK UPLOAD FOTO BARU ---
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $targetDir = __DIR__ . '/../public/uploads/';
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileExt = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $fileName = time() . '_' . uniqid() . '.' . strtolower($fileExt);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
        $foto = $fileName;
    }
}

if ($id && !empty($nis) && !empty($nama)) {
    $userModel->updateStudent($id, $nis, $nama, $alamat, $tempat_lahir, $tanggal_lahir, $hobi, $cita_cita, $foto);
    
    echo json_encode([
        "status" => "success", 
        "message" => "Data siswa berhasil diperbarui",
        "foto" => $foto
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error", 
        "message" => "ID, NIS, dan Nama wajib diisi"
    ]);
}
?>
