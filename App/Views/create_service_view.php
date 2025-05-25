<?php include __DIR__ . '/../../header.html';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Service</title>
    <link rel="stylesheet" href="/assets/css/createServ.css">

</head>
<body> 
    <div class="main-container">
        <h1>Create a New Service</h1>
        <?php if (!empty($success)): ?>
            <div class="success-msg">Service created successfully!</div>
        <?php elseif (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form class="create-service-form" method="post" enctype="multipart/form-data" id="serviceForm">
            <div>
                <label for="title">Title *</label>
                <input type="text" name="title" id="title" maxlength="100" required>
            </div>
            <div>
                <label for="description">Description *</label>
                <textarea name="description" id="description" rows="5" maxlength="1000" required></textarea>
            </div>
            <div>
                <label for="base_price">Base Price (€) *</label>
                <input type="number" name="base_price" id="base_price" min="1" max="50" step="1" required>
            </div>
            <div>
                <label for="category">Category *</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <?= $categoryOptions ?>
                </select>
            </div>
            <div>
                <label for="images">Service Images</label>
                <input type="file" name="images[]" id="images" accept="image/*" multiple>
            </div>
            <div class="form-actions">
                <a href="index.php?page=allService" class="main-btn back-btn">Back to All Services</a>
                <button type="submit" class="main-btn" id="submitBtn" disabled>Create Service</button>
            </div>
        </form>
    </div>
    <script src="/assets/js/createServ.js"></script>
</body>
</html>