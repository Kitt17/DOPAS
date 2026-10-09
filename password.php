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

$userId = $_SESSION['userId'];
$current = $_POST['currentPassword'];
$new     = $_POST['newPassword'];
$confirm = $_POST['confirmPassword'];

if ($new !== $confirm) {
    header("Location: profile.php?error=password_mismatch");
    exit();
}

// Get current password hash
$stmt = $conn->prepare("SELECT Password FROM userdetails WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!password_verify($current, $row['Password'])) {
    header("Location: profile.php?error=wrong_password");
    exit();
}

// Hash new password
$newHash = password_hash($new, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE userdetails SET Password = ? WHERE userId = ?");
$update->bind_param("si", $newHash, $userId);
$update->execute();

header("Location: profile.php?success=password");
