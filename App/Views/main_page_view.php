<?php
include __DIR__ . '/Partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Freelance Platform</title>
    <link rel="stylesheet" href="/assets/css/main_page.css">

</head>

<body>
    <div class="main-container">
        <h1 class="main-title">FL\HAGS</h1>
        <p class="subtitle">Find the right freelancer for your house needs</p>
        <div class="button-group">
            <form id="loginbtn" method="GET" action="">
                <button id="loginBtn" type="submit" name="signin">Sign In</button>
            </form>
            <form id="signupbtn" method="GET" action="">
                <button id="signupBtn" type="submit" name="signup">Sign Up</button>
            </form>
        </div>
    </div>
    <div id="background-slider"></div>
    <script>
        const images = <?php echo json_encode($images); ?>;
    </script>
    <script src="/assets/js/main_page.js"></script>
</body>

</html>