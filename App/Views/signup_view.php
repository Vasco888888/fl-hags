<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    <form id="signupForm" method="post" action="">
        <label>Name: <br> 
        <input type="text" name="name" required></label><br>
        <label>Username: <br> 
        <input type="text" name="username" required></label><br>
        <label>Email: <br> 
        <input type="email" name="email" required></label><br>
        <label>Password: <br> 
        <input type="password" name="password" required></label><br>
        <button type="submit">Sign Up</button>
        <div id="errorMsg" style="color:red;"></div>
    </form>

    <p>Already have an account? <a href="/_PROJ/App/Views/signin_view.php">Sign In</a></p>
    <script src="/../../assets/js/signup.js"></script>
</body>
</html>

<?php
    $msg = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../Core/Signup.php';
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
                    window.location.href = '/_PROJ/App/Views/signin_view.php';
                }, 2000); </script>";
        }
    }
    if ($msg) echo "<p>$msg</p>";
?>