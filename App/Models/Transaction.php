<?php

require_once __DIR__ . '/../Core/Dbh.php';

class Transaction extends Dbh {
    private $order_id;
    private $amount;

    public function __construct($order_id = null, $amount = null) {
        $this->order_id = $order_id;
        $this->amount = $amount;
    }

    public static function finishTransaction($order_id, $amount) {
        $db = (new self())->connect();
        // Ensure exceptions on errors
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $db->beginTransaction();

            // Fetch the latest unpaid transaction
            $selectSql = "SELECT transaction_id FROM UserTransaction
                          WHERE order_id = :order_id AND paid = 0
                          ORDER BY transaction_id DESC LIMIT 1";
            $stmt = $db->prepare($selectSql);
            $stmt->execute([':order_id' => $order_id]);
            $txnId = $stmt->fetchColumn();
            if (!$txnId) {
                throw new Exception("No pending transaction found for order_id {$order_id}");
            }
            error_log("[Transaction] Pending txnId={$txnId} for order_id={$order_id}");

            // Mark transaction as paid
            $updateSql = "UPDATE UserTransaction SET paid = 1 WHERE transaction_id = :txnId";
            $stmt = $db->prepare($updateSql);
            $stmt->execute([':txnId' => $txnId]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("Failed to mark transaction {$txnId} as paid");
            }
            error_log("[Transaction] Marked txnId={$txnId} as paid");

            // Get demand info
            $stmt = $db->prepare("SELECT client_id, service_id FROM Demand WHERE order_id = :order_id");
            $stmt->execute([':order_id' => $order_id]);
            $demand = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$demand) {
                throw new Exception("Demand not found for order_id {$order_id}");
            }
            list('client_id' => $client_id, 'service_id' => $service_id) = $demand;
            error_log("[Transaction] Demand found: client_id={$client_id}, service_id={$service_id}");

            // Get freelancer
            $stmt = $db->prepare("SELECT freelancer_id FROM Service WHERE service_id = :sid");
            $stmt->execute([':sid' => $service_id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$service) {
                throw new Exception("Service not found (ID: {$service_id})");
            }
            $freelancer_id = $service['freelancer_id'];
            error_log("[Transaction] Freelancer_id={$freelancer_id}");

            // Perform balance transfer
            if (User::subBalance($db, $client_id, $amount) === -1) {
                throw new Exception("Insufficient funds for client_id={$client_id}");
            }
            User::addBalance($db, $freelancer_id, $amount);
            error_log("[Transaction] Transferred amount {$amount} from client {$client_id} to freelancer {$freelancer_id}");

            // Complete demand
            Demand::finishDemandById($db, $order_id);
            error_log("[Transaction] Demand finished for order_id={$order_id}");

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("[Transaction::finishTransaction] Error: " . $e->getMessage());
            return false;
        }
    }

    public static function createTransactionRequest($order_id, $amount, $desc) {
        $db = new self();
        $query = "INSERT INTO UserTransaction (order_id, amount, description, requested, paid) VALUES (:order_id, :amount, :description, 1, 0)";
        $stmt = $db->connect()->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':description', $desc);
        $stmt->execute();
        $stmt = null;
    }

    public static function getTransactionRequest($order_id) {
        $db = new self();
        $query = "SELECT * FROM UserTransaction WHERE order_id = :order_id AND requested = 1 ORDER BY transaction_id DESC LIMIT 1";
        $stmt = $db->connect()->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt = null;
        return $result;
    }
}