<?php
    require_once __DIR__ . '/../Core/Signup.php';

    $msg = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $signup = new Signup(
            $_POST['name'],
            $_POST['username'],
            $_POST['email'],
            $_POST['password']
        );
        $result = $signup->signupUser();
        if ($result == -1) {
            $msg = "Username or email already exists.";
        } else {
            $msg = "Registration successful! Redirecting you to the log in page.";
            echo "<script> setTimeout(function() {
                    window.location.href = '/_PROJ/App/Controllers/signInController.php';
                }, 2000); </script>";
        }
    }

    $imgDir = __DIR__ . '/../../assets/img/';
    $imgUrlBase = '/_PROJ/assets/img/';
    $images = [];
    foreach (glob($imgDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $img) {
        $images[] = $imgUrlBase . basename($img);
    }


    include __DIR__ . '/../Views/signup_view.php';
?>