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
                <div class="service-slider">
                    <?php foreach ($services as $service): ?>
                        <div class="service-item">
                            <?php
                                $imgPath = $mediaModel->getMainImage($service['service_id']);
                                $rating = $reviewModel->getAverageRating($service['service_id']);
                            ?>
                            <?php if ($imgPath): ?>
                                <img src="<?= htmlspecialchars($imgPath) ?>" alt="Service Image" class="service-thumb">
                            <?php else: ?>
                                <div class="service-thumb-placeholder">No Image</div>
                            <?php endif; ?>
                            <span class="service-title"><?= htmlspecialchars($service['title']) ?></span>
                            <span class="service-rating">★ <?= number_format($rating, 1) ?></span>
                            <form action="index.php?page=service" method="post" class="service-view-btn">
                                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['service_id']) ?>">
                                <button type="submit" class="main-btn">View Service</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
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