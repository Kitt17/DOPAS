/* ================= GLOBAL AUTH GUARD ================= */
(function () {

    // Prevent BFCache restore
    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            window.location.href = "login&registration.html";
        }
    });

    // Trap back button
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    window.addEventListener("popstate", function () {
        window.location.href = "login&registration.html";
    });

})();
