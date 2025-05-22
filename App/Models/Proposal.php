<?php
require_once __DIR__ . '/../Core/Dbh.php';

class Proposal extends Dbh {
    public function createProposal($conversation_id, $price) {
        $query = "INSERT INTO Proposal (conversation_id, price) VALUES (:conversation_id, :price)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':conversation_id', $conversation_id);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
        $stmt = null;
    }

    public function getPendingProposal($conversation_id) {
        $query = "SELECT * FROM Proposal WHERE conversation_id = :conversation_id AND status = 'pending' ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':conversation_id', $conversation_id);
        $stmt->execute();
        $proposal = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt = null;
        return $proposal;
    }

    public function updateStatus($proposal_id, $status) {
        $query = "UPDATE Proposal SET status = :status WHERE proposal_id = :proposal_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':proposal_id', $proposal_id);
        $stmt->execute();
        $stmt = null;
    }
}
?>