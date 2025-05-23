<?php
    require_once __DIR__ . '/../Core/Dbh.php';

    class Demand extends Dbh {
        private $order_id;
        private $service_id;
        private $client_id;
        private $completed;

        public function __construct($service_id = null, $client_id = null) {
            $this->service_id = $service_id;
            $this->client_id = $client_id;
            $this->completed = 0;
        }

        private function checkDemandExists() {
            $query = "SELECT * FROM Demand WHERE order_id = :order_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":order_id", $this->order_id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) { // Demand already exists
                $stmt = null;
                return true;
            } else {
                $stmt = null;
                return false; // Demand does not exist
            }
        }

        private function insertDemand() {
            $db = $this->connect();
            $query = "INSERT INTO Demand (service_id, client_id) VALUES (:service_id, :client_id)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":service_id", $this->service_id);
            $stmt->bindParam(":client_id", $this->client_id);
            $stmt->execute();
            $stmt = null;
            return $db->lastInsertId(); // Return the last inserted ID
        }

        public function finishDemand() {
            $query = "UPDATE Demand SET completed = 1, date_completed = CURRENT_TIMESTAMP WHERE order_id = :order_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":order_id", $this->order_id);
            $stmt->execute();
            $stmt = null;

            $this->completed = 1; // Update the completed status
        }

        public static function finishDemandById($db, $order_id) {
            $query = "UPDATE Demand SET completed = 1, date_completed = CURRENT_TIMESTAMP WHERE order_id = :order_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            $stmt = null;
        }

        public function createDemand() {
            $existing = $this->getDemandByServiceAndClient($this->service_id, $this->client_id);
            if ($existing) {
                return $existing['order_id'];
            } else {
                return $this->insertDemand();
            }
        }

        public function getDemandsByService($service_id) {
            $query = "SELECT * FROM Demand WHERE service_id = :service_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":service_id", $service_id);
            $stmt->execute();
            $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            return $demands; // Return all demands for the service
        }

        public function getDemandsByClient($client_id) {
            $query = "SELECT * FROM Demand WHERE client_id = :client_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":client_id", $client_id);
            $stmt->execute();
            $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            return $demands; // Return all demands for the client
        }

        public function getDemandsCompletedByClient($client_id) {
            $query = "SELECT * FROM Demand WHERE client_id = :client_id AND completed = 1";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":client_id", $client_id);
            $stmt->execute();
            $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            return $demands; // Return all completed demands for the client
        }

        public function getDemandById($order_id) {
            $query = "SELECT * FROM Demand WHERE order_id = :order_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            $demand = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;
            return $demand;
        }

        public function getDemandByServiceAndClient($service_id, $client_id) {
            $query = "SELECT * FROM Demand WHERE service_id = :service_id AND client_id = :client_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(":service_id", $service_id);
            $stmt->bindParam(":client_id", $client_id);
            $stmt->execute();
            $demand = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;
            return $demand;
        }
    }
?>