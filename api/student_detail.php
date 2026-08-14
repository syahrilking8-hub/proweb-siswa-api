<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../models/UserModel.php';

$id = $_GET['id'] ?? null;
$userModel = new UserModel();
$student = $id ? $userModel->getStudentById($id) : null;

if ($student) {
    $response = [
        "status"  => "success",
        "message" => "Detail data siswa ditemukan.",
        "data"    => $student
    ];
} else {
    http_response_code(404);
    $response = [
        "status"  => "error",
        "message" => "Data siswa tidak ditemukan."
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);
exit;
