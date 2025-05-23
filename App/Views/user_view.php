<?php include __DIR__ . '/../../header.html'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="/assets/css/user.css">
</head>
<body>
    <div class="user-profile-container">
        <h2>Your Profile</h2>
        <div class="user-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($user->getName()) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user->getUsername()) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
            <p><strong>Balance:</strong> € <?= number_format($user->getBalance(), 2) ?></p>
            <form action="index.php?page=edit" method="post" style="display:inline;">
                <button type="submit" class="main-btn">Edit Profile</button>
            </form>
        </div>

        <h3>Your Completed Orders</h3>
        <div class="user-orders">
            <?php if (empty($orders)): ?>
                <p>you have no completed orders.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($orders as $order): ?>
                        <li>
                            <?php $_SESSION['order_id'] = $order['order_id'] ?>
                            <form action="index.php?page=order" method="post" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                Service: <?= htmlspecialchars($order['service_id']) ?>
                                <button type="submit" class="main-btn" style="margin-left:10px;">View</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <h3>Your Services</h3>
        <div class="user-services">
            <?php
            require_once __DIR__ . '/../Models/Service_Media.php';
            require_once __DIR__ . '/../Models/Review.php';
            $mediaModel = new Service_Media();
            $reviewModel = new Review();
            ?>
            <?php if (empty($services)): ?>
                <p>You Havent Created a Service</p>
            <?php else: ?>
                <ul class="service-list">
                    <?php foreach ($services as $service): ?>
                        <li class="service-item" style="display:flex;align-items:center;gap:12px;">
                            <?php
                                // Get main image path
                                $imgPath = $mediaModel->getMainImage($service['service_id']);
                                // Get average rating
                                $rating = $reviewModel->getAverageRating($service['service_id']);
                            ?>
                            <?php if ($imgPath): ?>
                                <img src="<?= htmlspecialchars($imgPath) ?>" alt="Service Image" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                            <?php else: ?>
                                <div style="width:48px;height:48px;background:#eee;border-radius:6px;display:flex;align-items:center;justify-content:center;">No Image</div>
                            <?php endif; ?>
                            <span style="font-weight:500;"><?= htmlspecialchars($service['title']) ?></span>
                            <span style="color:#FFA500;margin-left:8px;">★ <?= number_format($rating, 1) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <form action="index.php?page=chat" method="post" style="display:inline;">
            <button type="submit" class="main-btn">Go to Chat</button>
        </form>
        <form action="index.php?page=allService" method="post" style="display:inline;">
            <button type="submit" class="main-btn" style="margin-left:10px;">Back to All Services</button>
        </form>
    </div>
    <script src="/assets/js/user.js"></script>
</body>
</html> 