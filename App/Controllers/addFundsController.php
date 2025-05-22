<?php

class addFundsController {
    public function index() {
        require_once __DIR__ . '/../Models/User.php';

        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_SESSION['username'] ?? null;
            $amount = $_POST['amount'] ?? null;

            // If "Other value" was selected, use the custom input
            if ($amount === 'other' && isset($_POST['other_amount'])) {
                $amount = $_POST['other_amount'];
            }

            if ($username && $amount && is_numeric($amount) && $amount > 0) {
                $usr = new User($username);
                $usr->addFunds($amount);
                header("Location: index.php?page=allService");
                exit;
            }
        }     
        include __DIR__ . '/../Views/add_funds_view.php';
    }
}