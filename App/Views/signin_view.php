<?php
    include __DIR__ . '/../../header.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="/_PROJ/assets/css/signIn.css">
</head>
<body>
    <div class="signin-container">
        <h2 class="form-title">Sign In</h2>
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
    </div>
    <div id="background-slider"></div>
    <script>
        const images = <?php echo json_encode($images); ?>;
        console.log(images); //for debugging
    </script>
    <script src="/_PROJ/assets/js/signin.js"></script>
</body>
</html>

