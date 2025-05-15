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
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    private function checkUserExists() {
        $query = "SELECT * FROM User WHERE username = :username OR email = :email";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        return ($stmt->rowCount() > 0); // User already exists
    }

    private function insertUser() {
        $query = "INSERT INTO User (name, username, email, password) VALUES
        (:name, :username, :email, :password)";

        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->execute();
    }

    public function signupUser() {
        if ($this->checkUserExists()) {
            return -1;
        } else {
            try {
                $this->insertUser();
                return 0; // User successfully registered
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    return -1; // Duplicate entry error
                }
                throw $e; // Error during insertion
            }
        }
    }
}

?>