document.getElementById('signinForm').addEventListener('submit', function(e) {
    const forbidden = /['";<>-]/;
    let error = "";
    const fields = ['username', 'password'];
    fields.forEach(function(field) {
        const value = document.querySelector(`[name="${field}"]`).value.trim();
        if (!value) {
            error = "All fields are required.";
        }
        if (forbidden.test(value)) {
            error = "Invalid characters detected in " + field + ".";
        }
    });
    if (error) {
        e.preventDefault();
        document.getElementById('errorMsg').textContent = error;
    }
});

// Background slider logic

document.addEventListener('DOMContentLoaded', function() {
    let current = 0;
    const slider = document.getElementById('background-slider');

    function showNextImage() {
        if (!images || images.length === 0) return;
        slider.style.backgroundImage = `url('${images[current]}')`;
        current = (current + 1) % images.length;
    }
    showNextImage();
    setInterval(showNextImage, 5000);
});