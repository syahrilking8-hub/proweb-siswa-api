<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->autoMigrate(); // Otomatis tambah kolom jika belum ada di DB
    }

    // --- AUTO MIGRATE DATABASE ---
    private function autoMigrate() {
        $columns = ['tempat_lahir', 'tanggal_lahir', 'hobi', 'cita_cita', 'foto'];
        foreach ($columns as $col) {
            try {
                // Tambahkan kolom jika belum ada
                $this->db->exec("ALTER TABLE students ADD COLUMN {$col} TEXT DEFAULT ''");
            } catch (PDOException $e) {
                // Abaikan error jika kolom sudah ada
            }
        }
    }

    // --- AUTENTIKASI ---
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    // --- KELOLA USERS ---
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, role FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $hashedPassword, $role = 'user') {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':role'     => $role
        ]);
    }

    // --- CRUD DATA SISWA (UPDATED) ---
    public function getAllStudents() {
        $stmt = $this->db->query("SELECT * FROM students ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // CREATE: Tambah parameter data baru + foto
    public function createStudent($nis, $nama, $alamat, $tempat_lahir = '', $tanggal_lahir = '', $hobi = '', $cita_cita = '', $foto = '') {
        $stmt = $this->db->prepare("INSERT INTO students (nis, nama, alamat, tempat_lahir, tanggal_lahir, hobi, cita_cita, foto) 
                                    VALUES (:nis, :nama, :alamat, :tempat_lahir, :tanggal_lahir, :hobi, :cita_cita, :foto)");
        return $stmt->execute([
            ':nis'           => $nis,
            ':nama'          => $nama,
            ':alamat'        => $alamat,
            ':tempat_lahir'  => $tempat_lahir,
            ':tanggal_lahir' => $tanggal_lahir,
            ':hobi'          => $hobi,
            ':cita_cita'     => $cita_cita,
            ':foto'          => $foto
        ]);
    }

    // UPDATE: Update data lengkap + foto (Disempurnakan agar selalu aman memuat parameter foto)
    public function updateStudent($id, $nis, $nama, $alamat, $tempat_lahir = '', $tanggal_lahir = '', $hobi = '', $cita_cita = '', $foto = '') {
        $stmt = $this->db->prepare("UPDATE students SET 
                                    nis = :nis, 
                                    nama = :nama, 
                                    alamat = :alamat, 
                                    tempat_lahir = :tempat_lahir, 
                                    tanggal_lahir = :tanggal_lahir, 
                                    hobi = :hobi, 
                                    cita_cita = :cita_cita, 
                                    foto = :foto 
                                    WHERE id = :id");
        return $stmt->execute([
            ':id'            => $id,
            ':nis'           => $nis,
            ':nama'          => $nama,
            ':alamat'        => $alamat,
            ':tempat_lahir'  => $tempat_lahir,
            ':tanggal_lahir' => $tanggal_lahir,
            ':hobi'          => $hobi,
            ':cita_cita'     => $cita_cita,
            ':foto'          => $foto
        ]);
    }

    public function deleteStudent($id) {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
