window.addEventListener('DOMContentLoaded', function() {
    var priceSlider = document.getElementById('price-slider');
    var minInput = document.getElementById('minPriceInput');
    var maxInput = document.getElementById('maxPriceInput');
    var minDisplay = document.getElementById('minPriceDisplay');
    var maxDisplay = document.getElementById('maxPriceDisplay');

    // Get initial values from hidden inputs or default
    var minValue = parseInt(minInput ? minInput.value : 0, 10);
    var maxValue = parseInt(maxInput ? maxInput.value : 50, 10);

    if(priceSlider && typeof noUiSlider !== 'undefined') {
        noUiSlider.create(priceSlider, {
            start: [minValue, maxValue],
            connect: true,
            range: {
                'min': 0,
                'max': 50
            },
            step: 1,
            tooltips: [true, true],
            format: {
                to: function (value) { return Math.round(value); },
                from: function (value) { return Number(value); }
            }
        });

        priceSlider.noUiSlider.on('update', function(values, handle) {
            if(minInput) minInput.value = values[0];
            if(maxInput) maxInput.value = values[1];
            if(minDisplay) minDisplay.textContent = values[0];
            if(maxDisplay) maxDisplay.textContent = values[1];
        });
    }
});
