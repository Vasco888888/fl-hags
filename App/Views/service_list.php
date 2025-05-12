<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Services</title>
</head>
<body>
    <h1>Available Freelance Services</h1>
</body>
</html>

<?php foreach ($services as $service): ?>
    <div>
        <h2><?= htmlspecialchars($service['title']); ?></h2>
        <p><?= htmlspecialchars($service['description']); ?></p>
        <strong>€ <?= htmlspecialchars($service['base_price']); ?></strong>
    </div>
<?php endforeach; ?>

