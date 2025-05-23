<?php
require_once __DIR__ . "/../Core/Dbh.php";

class Conversation extends Dbh {
    private $conversation_id;
    private $service_id;
    private $client_id;
    private $freelancer_id;
    private $service_title;
    private $order_id;

    public function __construct($client_id = null, $freelancer_id = null, $service_id = null, $order_id = null) {
        $this->order_id = $order_id;
        $this->client_id = $client_id;
        $this->freelancer_id = $freelancer_id;
        $this->service_id = $service_id;
    }

    public function setId($conversation_id) {
        $this->conversation_id = $conversation_id;
    }

    public function getId() {
        return $this->conversation_id;
    }

    private function checkConversationExists() {
        $query = "SELECT * FROM Conversation WHERE client_id = :client_id AND freelancer_id = :freelancer_id AND service_id = :service_id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->execute();
        $this->conversation_id = $stmt->fetchColumn(); // Fetch the conversation ID if it exists
        $stmt = null;
        if ($this->conversation_id) {
            return true; // Conversation exists
        }
        return false; // Conversation does not exist
    }

    private function createConversation() {
        $query = "INSERT INTO Conversation (client_id, freelancer_id, service_id, order_id) VALUES (:client_id, :freelancer_id, :service_id, :order_id)";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":freelancer_id", $this->freelancer_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->execute();
        $stmt = null;
        $this->conversation_id = parent::connect()->lastInsertId();
    }

    private function loadConversation() {
        $query = "SELECT * FROM Message WHERE conversation_id = :conversation_id ORDER BY sent_at ASC";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":conversation_id", $this->conversation_id);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $messages; // Return all messages in the conversation
    }

    public function openConversation($conversation_id = null) {
        if ($conversation_id) {
            $this->conversation_id = $conversation_id;
        }
        if ($this->checkConversationExists()) {
            // Fetch all messages for the existing conversation
            return $this->loadConversation();
        } else {
            // Create the conversation if it doesn't exist
            $this->createConversation();
            return []; // Return empty array since no messages yet
        }
    }

    public function getConversationsByUser($user_id) {
        $query = "SELECT * FROM Conversation WHERE client_id = :user_id OR freelancer_id = :user_id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $convo = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $stmt = null;
        return $convo; // Return all conversations for the user
    }

    public function getConvoTitle($conversation_id) {
        $query = "SELECT service.title FROM Conversation
                  JOIN Service ON Conversation.service_id = Service.service_id
                  WHERE Conversation.conversation_id = :conversation_id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":conversation_id", $conversation_id);
        $stmt->execute();
        $this->service_title = $stmt->fetchColumn(); // Fetch the service title
        $stmt = null;
        return $this->service_title; // Return the service title
    }

    public function getConvoFromId($conversation_id) {
        $query = "SELECT * FROM Conversation WHERE conversation_id = :conversation_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':conversation_id', $conversation_id);
        $stmt->execute();
        $conv = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $conv;
    }
}
?> 