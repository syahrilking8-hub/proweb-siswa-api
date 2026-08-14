<?php
// Koneksi ke file database SQLite
$db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. Buat Tabel Users (jika belum ada)
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin', 'user')) NOT NULL
)");

// 2. Buat Tabel Students (jika belum ada)
$db->exec("CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nis TEXT UNIQUE NOT NULL,
    nama TEXT NOT NULL,
    alamat TEXT NOT NULL,
    tempat_lahir TEXT DEFAULT '',
    tanggal_lahir TEXT DEFAULT '',
    hobi TEXT DEFAULT '',
    cita_cita TEXT DEFAULT '',
    foto TEXT DEFAULT ''
)");

// 3. AUTO-MIGRATION: Tambahkan kolom baru jika tabel lama belum punya kolom ini
$columns = [
    'tempat_lahir' => 'TEXT DEFAULT \'\'',
    'tanggal_lahir' => 'TEXT DEFAULT \'\'',
    'hobi'         => 'TEXT DEFAULT \'\'',
    'cita_cita'    => 'TEXT DEFAULT \'\'',
    'foto'         => 'TEXT DEFAULT \'\''
];

foreach ($columns as $columnName => $columnType) {
    try {
        $db->exec("ALTER TABLE students ADD COLUMN {$columnName} {$columnType}");
    } catch (PDOException $e) {
        // Abaikan jika kolom sudah ada
    }
}

// 4. Masukkan Data Sample Default (Seeder)
$hashedPassword = password_hash('password123', PASSWORD_BCRYPT);

$stmtUser = $db->prepare("INSERT OR IGNORE INTO users (username, password, role) VALUES (:username, :password, :role)");
$stmtUser->execute([':username' => 'admin', ':password' => $hashedPassword, ':role' => 'admin']);
$stmtUser->execute([':username' => 'user', ':password' => $hashedPassword, ':role' => 'user']);

echo "Database dan tabel berhasil di-upgrade dengan kolom baru!";
