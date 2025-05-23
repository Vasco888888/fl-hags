<?php
require_once __DIR__ . '/../Core/Dbh.php';

class User extends Dbh {
    private $username;
    private $password;
    private $email;
    private $name;
    private $userID;
    private $balance;

    public function __construct($username = null) {
        if (!$username) {
            return;
        }
        $this->username = $username;

        $query = "SELECT * FROM User WHERE username = :username";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC); 
        $stmt = null;
        
        if (!$user) {
            return -1; // User not found
        }

        $this->email = $user['email'];
        $this->name = $user['name'];
        $this->password = $user['password'];
        $this->userID = $user['id'];
        $this->balance = $user['balance'];
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
    public function getBalance() {
        return $this->balance;
    }

    public static function getNameById($user_id) {
        $db = new self();
        $query = "SELECT * FROM User WHERE id = :id";
        $stmt = $db->connect()->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt = null;
        return $user['name'];
    }

    public static function getBalanceById($user_id) {
        $db = (new self())->connect();
        $query = "SELECT balance FROM User WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt = null;
        return $user['balance'];
    }

    # Setters
    public function setUsername($newUsername) {
        $db = $this->connect();
        $query = "SELECT * FROM User WHERE username = :newUsername";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":newUsername", $newUsername);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt = null;
            return -1; // Username already exists
        }
        $stmt = null;

        $query2 = "UPDATE User SET username = :newUsername WHERE id = :id";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(":newUsername", $newUsername);
        $stmt2->bindParam(":id", $this->userID);
        $stmt2->execute();
        $stmt2 = null;

        $this->username = $newUsername;
    }
    public function setName($newName) {
        $query = "UPDATE User SET name = :newName WHERE id = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":newName", $newName);
        $stmt->bindParam(":id", $this->userID);
        $stmt->execute();
        $stmt = null;

        $this->name = $newName;
    }
    public function setEmail($newEmail) {
        $db = $this->connect();
        $query = "SELECT * FROM User WHERE email = :newEmail";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":newEmail", $newEmail);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $stmt = null;
            return -1; // Username already exists
        }
        $stmt = null;

        $query2 = "UPDATE User SET email = :newEmail WHERE id = :id";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(":newEmail", $newEmail);
        $stmt2->bindParam(":id", $this->userID);
        $stmt2->execute();
        $stmt2 = null;

        $this->email = $newEmail;
    }
    public function setPassword($newPassword) {
        $nPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE User SET password = :newPassword WHERE id = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":newPassword", $nPassword);
        $stmt->bindParam(":id", $this->userID);
        $stmt->execute();
        $stmt = null;

        $this->password = $nPassword;
    }

    public function addFunds($addFunds) {
        $newBalance = $this->balance + $addFunds;
        $this->setBalence($newBalance);
    }

    private function setBalence($newBalance) {
        $query = "UPDATE User SET balance = :newBalance WHERE id = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":newBalance", $newBalance);
        $stmt->bindParam(":id", $this->userID);
        $stmt->execute();
        $stmt = null;

        $this->balance = $newBalance;
    }


    public static function addBalance($db, $user_id, $amount) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $db->prepare("UPDATE User SET balance = balance + :amount WHERE id = :uid");
            $stmt->execute([':amount' => $amount, ':uid' => $user_id]);
            return true;
        } catch (PDOException $e) {
            error_log("[User::addBalance] Error: " . $e->getMessage());
            return false;
        }
    }

    public static function subBalance($db, $user_id, $amount) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $db->prepare("SELECT balance FROM User WHERE id = :uid");
            $stmt->execute([':uid' => $user_id]);
            $balance = $stmt->fetchColumn();

            if ($balance === false || $balance < $amount) {
                error_log("[User::subBalance] Insufficient balance or user not found. ID={$user_id}");
                return -1;
            }

            $stmt = $db->prepare("UPDATE User SET balance = balance - :amount WHERE id = :uid");
            $stmt->execute([':amount' => $amount, ':uid' => $user_id]);

            return true;
        } catch (PDOException $e) {
            error_log("[User::subBalance] Error: " . $e->getMessage());
            return -1;
        }
    }


    
}
?>