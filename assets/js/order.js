document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            const comment = document.getElementById('comment').value;
            const forbidden = /['";<>]/;
            if (forbidden.test(comment)) {
                e.preventDefault();
                alert("Invalid characters detected in comment.");
            }
        });
    }
});