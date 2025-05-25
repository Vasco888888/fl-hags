<?php

require_once __DIR__ . '/../Core/Dbh.php';

class Category extends Dbh {
    private $category_id;
    private $name;

    public function __construct($name = null) {
        $this->name = $name;
    }

    public function insertCategory() {
        $query = "INSERT OR IGNORE INTO Category (name) VALUES (:name)";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();
        $stmt = null;
        return parent::connect()->lastInsertId();
    }
    
    public function getCategory($category_id) {
        $query = "SELECT * FROM Category WHERE category_id = :category_id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $category;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM Category";
        $stmt = parent::connect()->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $categories;
    }
} 