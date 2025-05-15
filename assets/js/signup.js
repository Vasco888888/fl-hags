document.getElementById('signupForm').addEventListener('submit', function(e) {
    const forbidden = /['";<>-]/;
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