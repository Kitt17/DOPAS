<?php
session_start();

/* ===== PREVENT BACK BUTTON ===== */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* ===== ADMIN AUTH CHECK ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login&registration.html");
    exit();
}

include "db.php";

// Fetch alerts
$alertsQuery = "
    SELECT 
        a.alertId,
        a.cityName,
        m.mukimName,
        a.Week,
        a.Year,
        a.riskLevel
    FROM alerts a
    JOIN mukim m ON a.mukimId = m.mukimId
    ORDER BY a.alertId DESC
";

$alerts = mysqli_query($conn, $alertsQuery);

// =================== FETCH LATEST WEEK PREDICTIONS ===================

// Get latest Year + Week
$latestQuery = "
    SELECT Year, Week
    FROM predictionresults
    ORDER BY Year DESC, Week DESC
    LIMIT 1
";
$latestResult = mysqli_query($conn, $latestQuery);
$latest = mysqli_fetch_assoc($latestResult);

$latestYear = $latest['Year'] ?? null;
$latestWeek = $latest['Week'] ?? null;

// Fetch predictions only for latest week
$predictionQuery = "
    SELECT 
        m.mukimName,
        p.Week,
        p.Year,
        p.predictedCases,
        p.riskLevel
    FROM predictionresults p
    JOIN mukim m ON p.mukimId = m.mukimId
    WHERE p.Year = '$latestYear' AND p.Week = '$latestWeek'
    ORDER BY m.mukimName ASC
";

$predictions = mysqli_query($conn, $predictionQuery);

if (!$predictions) {
    die("Prediction query failed: " . mysqli_error($conn));
}

if (!$alerts) {
    die("Query failed: " . mysqli_error($conn));
}
// =================== STATUS MESSAGES ===================
if (isset($_GET['status'])) {
    $status = $_GET['status'];

    if ($status == 'csv_success') {
        echo "<div class='alert alert-success'>CSV uploaded and prediction done successfully!</div>";
    } elseif ($status == 'manual_success') {
        echo "<div class='alert alert-success'>Manual entry saved and prediction done successfully!</div>";
    }
}

// =================== ACTION HANDLING ===================
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'view_cases') {
        // Your code to show past cases table
    }
}
?>
