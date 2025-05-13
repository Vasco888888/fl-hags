<?php
require_once __DIR__ . '/../Core/Dbh.php';

class User extends Dbh {
    private $username;
    private $password;
    private $email;
    private $name;
    private $userID;

    public function __construct($username) {
        $this->username = $username;

        $query = "SELECT * FROM User WHERE username = :username";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->email = $user['email'];
        $this->name = $user['name'];
        $this->password = $user['password'];
        $this->userID = $user['id'];
    }

    # Getters
    public function getUsername() {
        return $this->username;
    }
    public function getName() {
        return $this->name;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getID() {
        return $this->userID;
    }

    # Setters
    public function setUsername($newUsername) {
        $query = "SELECT * FROM User WHERE username = :newUsername";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":newUsername", $newUsername);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return -1; // Username already exists
        }

        $query2 = "UPDATE User SET username = :newUsername WHERE username = :username";
        $stmt2 = parent::connect()->prepare($query2);
        $stmt2->bindParam(":newUsername", $newUsername);
        $stmt2->bindParam(":username", $this->username);
        $stmt2->execute();

        $this->username = $newUsername;
    }
    public function setName($newName) {
        $query = "UPDATE User SET name = :newName WHERE name = :name";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":newName", $newName);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        $this->username = $newName;
    }
    public function setEmail($newEmail) {
        $query = "SELECT * FROM User WHERE email = :newEmail";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":newEmail", $newEmail);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return -1; // Username already exists
        }

        $query2 = "UPDATE User SET email = :newEmail WHERE email = :email";
        $stmt2 = parent::connect()->prepare($query2);
        $stmt2->bindParam(":newEmail", $newEmail);
        $stmt2->bindParam(":email", $this->email);
        $stmt2->execute();

        $this->email = $newEmail;
    }
    public function setPassword($newPassword) {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE User SET password = :newPassword WHERE password = :password";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":newPassword", $newPassword);
        $stmt->bindParam(":password", $this->password);
        $stmt->execute();

        $this->password = $newPassword;
    }
}
?>