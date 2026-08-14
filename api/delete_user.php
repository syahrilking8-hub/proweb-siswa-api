<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once dirname(__DIR__) . '/config/database.php';

// Ambil input JSON
$data = json_decode(file_get_contents("php://input"), true);
$id = isset($data['id']) ? $data['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "fail", "message" => "ID user wajib disertakan!"]);
    exit();
}

try {
    $db = Database::getConnection();

    // 1. Proteksi: Cek apakah user yang mau dihapus adalah 'admin'
    $checkUser = $db->prepare("SELECT username FROM users WHERE id = :id");
    $checkUser->execute([':id' => $id]);
    $user = $checkUser->fetch();

    if ($user && strtolower($user['username']) === 'admin') {
        http_response_code(403);
        echo json_encode(["status" => "fail", "message" => "Akun admin utama tidak boleh dihapus!"]);
        exit();
    }

    // 2. Eksekusi Hapus User dari SQLite
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "User berhasil dihapus!"]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan."]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
}
?>
