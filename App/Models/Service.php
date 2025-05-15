<?php

require_once __DIR__ . '/../Core/Dbh.php';

class Service extends Dbh{

    private $freelancer_id;
    private $category_id;
    private $title;
    private $description;
    private $base_price;

    public function __construct($freelancer_id = null, 
                                $category_id = null, $title = null, $description = null, 
                                $base_price = null) {

        $this->freelancer_id = $freelancer_id;
        $this->category_id = $category_id;
        $this->title = $title;
        $this->description = $description;
        $this->base_price = $base_price;
    }

    private function checkServiceExists() {
        $query = "SELECT * FROM Service WHERE title = :title AND freelancer_id = :freelancer_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->execute();

        return ($stmt->rowCount() > 0); // Service already exists
    }

    private function insertService() {
        $query = "INSERT INTO Service (freelancer_id, category_id, title, description, base_price) 
                  VALUES (:freelancer_id, :category_id, :title, :description, :base_price)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":base_price", $this->base_price);
        return $stmt->execute();
    }

    public function createService() {
        if ($this->checkServiceExists()) {
            return -1; // Service already exists
        } else {
            $this->insertService();
            return 0; // Service created successfully
        }
    }

    public function getService($service_id) {
        $query = "SELECT * FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        return $service;
    }

    public function getAllServices($category_id) {
        $query = "SELECT * FROM Service WHERE category_id = :category_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $services;
    }

    public function updateService($service_id) {
        $query = "UPDATE Service SET title = :title, description = :description, base_price = :base_price WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":base_price", $this->base_price);
        $stmt->bindParam(":service_id", $service_id);
        return $stmt->execute();
    }

    public function deleteService($service_id) {
        $query = "DELETE FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        return $stmt->execute();
    }

    public function getFreelancerServices($freelancer_id) {
        $query = "SELECT * FROM Service WHERE freelancer_id = :freelancer_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":freelancer_id", $freelancer_id);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $services;
    }

    
    public function getFreelancerID($service_id) {
        $query = "SELECT freelancer_id FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $freelancer = $stmt->fetch(PDO::FETCH_ASSOC);
        return $freelancer['freelancer_id'];
    }

    public function getTitle($service_id) {
        $query = "SELECT title FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $title = $stmt->fetch(PDO::FETCH_ASSOC);
        return $title['title'];
    }

    public function getDescription($service_id) {
        $query = "SELECT description FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $description = $stmt->fetch(PDO::FETCH_ASSOC);
        return $description['description'];
    }

    public function getBasePrice($service_id) {
        $query = "SELECT base_price FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $base_price = $stmt->fetch(PDO::FETCH_ASSOC);
        return $base_price['base_price'];
    }

    public function getCategoryID($service_id) {
        $query = "SELECT category_id FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $category_id = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category_id['category_id'];
    }

    public function getUpdatedAt($service_id) {
        $query = "SELECT updated_at FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $updated_at = $stmt->fetch(PDO::FETCH_ASSOC);
        return $updated_at['updated_at'];
    }
}
?>