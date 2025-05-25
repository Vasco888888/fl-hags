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

        if ($stmt->rowCount() > 0){ // Service already exists
            $stmt = null;
            return true;
        } else {
            $stmt = null;
            return false; // Service does not exist
        }
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
        $stmt->execute();
        $stmt = null;
    }

    public function createService() {
        if ($this->checkServiceExists()) {
            return -1; // Service already exists
        } else {
            $this->insertService();
            return 0; // Service created successfully
        }
    }

    public function createNretLastID() { //sole purpose is for service creation
        $db = $this->connect();
        $query = "INSERT INTO Service (freelancer_id, category_id, title, description, base_price) 
                  VALUES (:freelancer_id, :category_id, :title, :description, :base_price)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":base_price", $this->base_price);
        $stmt->execute();
        $lastId = $db->lastInsertId(); // Use the SAME $db object
        error_log("Last inserted service_id: " . $lastId);
        $stmt = null;
        return $lastId; // Return the last inserted ID
    }

    
    public function getAllServices() {
        $query = "SELECT * FROM Service";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $services;
    }

    public function getService($service_id) {
        $query = "SELECT * FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $service;
    }

    private function getServicesByCategory($category_id) {
        if ($category_id == 0) {
            return $this->getAllServices();
        }
        $query = "SELECT * FROM Service WHERE category_id = :category_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $services;
    }

    private function getServicesByPrice($services, $minPrice, $maxPrice) {
        return array_filter($services, function($service) use ($minPrice, $maxPrice) {
            return $service['base_price'] >= $minPrice && $service['base_price'] <= $maxPrice;
        });
    }
 
    private function getServicesByRating($services, $minRating) {
        if ($minRating <= 0) return $services;
        require_once __DIR__ . '/Review.php';
        $reviewModel = new Review();
        return array_filter($services, function($service) use ($reviewModel, $minRating) {
            $rating = $reviewModel->getAverageRating($service['service_id']);
            return $rating >= $minRating;
        });
    }

    private function sortServicesByDate($services, $sort) {
        usort($services, function($a, $b) use ($sort) {
            if ($sort === 'oldest') {
                return strtotime($a['updated_at']) - strtotime($b['updated_at']);
            }
            // Default: newest first
            return strtotime($b['updated_at']) - strtotime($a['updated_at']);
        });
        return $services;
    }

    private function sortServicesByPrice($services, $sort) {
        usort($services, function($a, $b) use ($sort) {
            if ($sort === 'desc') {
                return $b['base_price'] <=> $a['base_price'];
            }
            // Default: asc
            return $a['base_price'] <=> $b['base_price'];
        });
        return $services;
    }

    private function sortServicesByRating($services, $sort) {
        require_once __DIR__ . '/Review.php';
        $reviewModel = new Review();
        // Attach average rating to each service
        foreach ($services as &$service) {
            $service['avg_rating'] = $reviewModel->getAverageRating($service['service_id']);
        }
        unset($service);
        usort($services, function($a, $b) use ($sort) {
            $aRating = $a['avg_rating'] ?? 0;
            $bRating = $b['avg_rating'] ?? 0;
            if ($sort === 'low') {
                return $aRating <=> $bRating;
            }
            // Default: high
            return $bRating <=> $aRating;
        });
        // Remove avg_rating before returning
        foreach ($services as &$service) {
            unset($service['avg_rating']);
        }
        unset($service);
        return $services;
    }

    public function updateService($service_id) {
        $query = "UPDATE Service SET title = :title, description = :description, base_price = :base_price WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":base_price", $this->base_price);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $stmt = null;
    }

    public function deleteService($service_id) {
        $query = "DELETE FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $stmt = null;
    }

    public function getFreelancerServices($freelancer_id) {
        $query = "SELECT * FROM Service WHERE freelancer_id = :freelancer_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":freelancer_id", $freelancer_id);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $services;
    }

    
    public function getFreelancerID($service_id) {
        $query = "SELECT freelancer_id FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $freelancer = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $freelancer['freelancer_id'];
    }

    public function getTitle($service_id) {
        $query = "SELECT title FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $title = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $title['title'];
    }

    public function getDescription($service_id) {
        $query = "SELECT description FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $description = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $description['description'];
    }

    public function getBasePrice($service_id) {
        $query = "SELECT base_price FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $base_price = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $base_price['base_price'];
    }

    public function getCategoryID($service_id) {
        $query = "SELECT category_id FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $category_id = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $category_id['category_id'];
    }

    public function getUpdatedAt($service_id) {
        $query = "SELECT updated_at FROM Service WHERE service_id = :service_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
        $stmt->execute();
        $updated_at = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $updated_at['updated_at'];
    }

    public function filterServices($category, $minPrice, $maxPrice, $minRating, $sort) {
        // Step 1: Get by category
        if ($category === 'all' || $category == 0) {
            $services = $this->getAllServices();
        } else {
            $services = $this->getServicesByCategory($category);
        }

        // Step 2: Filter by price
        $services = $this->getServicesByPrice($services, $minPrice, $maxPrice);

        // Step 3: Filter by rating
        $services = $this->getServicesByRating($services, $minRating);

        // Step 4: Sort
        switch ($sort) {
            case 'asc':
            case 'desc':
                $services = $this->sortServicesByPrice($services, $sort);
                break;
            case 'oldest':
            case 'newest':
            case 'date':
                $services = $this->sortServicesByDate($services, $sort);
                break;
            case 'rating_high':
            case 'rating_low':
                $services = $this->sortServicesByRating($services, $sort === 'rating_low' ? 'low' : 'high');
                break;
            default:
                $services = $this->sortServicesByDate($services, 'newest');
        }

        return $services;
    }
}
?>