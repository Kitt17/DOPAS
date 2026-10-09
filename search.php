<?php
session_start();

/* ===== PREVENT BACK BUTTON ===== */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['userId'])) {
    header("Location: login&registration.html");
    exit();
}

include "db.php";

// ================= GET LOGGED IN USER NAME =================
$userId = $_SESSION['userId'];

$stmtUser = $conn->prepare("SELECT fullName FROM userdetails WHERE userId = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$userRow = $stmtUser->get_result()->fetch_assoc();

$username = $userRow['fullName'] ?? 'User';


/* ================= GET INPUT ================= */
$location = $_GET['location'] ?? '';

if (!$location) {
    return;
}

$location = mysqli_real_escape_string($conn, $location);

/* ================= CURRENT WEEK & YEAR ================= */
$currentWeek = (int) date('W');
$currentYear = (int) date('o');

/* ================= GET MUKIM ID ================= */
$sqlMukim = "
    SELECT mukimId, cityName
    FROM locationrecords
    WHERE cityName LIKE '%$location%'
    LIMIT 1
";
$resMukim = mysqli_query($conn, $sqlMukim);
$mukim = mysqli_fetch_assoc($resMukim);

if (!$mukim) {
    die("Location not found in database.");
}

$mukimId = $mukim['mukimId'];
$locationName = $mukim['cityName'];

/* ================= WEEK RANGE ================= */
$startWeek = max(1, $currentWeek - 5);
$endWeek   = $currentWeek - 1;

/* ================= PAST DENGUE CASES ================= */
$sqlPast = "
    SELECT Week, dengueCases
    FROM denguerecords
    WHERE mukimId = $mukimId
      AND Year = $currentYear
      AND Week BETWEEN $startWeek AND $endWeek
    ORDER BY Week
";
$resPast = mysqli_query($conn, $sqlPast);

$pastCases = [];
while ($row = mysqli_fetch_assoc($resPast)) {
    $pastCases[$row['Week']] = $row['dengueCases'];
}

/* ================= PREDICTION ================= */
$sqlPred = "
    SELECT predictedCases, riskLevel
    FROM predictionresults
    WHERE mukimId = $mukimId
      AND Year = $currentYear
      AND Week = $currentWeek
    LIMIT 1
";
$resPred = mysqli_query($conn, $sqlPred);
$prediction = mysqli_fetch_assoc($resPred);

$predictedCases = $prediction['predictedCases'] ?? 'N/A';
$riskLevel = $prediction['riskLevel'] ?? 'N/A';
?>
