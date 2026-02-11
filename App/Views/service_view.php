<?php include __DIR__ . '/Partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($service['title']); ?> | Service Details</title>
    <link rel="stylesheet" href="/assets/css/service.css">

</head>
<body>
<div class="service-details-container">
    <h1 class="service-title"><?php echo htmlspecialchars($service['title']); ?></h1>
    <div class="service-meta">
        <span class="freelancer-name">By: <?php echo htmlspecialchars($freelancerName); ?></span>
        <span class="service-price">Base Price: $<?php echo htmlspecialchars($service['base_price']); ?></span>
        <span class="service-category">Category: <?php echo htmlspecialchars($categoryName); ?></span>        
    </div>
    <div class="service-images">
        <?php if ($media): ?>
            <?php foreach ($media as $m): ?>
                <div class="service-image">
                <img src="<?php echo htmlspecialchars($m['path']); ?>" alt="Service Image" class="service-img">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No images for this service.</p>
        <?php endif; ?>
    </div>
    <div class="service-description">
        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
    </div> 
    <div class="service-actions">
        <?php if (
            isset($_SESSION['user_id']) && 
            $freelancer_id != $_SESSION['user_id']
        ): ?>
        <form action="index.php?page=chat" method="post" style="display:inline;">
            <input type="hidden" name="freelancer_id" value="<?= htmlspecialchars($freelancer_id) ?>">
            <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">
            <input type="hidden" name="start_conversation" value="1">
            <button type="submit" class="chat-btn">Contact Freelancer</button>
        </form>
        <?php endif; ?>
    </div>
    <div class="service-reviews">
        <h2>Reviews</h2>
        <?php if (!empty($allReviews)): ?>
            <?php foreach ($allReviews as $review): ?>
                <div class="review">
                    <div class="review-rating">
                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                            <span style="color:#FFA500;">&#9733;</span>
                        <?php endfor; ?>
                        <?php for ($i = $review['rating']; $i < 5; $i++): ?>
                            <span style="color:#ccc;">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <div class="review-comment">
                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                    </div>
                    <div class="review-meta">
                        <small>
                            By: <?= htmlspecialchars(User::getNameById($review['client_id'])) ?>
                            on <?= htmlspecialchars(date('Y-m-d', strtotime($review['date_pub']))) ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet for this service.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
