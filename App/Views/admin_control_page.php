<?php
    include __DIR__ . '/Partials/header.php';
    // Assume $allUsers and $allServices are provided by the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel</title>
    <link rel="stylesheet" href="/assets/css/allServ.css">
    <style>
        .admin-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .admin-table th, .admin-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .admin-table th { background: #f0f0f0; }
        .admin-action-btn { background: #c62828; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .admin-action-btn:hover { background: #b71c1c; }
    </style>
</head>
<body>
    <div class="main-container">
        <h1>Admin Control Panel</h1>

        <h2>All Users</h2>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        <?php foreach ($allUsers as $user): ?>
            <?php if (in_array($user["id"], $adminIds)) { continue; } ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <form method="post" action="index.php?page=adminControl" style="display:inline;">
                        <input type="hidden" name="ban_user_id" value="<?= htmlspecialchars($user['id']) ?>">
                        <button type="submit" class="admin-action-btn" onclick="return confirm('Ban this user?')">Ban</button>
                    </form>
                    <form method="post" action="index.php?page=adminControl" style="display:inline; margin-left:5px;">
                        <input type="hidden" name="elevate_user_id" value="<?= htmlspecialchars($user['id']) ?>">
                        <button type="submit" class="admin-action-btn" style="background:#1976d2;" onclick="return confirm('Elevate this user to admin?')">Elevate User</button>
                    </form>                    
                </td>
            </tr>
        <?php endforeach; ?>
        </table>

        <h2>All Services</h2>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($allServices as $service): ?>
            <tr>
                <td><?= htmlspecialchars($service['service_id']) ?></td>
                <td><?= htmlspecialchars($service['title']) ?></td>
                <td><?= htmlspecialchars($categoryNames[$service['category_id']] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($service['owner_username']) ?></td>
                <td>
                    <form method="post" action="index.php?page=adminControl" style="display:inline;">
                        <input type="hidden" name="delete_service_id" value="<?= htmlspecialchars($service['service_id']) ?>">
                        <button type="submit" class="admin-action-btn" onclick="return confirm('Delete this service?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>Add New Category</h2>
        <form method="post" action="index.php?page=adminControl" style="margin-bottom:30px;">
            <input type="text" name="category_name" placeholder="Category name" required>
            <button type="submit" class="admin-action-btn" style="background:#1976d2;">Add Category</button>
        </form>
    </div>
</body>
</html>
