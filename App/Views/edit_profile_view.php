<?php include __DIR__ . '/../../header.html';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="/assets/css/editProfile.css">
</head>
<body>
<div class="changeData-container">
    <a href="index.php?page=user" class="back-btn">← Back to Profile</a>

    <h2 class="form-title">Edit Profile</h2>
    <?php if (!empty($msg)) echo "<div style='color:green;'>$msg</div>"; ?>
    <form method="post">
        <label>New Name:<br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>">
        </label><br>
        <label>New Username:<br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>">
        </label><br>
        <label>New Email:<br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
        </label><br>
        <label>New Password:<br>
            <input type="password" name="password" placeholder="Leave blank to keep current">
        </label><br>
        <button type="submit" name="update_profile">Update Profile</button>
    </form>
</div>
<div id="errorMsg" style="color:red;"></div>
<script src="/assets/js/editProfile.js"></script>
</body>
</html>