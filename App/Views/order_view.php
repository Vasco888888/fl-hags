<?php include __DIR__ . '/../../header.html'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="/assets/css/order.css">
</head>
<body>
<div class="order-details-container">
    <h2><?= htmlspecialchars($service['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
    <div class="service-images">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                <img src="<?= htmlspecialchars($img['path']) ?>" alt="Service Image">
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-image">No Image</div>
        <?php endif; ?>
    </div>
    <p><strong>Freelancer:</strong> <?= htmlspecialchars($freelancerName) ?></p>

    <?php if ($canReview): ?>
        <h3>Rate this Service</h3>
        <?php if (is_array($existingReview)): ?>
            <p>You already reviewed this service. Your rating: <?= $existingReview['rating'] ?> ★</p>
            <p><?= nl2br(htmlspecialchars($existingReview['comment'])) ?></p>
            <form method="post" onsubmit="return confirm('Delete your review?');">
                <button type="submit" name="delete_review" class="main-btn">Delete Review</button>
            </form>
        <?php else: ?>
            <form id="reviewForm" action="" method="post">
                <label for="rating">Rating:</label>
                <div id="starRating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <span class="star" data-value="<?= $i ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="rating" required>
                <br>
                <label for="comment">Comment:</label><br>
                <textarea name="comment" id="comment" rows="4" cols="40"></textarea>
                <br>
                <button type="submit" class="main-btn">Submit Review</button>
            </form>
            <script>
            // Simple star rating JS
            document.querySelectorAll('.star').forEach(function(star) {
                star.addEventListener('click', function() {
                    var rating = this.getAttribute('data-value');
                    document.getElementById('rating').value = rating;
                    document.querySelectorAll('.star').forEach(function(s) {
                        s.style.color = (s.getAttribute('data-value') <= rating) ? '#FFA500' : '#aaa';
                    });
                });
            });
            </script>
        <?php endif; ?>
    <?php else: ?>
        <p>This order is not completed yet. You can review after completion.</p>
    <?php endif; ?>

    <form action="index.php?page=user" method="post" style="margin-top:20px;">
        <button type="submit" class="main-btn">Back to Profile</button>
    </form>
    <script src="/assets/js/order.js"></script>
</div>
</body>
</html>