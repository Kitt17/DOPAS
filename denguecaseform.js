document.getElementById("dengueForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const csvFile = form.querySelector('input[name="csv_file"]').files.length;
    const dataOption = document.getElementById("data_option");

    // Decide mode
    if (csvFile > 0) {
        dataOption.value = "csv";
        formData.set("data_option", "csv");
    } else {
        dataOption.value = "form";
        formData.set("data_option", "form");
    }

    fetch("denguecaseform.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        data = data.trim();

        if (data === "manual_success") {
            alert("Dengue data submitted successfully!");
            form.reset();
        }
        else if (data === "csv_success") {
            alert("CSV uploaded successfully!");
            form.reset();
        }
        else if (data === "error_empty") {
            alert("Please upload a CSV or fill in all manual fields.");
        }
        else {
            alert("ERROR: " + data);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Something went wrong.");
    });
});
