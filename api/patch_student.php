<?php
// Set Header Response ke JSON & CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once dirname(__DIR__) . '/config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data['id']) ? $data['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (!$id) {
    http_response_code(400);
    echo json_encode([
        "status" => "fail",
        "message" => "ID siswa wajib disertakan!"
    ]);
    exit();
}

// Update daftar kolom yang diizinkan untuk PATCH
$allowedColumns = ['nis', 'nama', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'hobi', 'cita_cita', 'foto'];
$fieldsToUpdate = [];
$params = [':id' => $id];

foreach ($allowedColumns as $column) {
    if (isset($data[$column]) && $data[$column] !== '') {
        $fieldsToUpdate[] = "{$column} = :{$column}";
        $params[":{$column}"] = trim($data[$column]);
    }
}

if (empty($fieldsToUpdate)) {
    http_response_code(400);
    echo json_encode([
        "status" => "fail",
        "message" => "Tidak ada data yang dikirim untuk diperbarui!"
    ]);
    exit();
}

try {
    $db = Database::getConnection();

    $sql = "UPDATE students SET " . implode(", ", $fieldsToUpdate) . " WHERE id = :id";
    $stmt = $db->prepare($sql);
    $exec = $stmt->execute($params);

    if ($exec && $stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Data siswa ID " . $id . " berhasil diperbarui (PATCH)!"
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Data siswa tidak ditemukan atau tidak ada perubahan data."
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database Error: " . $e->getMessage()
    ]);
}
