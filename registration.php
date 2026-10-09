<?php
include "db.php";

// Collect POST data
$fullName = $_POST['fullName'] ?? '';
$email    = $_POST['Email'] ?? '';
$password = $_POST['Password'] ?? '';
$address  = $_POST['Address'] ?? '';
$postcode = $_POST['Postcode'] ?? '';

if (!$fullName || !$email || !$password || !$address || !$postcode) {
    die("All fields are required");
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ----------------------------------------------------
// Find locationId AND mukimId based on address
// (Added for alert system – links user to exact location)
// ----------------------------------------------------
$addressEscaped = mysqli_real_escape_string($conn, $address);

$locationQuery = "
    SELECT locationId, mukimId 
    FROM locationrecords 
    WHERE '$addressEscaped' LIKE CONCAT('%', cityName, '%') 
    LIMIT 1
";
$locationResult = mysqli_query($conn, $locationQuery);

if ($locationResult && mysqli_num_rows($locationResult) > 0) {
    $row = mysqli_fetch_assoc($locationResult);

    $locationId = $row['locationId']; // ✅ NEW: exact city record
    $mukimId    = $row['mukimId'];    // existing logic (unchanged)

} else {
    // If address does not match any known city
    $locationId = NULL;
    $mukimId    = NULL;
}


// ----------------------------------------------------
// Insert user with mukimId
// ----------------------------------------------------
$sql = "INSERT INTO userdetails 
        (fullName, Email, Password, Address, Postcode, locationId, mukimId)
        VALUES 
        ('$fullName', '$email', '$hashedPassword', '$address', '$postcode', ";
$sql .= ($locationId === NULL ? "NULL" : "'$locationId'") . ", ";
$sql .= ($mukimId === NULL ? "NULL" : "'$mukimId'");
$sql .= ")";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    echo "Database error: " . mysqli_error($conn);
}
?>
