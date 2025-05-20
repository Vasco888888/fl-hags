<?php

class signInController {
    public function index() {
        $msg = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../Core/Signin.php';
            $signin = new Signin(
                $_POST['username'],
                $_POST['password']
            );
            $result = $signin->signinUser();
            if ($result == -1) {
                $msg = "User does not exist.";
            } elseif ($result == 1) {
                $msg = "Incorrect password.";
            } else {
                // On successful login, set session and redirect
                session_start();
                $_SESSION['username'] = $_POST['username'];
                $msg = "Login successful! Redirecting...";
                header("Location: index.php?page=user");
                exit;
            }
        }

        
        $imgDir = __DIR__ . '/../../assets/img/';
        $imgUrlBase = '/assets/img/';
        $images = [];
        foreach (glob($imgDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $img) {
            $images[] = $imgUrlBase . basename($img);
        }
        

        //var_dump($images); // TEMPORARY: See what PHP finds

        include __DIR__ . '/../Views/signin_view.php';
    }
}
    
?>