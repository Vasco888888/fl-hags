document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#starRating .star');
    const ratingInput = document.getElementById('rating');
    if (stars.length && ratingInput) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                ratingInput.value = value;
                stars.forEach(s => s.style.color = (s.getAttribute('data-value') <= value) ? '#FFA500' : '#ccc');
            });
        });
    }

    // Optional: Prevent submit if no rating
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            if (!ratingInput.value) {
                alert('Please select a rating.');
                e.preventDefault();
            }
        });
    }
});