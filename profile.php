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

$userId = $_SESSION['userId'];

$stmt = $conn->prepare("SELECT fullName, Email, Address, Postcode FROM userdetails WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>

    <!-- Dashboard layout -->
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="form.css">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
    <div class="brand">
        <img src="logo.png">
        <span>DOPAS</span>
    </div>

    <nav class="menu">
        <a href="publicdashboard1.php">Dashboard</a>
        <a href="search1.php">Search</a>
        <a href="profile.php" class="active"> Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<main class="main">

    <div class="topbar">
        <span class="date"><?= date("d M Y"); ?></span>
        <div class="user">
            <div class="avatar">
                <?= strtoupper(substr($user['fullName'], 0, 1)); ?>
            </div>
            <span><?= htmlspecialchars($user['fullName']); ?></span>
        </div>
    </div>

    <div class="form-page">
    <div class="form-container profile-wide"> <h2>MY PROFILE</h2>

        <div class="profile-flex-wrapper"> <form action="updateprofile.php" method="POST" class="profile-column">
            <h3>Update details</h3>
                <label>Full Name</label>
                <input type="text" name="fullName" value="<?= htmlspecialchars($user['fullName']) ?>" required>

                <label>Email</label>
                <input type="email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>" required>

                <label>Address</label>
                <input type="text" name="Address" value="<?= htmlspecialchars($user['Address']) ?>" required>

                <label>Postcode</label>
                <input type="number" name="Postcode" value="<?= htmlspecialchars($user['Postcode']) ?>" required>

                <button type="submit">Update Profile</button>
            </form>

            <form action="password.php" method="POST" class="profile-column">
                <h3>Change Password</h3>

                <label>Current Password</label>
                <input type="password" name="currentPassword" required>

                <label>New Password</label>
                <input type="password" name="newPassword" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirmPassword" required>

                <button type="submit">Change Password</button>
            </form>

        </div> </div>
</div>

</main>
<script src="profile.js"></script>
<script src="authentication.js"></script> 

</body>
</html>
