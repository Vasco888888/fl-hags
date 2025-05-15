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