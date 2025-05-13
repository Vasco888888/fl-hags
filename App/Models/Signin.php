<?php
require_once __DIR__ . '/../Core/Dbh.php';

class Signin extends Dbh {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    private function checkUserExists() {
        $query = "SELECT * FROM User WHERE username = :username";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        return ($stmt->rowCount() > 0); // User already exists
    }

    public function signinUser() {
        if ($this->checkUserExists()) {
            $query = "SELECT * FROM User WHERE username = :username";
            $stmt = parent::connect()->prepare($query);
            $stmt->bindParam(":username", $this->username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->password, $user['password'])) {
                return 0; // Password is correct
            } else {
                return 1; // Password is incorrect
            }
        } else {
            return -1; // User does not exist
        }
    }
}
?>