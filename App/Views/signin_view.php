<?php
    include __DIR__ . '/Partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="/assets/css/signIn.css">

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
            <label>
                <input type="checkbox" name="captcha" id="captchaBox" required>
                I'm not a robot
            </label><br>
            <button type="submit">Sign In</button>
            <div id="errorMsg" style="color:red;"></div>
        </form>

        <?php
            if (!empty($msg)) echo "<p style='color:red;'>$msg</p>";
        ?>

        <p>Don't have an account? <a href="index.php?page=signUp">Sign Up</a></p>
    </div>
    <div id="captchaModal" class="captcha-modal" style="display:none;">
        <div class="captcha-modal-content">
            <span id="captchaQuestion"></span>
            <input type="number" id="captchaAnswer" placeholder="Your answer">
            <button id="captchaSubmit">Submit</button>
            <div id="captchaError" style="color:red; margin-top:8px;"></div>
        </div>
    </div>
    <div id="background-slider"></div>
    <script>
        const images = <?php echo json_encode($images); ?>;
        console.log(images); //for debugging
    </script>
    <script src="/assets/js/signin.js"></script>
</body>
</html>

