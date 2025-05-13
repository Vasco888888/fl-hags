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

    public function insertMSG() {
        $query = "INSERT INTO Message (conversation_id, send_id, text) VALUES (:conversation_id, :send_id, :text)";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":conversation_id", $this->conversation_id);
        $stmt->bindParam(":send_id", $this->send_id);
        $stmt->bindParam(":text", $this->text);
        $stmt->execute();
        $this->message_id = parent::connect()->lastInsertId();
        return $this->message_id;
    }
}
?>