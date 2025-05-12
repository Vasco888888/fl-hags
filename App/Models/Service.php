<?php

require_once __DIR__ . '/../Core/Dbh.php';

class Service {
    public function all() {
        $db = new Dbh();
        $pdo = $db->connect();
        $query = $pdo->query('SELECT * FROM Service');
        return $query->fetchAll();
    }
}
?>