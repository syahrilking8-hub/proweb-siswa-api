<?php
// Header CORS Wajib untuk Mobile App
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel();
$id = $_GET['id'] ?? null;

if ($id) {
    $student = $userModel->getStudentById($id);
    if ($student) {
        echo json_encode(["status" => "success", "message" => "Detail siswa ditemukan", "data" => $student], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["status" => "error", "message" => "Siswa tidak ditemukan", "data" => null], JSON_PRETTY_PRINT);
    }
} else {
    $students = $userModel->getAllStudents();
    echo json_encode(["status" => "success", "message" => "Data siswa berhasil diambil", "total" => count($students), "data" => $students], JSON_PRETTY_PRINT);
}
