<?php
/* ================== SESSION START ================== */
session_start();

/* ================== PREVENT BACK BUTTON ================== */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* ================== AUTH CHECK ================== */
if (!isset($_SESSION['userId'])) {
    header("Location: login&registration.html");
    exit();
}

/* ================== DATABASE ================== */
include "db.php";

/* ================== GET USER INFO ================== */
$userId = $_SESSION['userId'];

$query = "SELECT fullName FROM userdetails WHERE userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* ================== SAFETY CHECK ================== */
if (!$user) {
    session_destroy();
    header("Location: login&registration.html");
    exit();
}

$username = $user['fullName'];

/* ================= CURRENT WEEK & YEAR ================= */
$currentWeek = (int) date('W');
$currentYear = (int) date('o');

/* ================= USER LOCATION ================= */
$stmtLoc = $conn->prepare("
    SELECT mukimId 
    FROM userdetails 
    WHERE userId = ?
");
$stmtLoc->bind_param("i", $userId);
$stmtLoc->execute();
$locResult = $stmtLoc->get_result();
$locRow = $locResult->fetch_assoc();

if (!$locRow || !$locRow['mukimId']) {
    $mukimId = null;
} else {
    $mukimId = $locRow['mukimId'];
}

/* ================= LOCATION NAME ================= */
$locationName = "Unknown";

if ($mukimId) {
    $sqlLoc = "
        SELECT cityName 
        FROM locationrecords 
        WHERE mukimId = $mukimId
    ";
    $resLoc = mysqli_query($conn, $sqlLoc);
    $rowLoc = mysqli_fetch_assoc($resLoc);
    $locationName = $rowLoc['cityName'] ?? "Unknown";
}

/* ================= WEEK RANGE ================= */
$startWeek = max(1, $currentWeek - 5);
$endWeek   = $currentWeek - 1;

/* ================= PAST CASES ================= */
$pastCases = [];

if ($mukimId) {
    $sqlPast = "
        SELECT Week, dengueCases
        FROM denguerecords
        WHERE mukimId = $mukimId
          AND Year = $currentYear
          AND Week BETWEEN $startWeek AND $endWeek
        ORDER BY Week
    ";
    $resPast = mysqli_query($conn, $sqlPast);

    while ($row = mysqli_fetch_assoc($resPast)) {
        $pastCases[$row['Week']] = $row['dengueCases'];
    }
}

/* ================= PREDICTION ================= */
$predictedCases = "N/A";

if ($mukimId) {
    $sqlPred = "
        SELECT predictedCases
        FROM predictionresults
        WHERE mukimId = $mukimId
          AND Year = $currentYear
          AND Week = $currentWeek
        LIMIT 1
    ";
    $resPred = mysqli_query($conn, $sqlPred);
    $rowPred = mysqli_fetch_assoc($resPred);

    $predictedCases = $rowPred['predictedCases'] ?? "N/A";
}


?>
