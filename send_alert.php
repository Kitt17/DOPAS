<?php
session_start();
include "db.php";
$userId = $_SESSION['userId']; 

$stmt = $conn->prepare("
    SELECT locationId 
    FROM userdetails 
    WHERE userId = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$locationId = $user['locationId'];

// ----------------------------------------------------
// 2. Get cityName and mukimId using locationId
// (Ensures correct city name appears in admin alerts)
// ----------------------------------------------------
$stmt = $conn->prepare("
    SELECT cityName, mukimId 
    FROM locationrecords 
    WHERE locationId = ?
");
$stmt->bind_param("i", $locationId);
$stmt->execute();
$result = $stmt->get_result();
$loc = $result->fetch_assoc();

// 1. Get user's city (from session or login info)
$cityName = $loc['cityName']; 
$mukimId  = $loc['mukimId'];  
$stmt = $conn->prepare("SELECT mukimId FROM locationrecords WHERE cityName = ?");
$stmt->bind_param("s", $cityName);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$mukimId = $row['mukimId'];

// 3. Get latest prediction for that mukim
$stmt = $conn->prepare("
    SELECT riskLevel, Week, Year
    FROM predictionresults
    WHERE mukimId = ?
    ORDER BY Year DESC, Week DESC
    LIMIT 1
");
$stmt->bind_param("i", $mukimId);
$stmt->execute();
$result = $stmt->get_result();
$pred = $result->fetch_assoc();

$riskLevel = $pred['riskLevel'];
$week = $pred['Week'];
$year = $pred['Year'];

// 4. Insert alert including city name
$stmt = $conn->prepare("
    INSERT INTO alerts (mukimId, cityName, Week, Year, riskLevel)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("isiss", $mukimId, $cityName, $week, $year, $riskLevel);
$stmt->execute();

header("Location: publicdashboard1.php?status=alert_sent");
exit;
?>
