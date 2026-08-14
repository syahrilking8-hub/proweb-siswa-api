<?php
// Set Header Response ke JSON & CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight request CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Panggil database.php yang ada di folder config/
$databasePath = dirname(__DIR__) . '/config/database.php';

if (file_exists($databasePath)) {
    require_once $databasePath;
} else {
    // Fallback jika ternyata ditaruh di root
    require_once dirname(__DIR__) . '/database.php';
}

// Ambil input JSON atau POST
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
} else {
    $username = isset($data->username) ? trim($data->username) : '';
    $password = isset($data->password) ? trim($data->password) : '';
}

// Cek apakah username & password diisi
if (!empty($username) && !empty($password)) {
    try {
        // Ambil koneksi PDO dari class Database
        $db = Database::getConnection();

        // Cari user berdasarkan username
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // Verifikasi User & Password Hash BCRYPT
        if ($user && password_verify($password, $user['password'])) {
            http_response_code(200);
            echo json_encode(array(
                "status" => "success",
                "message" => "Login berhasil!",
                "data" => array(
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "role" => $user['role']
                )
            ));
        } else {
            http_response_code(401);
            echo json_encode(array(
                "status" => "error",
                "message" => "Username atau Password salah!"
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
        "message" => "Username dan Password wajib diisi!"
    ));
}
?>
