<?php

require_once __DIR__ . '/../Core/Dbh.php';

class Service extends Dbh{
    public function all() {
        $query = "SELECT * FROM Service";
        $stmt = parent::connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getService($id) {}
}
?>