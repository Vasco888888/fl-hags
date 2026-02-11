document.addEventListener('DOMContentLoaded', function() {
    // For freelancer price request
    const priceForm = document.querySelector('form[action=""][method="post"]');
    if (priceForm) {
        priceForm.addEventListener('submit', function(e) {
            const amountInput = document.getElementById('amount');
            if (amountInput && (parseFloat(amountInput.value) <= 0 || isNaN(amountInput.value))) {
                alert('Please enter a valid amount greater than zero.');
                e.preventDefault();
            }
        });
    }

    // For client payment
    const payBtn = document.querySelector('button[name="pay"]');
    if (payBtn) {
        payBtn.addEventListener('click', function(e) {
            const amount = this.form.querySelector('input[name="amount"]').value;
            if (!confirm(`Are you sure you want to pay €${amount}?`)) {
                e.preventDefault();
            }
        });
    }
});