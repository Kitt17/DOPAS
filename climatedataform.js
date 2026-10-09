document.getElementById("climateForm").addEventListener("submit", function (e) {
    e.preventDefault(); // stop normal page reload

    const formData = new FormData(this);

    fetch("climatedataform.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "csv_success") {
            alert("CSV file has been submitted successfully!");
            this.reset();
        }
        else if (data.trim() === "manual_success") {
            alert("Climate data form has been submitted successfully!");
            this.reset();
        }
        else if (data.trim() === "error_empty") {
            alert("Error: Please upload a CSV file or fill in all manual fields.");
        }

        else {
            alert(data); // show error from PHP
        }
    })
    .catch(err => {
        alert("Something went wrong. Please try again.");
        console.error(err);
    });
});
