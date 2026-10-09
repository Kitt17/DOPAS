document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
     const formData = new FormData(this);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(response => {
    response = response.trim(); 
    console.log("Login response:", response);

    if (response === "admin") {
        window.location.href = "admindashboard1.php";
    } 
    else if (response === "user") {
        window.location.href = "publicdashboard1.php";
    } 
    else {
        alert(response);
    }
})

});