<?php
    include __DIR__ . '/../../header.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/assets/css/signUp.css">
</head>
<body>
    <div class="signup-container">
        <h2 class="form-title">Sign Up</h2>
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

        <p>Already have an account? <a href="index.php?page=signIn">Sign In</a></p>
    </div>
    <div id="background-slider"></div>
    <script>
        const images = <?php echo json_encode($images); ?>;
        console.log(images); //for debugging
    </script>
    <script src="/assets/js/signup.js"></script>
</body>
</html>
