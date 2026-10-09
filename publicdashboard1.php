<?php include "publicdashboard.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOPAS Public Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>


<!-- ===== LEFT SIDEBAR (HTML ONLY) ===== -->
<aside class="sidebar">
    <div class="brand">
        <img src="logo.png">
        <span>DOPAS</span>
    </div>

    <nav class="menu">
        <a href="publicdashboard1.php" class="active">Dashboard</a>
        <a href="search1.php">Search</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<div class="main" id="main-content">

    <!-- ===== TOP BAR ===== -->
    <div class="topbar">
        <span class="date"><?= date("d M Y"); ?></span>
        <div class="user">
            <div class="avatar">
                <?php echo strtoupper(substr($username, 0, 1)); ?>
            </div>
            <span><?php echo htmlspecialchars($username); ?></span>
        </div>
    </div>

    <!-- ===== WELCOME ===== -->
    <div class="welcome">
        <h2>Hi, <span class="username"><?php echo htmlspecialchars($username); ?></span></h2>
        <p>Dengue prediction overview for this week.</p>
    </div>

    <?php
    /* ================= GET LATEST PREDICTION ================= */
    include "db.php";

    $query = "
        SELECT predictedCases
        FROM predictionresults
        ORDER BY Year DESC, Week DESC
        LIMIT 1
    ";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $predicted = $row['predictedCases'] ?? 0;

    // ================= ALERT LEVEL =================
    if ($predicted <= 33) {
        $status = "SAFE";
        $statusClass = "safe";
    } elseif ($predicted <= 62) {
        $status = "AT RISK";
        $statusClass = "risk";
    } else {
        $status = "DANGER";
        $statusClass = "danger";
    }
    ?>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'alert_sent'): ?>
    <script>
        alert("Alert has been successfully sent.");
    </script>
<?php endif; ?>

    <!-- ===== DASHBOARD ROW  ===== -->
<div class="stats">

    <!-- PREDICTED CASES -->
    <div class="stat-card">

    <h4>Dengue Cases in your location</h4>

    <table class="prediction-table">
        <thead>
            <tr>
                <?php for ($w = $startWeek; $w <= $endWeek; $w++): ?>
                    <th>Week <?= $w ?></th>
                <?php endfor; ?>
                <th>Week <?= $currentWeek ?> (Predicted)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <?php for ($w = $startWeek; $w <= $endWeek; $w++): ?>
                    <td><?= $pastCases[$w] ?? '-' ?></td>
                <?php endfor; ?>
                <td><strong><?= $predictedCases ?></strong></td>
            </tr>
        </tbody>
    </table>

</div>

    <!-- STATUS -->
    <div class="stat-card <?php echo $statusClass; ?>">
        <h4>Status</h4>
        <span class="stat-status">
            <?php echo $status; ?>
        </span>

        <form action="send_alert.php" method="POST">
            <button type="submit" class="notify-btn">
                Notify Fogging Service
            </button>
        </form>
    </div>
   </div> 

    <!-- ===== EXTRA INFO CARDS ===== -->
    <div class="stats extra-cards">

        <!-- CARD 1 -->

        <a href="https://www.knowdengue.my/" target="_blank" class="info-card">
    <div class="info-card-text">
        <h3>Dengue Symptoms</h3>
        <p>How well do you know the symptoms of dengue?</p>

        <span class="info-btn">Find out more</span>
    </div>

    <div class="info-card-image">
        <img src="symptoms.png" alt="Dengue Symptoms">
    </div>
</a>

<a href="https://idengue.mysa.gov.my/did_/" target="_blank" class="info-card">
    <div class="info-card-text">
        <h3>iDengue Malaysia</h3>
        <p>Curious about dengue hotspots in Malaysia?</p>

        <span class="info-btn">Find out more</span>
    </div>

    <div class="info-card-image">
        <img src="idengue.png" alt="Dengue Symptoms">
    </div>
</a>

    </div>

</div>

<script src="publicdashboard.js"></script>
<script src="authentication.js"></script>
</body>
</html>