<?php
// Set Header Response ke JSON & CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight request CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Panggil database.php dari folder config/
$databasePath = dirname(__DIR__) . '/config/database.php';

if (file_exists($databasePath)) {
    require_once $databasePath;
} else {
    require_once dirname(__DIR__) . '/database.php';
}

// Ambil input JSON atau Form POST
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
} else {
    $username = isset($data->username) ? trim($data->username) : '';
    $password = isset($data->password) ? trim($data->password) : '';
}

// Validation: Cek apakah username & password diisi
if (!empty($username) && !empty($password)) {
    try {
        $db = Database::getConnection();

        // 1. Cek apakah username sudah dipakai
        $checkStmt = $db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $checkStmt->execute([':username' => $username]);

        if ($checkStmt->fetch()) {
            http_response_code(400);
            echo json_encode(array(
                "status" => "fail",
                "message" => "Username sudah terdaftar! Gunakan username lain."
            ));
            exit();
        }

        // 2. Hash Password demi keamanan
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $defaultRole = 'user'; // Default role untuk pendaftar baru

        // 3. Insert ke database
        $insertStmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $exec = $insertStmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':role'     => $defaultRole
        ]);

        if ($exec) {
            http_response_code(201); // 201 Created
            echo json_encode(array(
                "status" => "success",
                "message" => "Registrasi berhasil! Silakan login."
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                "status" => "error",
                "message" => "Gagal mendaftarkan user baru."
            ));
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array(
            "status" => "error",
            "message" => "Database Error: " . $e->getMessage()
        ));
    }
} else {
    http_response_code(400);
    echo json_encode(array(
        "status" => "fail",
        "message" => "Username dan Password tidak boleh kosong!"
    ));
}
?>
