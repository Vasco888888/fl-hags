document.getElementById('signupForm').addEventListener('submit', function(e) {
    const forbidden = /['";<>-\s]/;
    let error = "";
    const fields = ['name', 'username', 'email', 'password'];
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

let current = 0;
const slider = document.getElementById('background-slider');

function setBackgroundWithPreload(src, callback) {
    const img = new Image();
    img.onload = function() {
        slider.style.backgroundImage = `url('${src}')`;
        if (callback) callback(); // Trigger next step after load
    };
    img.src = src;
}

if (images && images.length > 0) {
    setBackgroundWithPreload(images[0], () => {
        if (images.length > 1) {
            setInterval(() => {
                current = (current + 1) % images.length;
                setBackgroundWithPreload(images[current]);
            }, 5000);
        }
    });
}