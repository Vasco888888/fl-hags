document.getElementById('loginBtn').addEventListener('click', function() {
    window.location.href = '/_PROJ/App/Controllers/signInController.php';
});

document.getElementById('signupBtn').addEventListener('click', function() {
    window.location.href = '/_PROJ/App/Controllers/signUpController.php';
});

// Background slider logic

let current = 0;
const slider = document.getElementById('background-slider');

function showNextImage() {
    slider.style.backgroundImage = `url('${images[current]}')`;
    current = (current + 1) % images.length;
}
showNextImage();
setInterval(showNextImage, 5000);