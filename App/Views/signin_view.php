<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>
<body>
    <h2>Sign In</h2>
    <form id="signinForm" method="post" action="">
        <label>Username:<br>
            <input type="text" name="username" required>
        </label><br>
        <label>Password:<br>
            <input type="password" name="password" required>
        </label><br>
        <button type="submit">Sign In</button>
        <div id="errorMsg" style="color:red;"></div>
    </form>
    <?php
        $msg = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../Core/Signin.php';
            $signin = new Signin(
                $_POST['username'] ?? '',
                $_POST['password'] ?? ''
            );
            $result = $signin->signinUser();
            if ($result === -1) {
                $msg = "User does not exist.";
            } elseif ($result === 1) {
                $msg = "Incorrect password.";
            } else {
                // On successful login, redirect to dashboard or home page
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '/_PROJ/index.php';
                    }, 1000);
                </script>";
                $msg = "Login successful! Redirecting...";
            }
        }
        if ($msg) echo "<p style='color:red;'>$msg</p>";
    ?>
    <p>Don't have an account? <a href="/_PROJ/App/Views/signup_view.php">Sign Up</a></p>

    <script src="/_PROJ/assets/js/signin.js"></script>
</body>
</html>

