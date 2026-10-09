<?php
session_start();
include "db.php";

$Email = trim($_POST['Email'] ?? '');
$Password = $_POST['Password'] ?? '';

// ===== ADMIN LOGIN =====
if ($Email === "admin@dopas.com" && $Password === "admin123") {
    $_SESSION['role'] = 'admin';
    echo "admin";
    exit;
}

// ===== USER LOGIN =====
$stmt = $conn->prepare("SELECT userId, Password FROM userdetails WHERE Email = ?");
$stmt->bind_param("s", $Email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($Password, $user['Password'])) {
        $_SESSION['role'] = 'user';
        $_SESSION['userId'] = $user['userId'];
        echo "user";
    } else {
        echo "Incorrect password";
    }
} else {
    echo "Email not found";
}

$stmt->close();
