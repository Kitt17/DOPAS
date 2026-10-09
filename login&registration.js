document.addEventListener("DOMContentLoaded", () => {

    /* ========= PASSWORD TOGGLES ========= */

    // REGISTER
    const showRegister = document.getElementById("showRegisterPassword");
    const regPassword = document.getElementById("regPassword");
    const confirmPassword = document.getElementById("confirmPassword");

    if (showRegister) {
        showRegister.addEventListener("change", () => {
            const type = showRegister.checked ? "text" : "password";
            regPassword.type = type;
            confirmPassword.type = type;
        });
    }

    // LOGIN
    const showLogin = document.getElementById("showLoginPassword");
    const loginPassword = document.getElementById("loginPassword");

    if (showLogin) {
        showLogin.addEventListener("change", () => {
            loginPassword.type = showLogin.checked ? "text" : "password";
        });
    }

    /* ========= SLIDING PANEL ========= */

    const signUpBtn = document.getElementById("signUpBtn");
    const signInBtn = document.getElementById("signInBtn");
    const container = document.getElementById("container");

    signUpBtn.addEventListener("click", () => {
        container.classList.add("right-panel-active");
    });

    signInBtn.addEventListener("click", () => {
        container.classList.remove("right-panel-active");
    });

});
