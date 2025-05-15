<?php
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
            // On successful login, redirect to dashboard or home page
            echo "<script>
                setTimeout(function() {
                    window.location.href = '/_PROJ/index.php';
                }, 2000);
            </script>";
            $msg = "Login successful! Redirecting...";
        }
    }

    
    $imgDir = __DIR__ . '/../../assets/img/';
    $imgUrlBase = '/_PROJ/assets/img/';
    $images = [];
    foreach (glob($imgDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $img) {
        $images[] = $imgUrlBase . basename($img);
    }
    

    //var_dump($images); // TEMPORARY: See what PHP finds

    include __DIR__ . '/../Views/signin_view.php';
?>