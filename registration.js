document.getElementById("registerForm").addEventListener("submit", function (e) {

    e.preventDefault();

    const password = document.querySelector('input[name="Password"]').value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    const emailInput = document.querySelector('input[name="Email"]').value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Password rules: Minimum 8 characters, At least one capital letter, At least one number.
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

    // Check email format
    if (!emailPattern.test(emailInput)) {
        alert("Invalid email format");
        return;
    }

    // Check password strength
    if (!passwordPattern.test(password)) {
        alert("Password must be at least 8 characters, include 1 capital letter and 1 number");
        return;
    }

    // Check if passwords match
    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return;
    }

    // Collect form data
    const formData = new FormData(this);

    // Send data to PHP using Fetch API
    fetch("registration.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data === "success") {
            alert("Registration successful!");
            window.location.href = "login&registration.html";
        } else {
            alert(data);
        }
    });
});
