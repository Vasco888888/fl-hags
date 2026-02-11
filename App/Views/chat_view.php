<?php include __DIR__ . '/Partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="/assets/css/chat.css">

</head>
<body>
<div class="chat-container">
    <div class="chat-sidebar">
        <h3>Conversations</h3>
        <ul class="conversation-list">
            <?php foreach ($conversationList as $conv): ?>
                <li>
                    <form method="post" action="index.php?page=chat" class="conversation-form" style="margin:0;">
                        <input type="hidden" name="conversation_id" value="<?= htmlspecialchars($conv['conversation_id']) ?>">
                        <button type="submit" class="conversation-btn<?= (isset($selected_conversation_id) && $selected_conversation_id == $conv['conversation_id']) ? ' active' : '' ?>">
                            <?= htmlspecialchars($conv['other_user_name']) ?>
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="chat-main">
        <div class="chat-header">
            <?= !empty($serviceTitle) ? htmlspecialchars($serviceTitle) : 'Select a conversation' ?>
        </div>
        <div class="chat-empty">Talk to your client or freelancer here!</div>
    </div>
    <?php if (!empty($order_id)): ?>
        <div class="transaction-btn">
            <form method="post" action="index.php?page=transaction" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
                <button type="submit" class="main-btn">Go to Transaction</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<script>
    var selectedConversationId = <?= json_encode($selected_conversation_id ?? null) ?>;
</script>
<script src="/assets/js/chat.js"></script>
</body>
</html>
