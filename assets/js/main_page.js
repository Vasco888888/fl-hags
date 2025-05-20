// Background slider logic with preloading
let current = 0;
const slider = document.getElementById('background-slider');

// Preload images
let loaded = 0;
const imgElements = [];
images.forEach((src, i) => {
    const img = new Image();
    img.src = src;
    img.onload = () => {
        loaded++;
        if (loaded === images.length) startSlider();
    };
    img.onerror = () => {
        // Remove broken images from the array
        images.splice(i, 1);
        if (loaded === images.length) startSlider();
    };
    imgElements.push(img);
});

function startSlider() {
    if (!images.length) return;
    slider.style.backgroundImage = `url('${images[current]}')`;
    setInterval(() => {
        current = (current + 1) % images.length;
        slider.style.backgroundImage = `url('${images[current]}')`;
    }, 5000);
}