<?php
session_start();

/* ===== PREVENT BACK BUTTON ===== */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['userId'])) {
    header("Location: login&registration.html");
    exit();
}

include "db.php";
$userId   = $_SESSION['userId'];
$fullName = trim($_POST['fullName'] ?? '');
$email    = trim($_POST['Email'] ?? '');
$address  = trim($_POST['Address'] ?? '');
$postcode = trim($_POST['Postcode'] ?? '');

// ===== BASIC EMPTY CHECK =====
if (
    empty($fullName) ||
    empty($email) ||
    empty($address) ||
    empty($postcode)
) {
    header("Location: profile.php?error=missing_fields");
    exit();
}

// ===== EMAIL FORMAT CHECK =====
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: profile.php?error=invalid_email");
    exit();
}

// ===== POSTCODE VALIDATION (Malaysia: 5 digits) =====
if (!ctype_digit($postcode) || strlen($postcode) !== 5) {
    header("Location: profile.php?error=invalid_postcode");
    exit();
}

$stmt = $conn->prepare("
    UPDATE userdetails 
    SET fullName = ?, Email = ?, Address = ?, Postcode = ?
    WHERE userId = ?
");
$stmt->bind_param("sssii", $fullName, $email, $address, $postcode, $userId);

if ($stmt->execute()) {
    header("Location: profile.php?success=updated");
} else {
    header("Location: profile.php?error=update_failed");
    exit();
}
