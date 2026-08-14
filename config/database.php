<?php

class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                // Mengubungkan ke file SQLite di root direktori
                $dbPath = __DIR__ . '/../database.sqlite';
                self::$pdo = new PDO('sqlite:' . $dbPath);
                
                // Set mode error ke Exception untuk kemudahan debugging
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Koneksi database gagal: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
