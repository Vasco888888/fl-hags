<?php
    include __DIR__ . '/../../header.html';
?>
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
        if (!empty($msg)) echo "<p style='color:red;'>$msg</p>";
    ?>

    <p>Don't have an account? <a href="/_PROJ/App/Controllers/signUpController.php">Sign Up</a></p>

    <script src="/_PROJ/assets/js/signup.js"></script>
</body>
</html>

