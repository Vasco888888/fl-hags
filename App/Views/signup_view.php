<?php
    include __DIR__ . '/../../header.html';
?>
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

    <?php
        if (!empty($msg)) echo "<p style='color:red;'>$msg</p>";
    ?>

    <p>Already have an account? <a href="/_PROJ/App/Controllers/signInController.php">Sign In</a></p>
    <script src="/_PROJ/assets/js/signup.js"></script>
</body>
</html>
