<?php

class transactionController {
    public function index() {
        session_start();

        // If coming from chat, set the session order_id
        if (isset($_POST['order_id'])) {
            $_SESSION['order_id'] = $_POST['order_id'];
        }

        require_once __DIR__ . '/../Models/Transaction.php';
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Demand.php';

        $user_id = $_SESSION['user_id'];
        $order_id = $_SESSION['order_id'];

        $demandModel = new Demand();
        $order = $demandModel->getDemandById($order_id);
        $isCompleted = !empty($order['completed']) && $order['completed'] == 1;

        // Get service info
        require_once __DIR__ . '/../Models/Service.php';
        $serviceModel = new Service();
        $service = $serviceModel->getService($order['service_id']);

        // Check if user is freelancer for this order
        $isFreelancer = ($service['freelancer_id'] == $user_id);

        // Handle freelancer price request
        if ($isFreelancer && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_price'])) {
            $amount = floatval($_POST['amount']);
            $desc = trim($_POST['desc']);
            // Save price request in UserTransaction (or another table if you have it)
            Transaction::createTransactionRequest($order_id, $amount, $desc);
            $_SESSION['transaction_msg'] = "Price requested successfully!";
            header("Location: index.php?page=transaction");
            exit;
        }

        // Handle client payment
        if (!$isFreelancer && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
            $amount = $_POST['amount'];
            $result = Transaction::finishTransaction($order_id, $amount);
            if ($result === -1) {
                $_SESSION['transaction_error'] = "Insufficient funds!";
                header("Location: index.php?page=addFunds");
                exit;
            } else {
                $_SESSION['transaction_msg'] = "Payment successful! Order completed.";
                header("Location: index.php?page=transaction");
                exit;
            }
        }

        // Get transaction request (price) if exists
        $priceRequest = Transaction::getTransactionRequest($order_id);

        include __DIR__ . '/../Views/transaction_view.php';
    }
}