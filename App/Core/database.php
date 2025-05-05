<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    public static function connect() {
        try {
            return new PDO('sqlite:' . __DIR__ . '/../Database/minha_base.sqlite');
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>
