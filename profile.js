// ===== PROFILE PAGE ALERT HANDLER =====

// Read URL parameters
const params = new URLSearchParams(window.location.search);

// Error messages
if (params.has("error")) {
    const error = params.get("error");

    if (error === "password_mismatch") {
        alert("New password and confirm password do not match.");
    }
    else if (error === "wrong_password") {
        alert("Current password is incorrect.");
    }
    else if (error === "update_failed") {
        alert("Failed to update profile. Please try again.");
    }
    else if (error === "missing_fields") {
        alert("Please fill in all required fields.");
    }
    else if (error === "invalid_email") {
        alert("Please enter a valid email address.");
    }
    else if (error === "invalid_postcode") {
        alert("Postcode must contain exactly 5 digits.");
    }

}

// Success messages
if (params.has("success")) {
    const success = params.get("success");

    if (success === "password") {
        alert("Password updated successfully.");
    }
    else if (success === "updated") {
        alert("Profile updated successfully.");
    }
}
