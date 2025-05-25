<?php
    require_once __DIR__ . '/../Core/Dbh.php';

    class Review extends Dbh {
        private $service_id;
        private $client_id;
        private $rating;
        private $comment;
        private $db;

        public function __construct($service_id = null, $client_id = null, $rating = null, $comment = null) {
            $this->service_id = $service_id;
            $this->client_id = $client_id;
            $this->rating = $rating;
            $this->comment = $comment;
            $this->db = $this->connect(); // Initialize the database connection
        }

        private function checkReviewExists() {
            $query = "SELECT * FROM Review WHERE service_id = :service_id AND client_id = :client_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":service_id", $this->service_id);
            $stmt->bindParam(":client_id", $this->client_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) { // Review already exists
                $stmt = null;
                return true;
            } else {
                $stmt = null;
                return false; // Review does not exist
            }
        }

        private function insertReview() {
            $sql = "INSERT INTO Review (service_id, client_id, rating, comment)
                    VALUES (:service_id, :client_id, :rating, :comment)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":service_id", $this->service_id);
            $stmt->bindParam(":client_id", $this->client_id);
            $stmt->bindParam(":rating", $this->rating);
            $stmt->bindParam(":comment", $this->comment);
            $stmt->execute();
            $stmt = null; // Clear the statement
        }

        public function createReview() {

            if ($this->checkReviewExists()) {
                return false; // Review already exists
            } else {
                try {
                    $this->insertReview();
                    return true;
                } catch (Exception $e) {
                    error_log("[Review::createReview] Error: " . $e->getMessage());
                    return false;
                } // Service created successfully
            }
        }

        public function getReviews($service_id) {
            $query = "SELECT * FROM Review WHERE service_id = :service_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":service_id", $service_id);
            $stmt->execute();

            $revs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            return $revs; // Return all reviews for the service
        }

        public function getUserReviews($client_id, $service_id) {
            error_log("getUserReviews called with client_id=" . var_export($client_id, true) . ", service_id=" . var_export($service_id, true));
            $query = "SELECT * FROM Review WHERE client_id = :client_id AND service_id = :service_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(":client_id", (int)$client_id, PDO::PARAM_INT);
            $stmt->bindValue(":service_id", (int)$service_id, PDO::PARAM_INT);
            $stmt->execute();

            $review = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;

            if ($review) {
                error_log("Review found: " . print_r($review, true));
                return $review; // Return the user's review for the service
            } else {
                error_log("No review found.");
                return false;
            }
        }


        public function getAverageRating($service_id) {
            $query = "SELECT AVG(rating) as average_rating FROM Review WHERE service_id = :service_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":service_id", $service_id);
            $stmt->execute();

            $rate = $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
            $stmt = null;
            return $rate; // Return the average rating for the service
        }
        
        public function deleteReview($service_id, $client_id) {
            $query = "DELETE FROM Review WHERE service_id = :service_id AND client_id = :client_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":service_id", $service_id);
            $stmt->bindParam(":client_id", $client_id);
            $stmt->execute();
            $stmt = null;
        }
    }
?>