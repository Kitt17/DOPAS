<?php
session_start();
include "db.php";

/* ADMIN CHECK */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "unauthorized";
    exit;
}

$dataOption = $_POST['data_option'] ?? '';

/* Get current year */
$currentYear = date("Y");

/* ================= MANUAL ENTRY ================= */
if ($dataOption === "form") {

    $week = $_POST['week'] ?? '';
    $mukimId = $_POST['mukimId'] ?? '';
    $dengueCases = $_POST['dengueCases'] ?? '';

    if ($week === '' || $mukimId === '' || $dengueCases === '') {
        echo "error_empty";
        exit;
    }

    // Insert with Year
    $sql = "INSERT INTO denguerecords (`Week`, `Year`, mukimId, dengueCases)
            VALUES ('$week', '$currentYear', '$mukimId', '$dengueCases')";

    if (mysqli_query($conn, $sql)) {
        echo "manual_success";
    } else {
        echo "error_db: " . mysqli_error($conn);
    }
    exit;
}

/* ================= CSV UPLOAD ================= */
if ($dataOption === "csv") {

    if (empty($_FILES['csv_file']['name'])) {
        echo "error_empty";
        exit;
    }

    $file = fopen($_FILES['csv_file']['tmp_name'], "r");
    fgetcsv($file); // skip header

    while (($row = fgetcsv($file)) !== false) {
        $week = $row[0];
        $mukimId = $row[1];
        $cases = $row[2];

        // Insert with Year
        $sql = "INSERT INTO denguerecords (`Week`, `Year`, mukimId, dengueCases)
                VALUES ('$week', '$currentYear', '$mukimId', '$cases')";
        mysqli_query($conn, $sql);
    }

    fclose($file);
    echo "csv_success";
    exit;
}

echo "invalid_request";
