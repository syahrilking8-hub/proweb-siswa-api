<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/UserModel.php';

// Ambil ID dari: 1. POST Form Data, 2. URL (?id=x), 3. JSON RAW Body
$input = json_decode(file_get_contents('php://input'), true);
$id = $_POST['id'] ?? $_GET['id'] ?? $input['id'] ?? null;

if ($id) {
    $userModel = new UserModel();
    
    // Jalankan fungsi delete
    $result = $userModel->deleteStudent($id);
    
    if ($result) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Data siswa berhasil dihapus"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal menghapus data dari database"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID siswa wajib disertakan"]);
}
