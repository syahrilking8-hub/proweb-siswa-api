<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../models/UserModel.php';

$userModel = new UserModel();
$students = $userModel->getAllStudents();

$response = [
    "status"  => "success",
    "message" => "Data siswa berhasil diambil.",
    "total"   => count($students),
    "data"    => $students
];

echo json_encode($response, JSON_PRETTY_PRINT);
exit;
