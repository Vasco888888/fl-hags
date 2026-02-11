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
