<?php include __DIR__ . '/Partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Freelance Platform</title>
    <link rel="stylesheet" href="/assets/css/allServ.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css">
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>

</head>
<body>
    <div class="main-container">
        <div class="main-header-bar">
            <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
            <div class="main-actions">
                <a href="index.php?page=createService" class="main-btn" id="create">Create Service</a>
            </div>
        </div>
        <form method="post" class="service-filter-form" action="index.php?page=allService">
            <select name="category">
                <?= $categoryOptions ?>
            </select>
            <label>
                Price: 
                <span id="minPriceDisplay"><?= htmlspecialchars($_GET['min_price'] ?? 0) ?></span> - 
                <span id="maxPriceDisplay"><?= htmlspecialchars($_GET['max_price'] ?? 50) ?></span>
            </label>
            <div id="price-slider"></div>
            <input type="hidden" name="min_price" id="minPriceInput" value="<?= htmlspecialchars($_GET['min_price'] ?? 0) ?>">
            <input type="hidden" name="max_price" id="maxPriceInput" value="<?= htmlspecialchars($_GET['max_price'] ?? 50) ?>">
            <select name="min_rating">
                <option value="0" <?= (($_GET['min_rating'] ?? '') == 0) ? 'selected' : '' ?>>Any Rating</option>
                <option value="1" <?= (($_GET['min_rating'] ?? '') == 1) ? 'selected' : '' ?>>1★ and up</option>
                <option value="2" <?= (($_GET['min_rating'] ?? '') == 2) ? 'selected' : '' ?>>2★ and up</option>
                <option value="3" <?= (($_GET['min_rating'] ?? '') == 3) ? 'selected' : '' ?>>3★ and up</option>
                <option value="4" <?= (($_GET['min_rating'] ?? '') == 4) ? 'selected' : '' ?>>4★ and up</option>
                <option value="5" <?= (($_GET['min_rating'] ?? '') == 5) ? 'selected' : '' ?>>5★</option>
            </select> 
            <select name="sort">
                <option value="asc" <?= (($_GET['sort'] ?? '') == 'asc') ? 'selected' : '' ?>>Price ↑</option>
                <option value="desc" <?= (($_GET['sort'] ?? '') == 'desc') ? 'selected' : '' ?>>Price ↓</option>
                <option value="newest" <?= (($_GET['sort'] ?? '') == 'newest' || ($_GET['sort'] ?? '') == 'date') ? 'selected' : '' ?>>Newest</option>
                <option value="oldest" <?= (($_GET['sort'] ?? '') == 'oldest') ? 'selected' : '' ?>>Oldest</option>
                <option value="rating_high" <?= (($_GET['sort'] ?? '') == 'rating_high') ? 'selected' : '' ?>>Rating ↑</option>
                <option value="rating_low" <?= (($_GET['sort'] ?? '') == 'rating_low') ? 'selected' : '' ?>>Rating ↓</option>
            </select>
            <button type="submit" class="main-btn">Filter</button>
        </form>
        <div class="service-list">
            <?php if (empty(trim($serviceCards))): ?>
                <p>No services found.</p>
            <?php else: ?>
                <div class="service-grid">
                    <?= $serviceCards ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="/assets/js/allServ.js"></script>
</body>
</html>
