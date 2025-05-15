<?php
    include __DIR__ . '/../../header.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Freelance Platform</title>
    <link rel="stylesheet" href="/_PROJ/assets/css/main_page.css">
</head>
<body>
    <div class="main-container">
        <h1 class="main-title">FL\HAGS</h1>
        <p class="subtitle">Find the right freelancer for your needs</p>
        <div class="button-group">
            <button id="loginBtn">Log In</button>
            <button id="signupBtn">Sign Up</button>
        </div>
    </div>
    <div id="background-slider"></div>
    <?php
        $imgDir = __DIR__ . '/../../assets/img/';
        $imgUrlBase = '/_PROJ/assets/img/';
        $images = [];
        foreach (glob($imgDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $img) {
            $images[] = $imgUrlBase . basename($img);
        }
    ?>
    <script>
    const images = <?php echo json_encode($images); ?>;
    </script>
    <script src="/_PROJ/assets/js/main_page.js"></script>
</body>
</html>