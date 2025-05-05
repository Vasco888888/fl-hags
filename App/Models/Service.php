<?php
namespace App\Models;

use App\Core\Database;

class Service {
    public static function all() {
        $pdo = Database::connect();
        $query = $pdo->query('SELECT * FROM Service');
        return $query->fetchAll();
    }
}
?>