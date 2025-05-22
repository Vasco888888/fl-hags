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

        <h3>Your Orders</h3>
        <div class="user-orders">
            <?php
            // Filter orders to show only those not completed
            $pendingOrders = array_filter($orders, function($order) {
                return empty($order['completed']) || $order['completed'] == 0;
            });
            ?>
            <?php if (empty($pendingOrders)): ?>
                <p>No pending orders found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($pendingOrders as $order): ?>
                        <li>
                            Service: <?= htmlspecialchars($order['service_id']) ?> |
                            Status: Pending
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <h3>Your Services</h3>
        <div class="user-services">
            <?php if (empty($services)): ?>
                <p>No services found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($services as $service): ?>
                        <li>
                            <?= htmlspecialchars($service['title']) ?> - €<?= number_format($service['base_price'], 2) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <form action="index.php?page=chat" method="get">
            <button type="submit" class="main-btn">Go to Chat</button>
        </form>
    </div>
    <script src="/assets/js/user.js"></script>
</body>
</html>