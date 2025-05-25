<?php
require_once __DIR__ . '/../Core/Dbh.php';

class Admin extends Dbh {
    private $id;

    public function __construct($id=null) {
        $this->id = $id;
    }

    // Check if a user is an admin
    public function isAdmin($user_id) {
        $query = "SELECT COUNT(*) FROM Admin WHERE id = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt = null;
        return ($count > 0); // true if user is admin, false otherwise
    }

    // Elevate a user to admin status
    public function elevateUser($user_id) {
        $query = "INSERT OR IGNORE INTO Admin (id) VALUES (:id)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $stmt = null;
    }

    // Add a new service category
    public function addCategory($name) {
        $category = new Category($name);
        $category->insertCategory();
    }

    // Get all users
    public static function getAllUsers() {
        $query = "SELECT * FROM User";
        $stmt = (new Dbh())->connect()->prepare($query);
        $stmt->execute();
        $allUs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $allUs;
    }


    // Get all admins
    public function getAllAdmins() {
        $query = "SELECT * FROM Admin";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $allAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $allAdmins;
    }

    // Get all services
    public static function getAllServices() {
        $query = "SELECT Service.*, User.username AS owner_username
                FROM Service
                JOIN User ON Service.freelancer_id = User.id";
        $stmt = (new Dbh())->connect()->prepare($query);
        $stmt->execute();
        $allSrv = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $allSrv;
    }

    // Get all conversations
    public static function getAllConversations() {
        $query = "SELECT * FROM Conversation";
        $stmt = (new Dbh())->connect()->prepare($query);
        $stmt->execute();
        $allConv = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $allConv;
    }

    // Ban an user
    public function banUser($user_id) {
        if ($this->isAdmin($user_id)) return -1; // Cannot ban an admin
        $query = "DELETE FROM User WHERE id = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $stmt = null;
        return 0; // User banned successfully
    }
}
?> 