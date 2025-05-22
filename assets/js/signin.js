document.getElementById('signinForm').addEventListener('submit', function(e) {
    const forbidden = /['";<>-\s]/;
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
    if (!captchaCompleted) {
        e.preventDefault();
        captchaBox.focus();
        document.getElementById('errorMsg').textContent = "Please complete the CAPTCHA.";
    }
});

// Math CAPTCHA logic
const captchaBox = document.getElementById('captchaBox');
const captchaModal = document.getElementById('captchaModal');
const captchaQuestion = document.getElementById('captchaQuestion');
const captchaAnswer = document.getElementById('captchaAnswer');
const captchaSubmit = document.getElementById('captchaSubmit');
const captchaError = document.getElementById('captchaError');

let correctAnswer = null;
let captchaCompleted = false;

function generateCaptcha() {
    const a = Math.floor(Math.random() * 9) + 1;
    const b = Math.floor(Math.random() * 9) + 1;
    correctAnswer = a + b;
    captchaQuestion.textContent = `What is ${a} + ${b}?`;
    captchaAnswer.value = '';
    captchaError.textContent = '';
}

captchaBox.addEventListener('click', function(e) {
    if (!captchaCompleted) {
        e.preventDefault();
        generateCaptcha();
        captchaModal.style.display = 'flex';
        captchaAnswer.focus();
    }
});

captchaSubmit.addEventListener('click', function() {
    if (parseInt(captchaAnswer.value, 10) === correctAnswer) {
        captchaModal.style.display = 'none';
        captchaBox.checked = true;
        captchaCompleted = true;
    } else {
        captchaError.textContent = "Incorrect answer. Try again!";
        captchaAnswer.value = '';
        captchaAnswer.focus();
    }
});

// Prevent closing modal by clicking outside
captchaModal.addEventListener('click', function(e) {
    if (e.target === captchaModal) {
        captchaError.textContent = "Please answer the question!";
    }
});

// Background slider logic

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