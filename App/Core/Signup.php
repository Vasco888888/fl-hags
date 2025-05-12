<?php

require_once __DIR__ . '/Dbh.php'; // Ensure the correct path to the Database class file

class Signup extends Dbh {
    private $username;
    private $password;
    private $email;
    private $name;

    public function __construct($name,$username,$email,$password) {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    private function insertUser() {
        $query = "INSERT INTO users (name, username, email, password) VALUES
        (:name, :username, :email, :password)";

        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->execute();
    }
}

?>