<?php include __DIR__ . '/../../header.html'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Funds</title>
    <link rel="stylesheet" href="/assets/css/addFunds.css">
</head>
<body>
    <form class="funds-form" method="post" action="">
        <h2>Add Funds</h2>
        <div class="funds-options">
            <label>
                <input type="radio" name="amount" value="10" required> €10
            </label>
            <label>
                <input type="radio" name="amount" value="20"> €20
            </label>
            <label>
                <input type="radio" name="amount" value="50"> €50
            </label>
            <label>
                <input type="radio" name="amount" value="other" id="otherRadio"> Other value
            </label>
            <div class="other-amount-input" id="otherAmountDiv">
                <input type="number" min="1" max="10000" step="1" name="other_amount" id="otherAmount" placeholder="Enter amount">
            </div>
        </div>

        <!-- Credit Card Simulation Section -->
        <div class="card-sim-section" id="cardSimSection" style="display:none;">
            <h3>Credit Card Details</h3>
            <div class="card-fields">
                <input type="text" name="cc_number" id="ccNumber" maxlength="19" placeholder="Card Number (16 digits)" pattern="\d{16}" required>
                <input type="text" name="cc_name" id="ccName" maxlength="40" placeholder="Cardholder Name" required>
                <input type="text" name="cc_address" id="ccAddress" maxlength="100" placeholder="Card Address" required>
                <input type="month" name="cc_expiry" id="ccExpiry" required>
                <input type="text" name="cc_cvv" id="ccCVV" maxlength="4" placeholder="CVV" pattern="\d{3,4}" required>
            </div>
        </div>

        <button type="submit" class="main-btn">Add Funds</button>
    </form>
    <script src="/assets/js/addFunds.js"></script>
</body>
</html>