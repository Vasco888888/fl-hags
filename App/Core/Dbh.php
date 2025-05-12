<?php

class Dbh {
    #its a sqlite db so it doesnt run in a server
    public function connect() {
        try {
            $path = __DIR__ . "/../Database/minha_base.sqlite";
            $pdo = new PDO('sqlite:' . $path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>
