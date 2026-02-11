window.addEventListener('DOMContentLoaded', function() {
    // Forbid special characters in cardholder name and address (but allow spaces)
    const forbidden = /['";<>-]/;

    const form = document.querySelector('.funds-form');
    const ccName = document.getElementById('ccName');
    const ccAddress = document.getElementById('ccAddress');
    const errorMsg = document.getElementById('errorMsg');

    form.addEventListener('submit', function(e) {
        // Only validate if card section is visible
        if (document.getElementById('cardSimSection').style.display !== 'none') {
            if (forbidden.test(ccName.value)) {
                e.preventDefault();
                alert("Invalid characters detected in Cardholder Name.");
                return;
            }
            if (forbidden.test(ccAddress.value)) {
                e.preventDefault();
                alert("Invalid characters detected in Card Address.");
                return;
            }
        }
    });

    const radios = document.querySelectorAll('input[name="amount"]');
    const otherRadio = document.getElementById('otherRadio');
    const otherAmountDiv = document.getElementById('otherAmountDiv');
    const otherAmountInput = document.getElementById('otherAmount');
    const cardSimSection = document.getElementById('cardSimSection');

    function toggleCardSection() {
        let show = false;
        radios.forEach(radio => {
            if (radio.checked) show = true;
        });
        cardSimSection.style.display = show ? 'block' : 'none';
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (otherRadio.checked) {
                otherAmountDiv.style.display = 'block';
                otherAmountInput.required = true;
            } else {
                otherAmountDiv.style.display = 'none';
                otherAmountInput.required = false;
                otherAmountInput.value = '';
            }
            toggleCardSection();
        });
    });

    // Show card section if already selected (on reload)
    toggleCardSection();

    form.addEventListener('submit', function(e) {
        // Only validate if card section is visible
        if (cardSimSection.style.display !== 'none') {
            const ccNumber = document.getElementById('ccNumber').value.trim();
            const ccExpiry = document.getElementById('ccExpiry').value;
            const ccCVV = document.getElementById('ccCVV').value.trim();

            // Card number: 16 digits
            if (!/^\d{16}$/.test(ccNumber)) {
                alert('Please enter a valid 16-digit card number.');
                e.preventDefault();
                return;
            }
            // Name
            if (ccName.value.trim().length < 2) {
                alert('Please enter the cardholder name.');
                e.preventDefault();
                return;
            }
            // Address
            if (ccAddress.value.trim().length < 4) {
                alert('Please enter the card address.');
                e.preventDefault();
                return;
            }
            // Expiry date: not in the past
            if (ccExpiry) {
                const today = new Date();
                const [expYear, expMonth] = ccExpiry.split('-').map(Number);
                const expDate = new Date(expYear, expMonth - 1, 1);
                if (expDate < new Date(today.getFullYear(), today.getMonth(), 1)) {
                    alert('Card expiry date cannot be in the past.');
                    e.preventDefault();
                    return;
                }
            } else {
                alert('Please enter the card expiry date.');
                e.preventDefault();
                return;
            }
            // CVV: 3 or 4 digits
            if (!/^\d{3,4}$/.test(ccCVV)) {
                alert('Please enter a valid CVV (3 or 4 digits).');
                e.preventDefault();
                return;
            }
        }

        // Handle "other" value
        if (otherRadio.checked) {
            if (!otherAmountInput.value || isNaN(otherAmountInput.value) || Number(otherAmountInput.value) <= 0) {
                alert('Please enter a valid amount.');
                e.preventDefault();
                return;
            }
            otherRadio.value = otherAmountInput.value;
        }
    });
});