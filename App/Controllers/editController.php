<?php
class editController {
    public function index() {
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Service.php';
        require_once __DIR__ . '/../Models/Demand.php';
        require_once __DIR__ . '/../Models/Conversation.php';

        session_start();
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?page=signin");
            exit;
        }

        $user = new User($_SESSION['username']);
        $msg = "";

        // Handle profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            try {
                if (!empty($_POST['username'])) {
                    $result = $user->setUsername($_POST['username']);
                    if ($result !== -1) {
                        $_SESSION['username'] = $_POST['username'];
                    }
                }
                if (!empty($_POST['name'])) {
                    $result = $user->setUsername($_POST['name']);
                    if ($result !== -1) {
                        $_SESSION['name'] = $_POST['name'];
                    }
                }
                if (!empty($_POST['email'])) {
                    $result = $user->setUsername($_POST['email']);
                    if ($result !== -1) {
                        $_SESSION['email'] = $_POST['email'];
                    }
                }
                if (!empty($_POST['password'])) {
                    $result = $user->setUsername($_POST['password']);
                    if ($result !== -1) {
                        $_SESSION['password'] = $_POST['password'];
                    }
                }
                // Redirect to user profile/dashboard after update
                header("Location: index.php?page=user");
                exit;
            } catch (Exception $e) {
                if ($e->getCode() == -1) {
                    $msg = "Username or email already exists.";
                } else {
                    $msg = "An error occurred: " . $e->getMessage();
                }
            }
            
        }

        // Fetch user data
        $userData = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ];

        include __DIR__ . '/../Views/edit_profile_view.php';
    }
}
?>