<?php
require_once __DIR__ . '/../Core/Dbh.php';

class Message extends Dbh {
    private $message_id;
    private $conversation_id;
    private $send_id;
    private $text;

    public function __construct($conversation_id = null, $send_id = null, $text = null) {
        $this->conversation_id = $conversation_id;
        $this->send_id = $send_id;
        $this->text = $text;
    }

    public static function sendMessage($conversation_id, $send_id, $text) {
        $db = (new self())->connect();
        $query = "INSERT INTO Message (conversation_id, send_id, text) VALUES (:conversation_id, :send_id, :text)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":conversation_id", $conversation_id);
        $stmt->bindParam(":send_id", $send_id);
        $stmt->bindParam(":text", $text);
        $stmt->execute();
        $stmt = null;
        return $db->lastInsertId();
    }
} 
?>