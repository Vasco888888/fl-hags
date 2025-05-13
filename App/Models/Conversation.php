<?php
require_once __DIR__ . "/../Core/Dbh.php";

class Conversation extends Dbh {
    private $conversation_id;
    private $service_id;
    private $client_id;
    private $freelancer_id;

    public function __construct($client_id = null, $freelancer_id = null, $service_id = null) {
        $this->client_id = $client_id;
        $this->freelancer_id = $freelancer_id;
        $this->service_id = $service_id;
    }

    private function checkConversationExists() {
        $query = "SELECT * FROM Conversation WHERE client_id = :client_id AND freelancer_id = :freelancer_id AND service_id = :service_id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->execute();
        $this->conversation_id = $stmt->fetchColumn(); // Fetch the conversation ID if it exists
        if ($this->conversation_id) {
            return true; // Conversation exists
        }
        return false; // Conversation does not exist
    }

    private function createConversation() {
        $query = "INSERT INTO Conversation (client_id, freelancer_id, service_id) VALUES (:client_id, :freelancer_id, :service_id)";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->execute();
        $this->conversation_id = parent::connect()->lastInsertId();
    }

    private function loadConversation() {
        $query = "SELECT * FROM Message WHERE conversation_id = :conversation_id ORDER BY sent_at ASC";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":conversation_id", $this->conversation_id);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $messages; // Return all messages in the conversation
    }

    public function openConversation() {
        if ($this->checkConversationExists()) {
            // Fetch all messages for the existing conversation
            return $this->loadConversation();
        } else {
            // Create the conversation if it doesn't exist
            $this->createConversation();
            return []; // Return empty array since no messages yet
        }
    }
}
?>