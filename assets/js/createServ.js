window.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('serviceForm');
    const title = document.getElementById('title');
    const desc = document.getElementById('description');
    const price = document.getElementById('base_price');
    const submitBtn = document.getElementById('submitBtn');

    // Forbid special characters but allow spaces
    const forbidden = /['";<>-]/;

    function checkForm() {
        let error = "";
        if (!title.value.trim() || !desc.value.trim() || !price.value) {
            submitBtn.disabled = true;
            return;
        }
        if (forbidden.test(title.value)) {
            error = "Title contains invalid characters.";
        }
        if (forbidden.test(desc.value)) {
            error = "Description contains invalid characters.";
        }
        submitBtn.disabled = !!error;
        // Show error message if you have an error display element
        let errorMsg = document.getElementById('errorMsg');
        if (!errorMsg) {
            errorMsg = document.createElement('div');
            errorMsg.id = 'errorMsg';
            errorMsg.style.color = 'red';
            form.insertBefore(errorMsg, form.firstChild);
        }
        errorMsg.textContent = error;
    }

    title.addEventListener('input', checkForm);
    desc.addEventListener('input', checkForm);
    price.addEventListener('input', checkForm);

    form.addEventListener('submit', function(e) {
        if (forbidden.test(title.value) || forbidden.test(desc.value)) {
            e.preventDefault();
            checkForm();
        }
    });

    // Initial check
    checkForm();
});