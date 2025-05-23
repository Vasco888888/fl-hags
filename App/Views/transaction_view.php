<?php include __DIR__ . '/../../header.html'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction</title>
    <link rel="stylesheet" href="/assets/css/order.css">
</head>
<body>
<div class="transaction-container">
    <h2>Transaction for Order #<?= htmlspecialchars($order_id) ?></h2>
    <?php if (!empty($_SESSION['transaction_msg'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['transaction_msg']) ?></div>
        <?php unset($_SESSION['transaction_msg']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['transaction_error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['transaction_error']) ?></div>
        <?php unset($_SESSION['transaction_error']); ?>
    <?php endif; ?>

    <?php if ($isFreelancer): ?>
        <h3>Request a Price</h3>
        <?php if ($priceRequest): ?>
            <p><strong>Requested Price:</strong> €<?= number_format($priceRequest['amount'], 2) ?></p>
            <?php if (!empty($priceRequest['description'])): ?>
                <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($priceRequest['description'])) ?></p>
            <?php endif; ?>
            <p>You have already requested a price for this order.</p>
        <?php else: ?>
            <form method="post">
                <label for="amount">Amount (€):</label>
                <input type="number" name="amount" id="amount" min="1" step="0.01" required>
                <br>
                <label for="desc">Description (optional):</label><br>
                <textarea name="desc" id="desc" rows="3" cols="40" placeholder="Describe the work, e.g. itemized list"></textarea>
                <br>
                <button type="submit" name="request_price" class="main-btn">Request Price</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <h3>Pay for the Service</h3>
        <?php if ($isCompleted): ?>
            <p class="success">This order has been paid and completed.</p>
        <?php elseif (!$priceRequest): ?>
            <p>The freelancer has not asked for a price yet.</p>
        <?php else: ?>
            <p><strong>Requested Price:</strong> €<?= number_format($priceRequest['amount'], 2) ?></p>
            <?php if (!empty($priceRequest['description'])): ?>
                <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($priceRequest['description'])) ?></p>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="amount" value="<?= htmlspecialchars($priceRequest['amount']) ?>">
                <button type="submit" name="pay" class="main-btn">Pay Now</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <form action="index.php?page=user" method="post" style="margin-top:20px;">
        <button type="submit" class="main-btn">Back to Profile</button>
    </form>
    <script src="/assets/js/transaction.js"></script>
</div>
</body>
</html>