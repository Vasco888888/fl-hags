document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(e) {
        const forbidden = /['";<>-]/;
        let error = "";

        // Collect field values
        const name = this.querySelector('[name="name"]').value.trim();
        const username = this.querySelector('[name="username"]').value.trim();
        const email = this.querySelector('[name="email"]').value.trim();
        const password = this.querySelector('[name="password"]').value;

        // Check for empty fields (optional: only if you want all required)
        if (!name || !username || !email) {
            error = "Name, username, and email are required.";
        }

        // Check forbidden characters
        if (forbidden.test(name)) error = "Invalid characters detected in name.";
        if (forbidden.test(username)) error = "Invalid characters detected in username.";
        if (forbidden.test(email)) error = "Invalid characters detected in email.";
        if (password && forbidden.test(password)) error = "Invalid characters detected in password.";

        if (error) {
            e.preventDefault();
            document.getElementById('errorMsg').textContent = error;
        }
    });
});
