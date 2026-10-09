<?php
include "admindashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOPAS Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
    <div class="brand">
        <img src="logo.png">
        <span>DOPAS</span>
    </div>

    <nav class="menu">
        <a href="admindashboard1.php" class="active">Dashboard</a>
        <a href="denguecaseform1.php">Update Dengue Data</a>
        <a href="climatedataform1.php">Update Climate Data</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<main class="main">

    <!-- TOP BAR -->
    <div class="topbar">
        <span class="date"><?= date("d M Y"); ?></span>
        <div class="user">
            <div class="avatar">AD</div>
        </div>
    </div>

    <!-- WELCOME -->
    <div class="welcome">
        <h2>Hi, <span class="highlight-name">Admin</span></h2>
        <p>System monitoring and alerts</p>
    </div>

    <br><br>

    <!-- ===== ALERT TABLE ===== -->
    <div class="card">
        <h3>User Alert Notifications</h3>
        <br>

 <!-- ===== DASHBOARD TABLE ROW ===== -->
<div class="dashboard-row">

    <!-- LEFT COLUMN : PREDICTION TABLE -->
    <div class="dashboard-column">
        <div class="card">
            <h3>Weekly Prediction Overview</h3>
            <br>

            <table class="prediction-table">
                <thead>
                    <tr>
                        <th>Mukim</th>
                        <th>Week</th>
                        <th>Year</th>
                        <th>Predicted Cases</th>
                        <th>Predicted Risk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($predictions->num_rows > 0): ?>
                        <?php while ($row = $predictions->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['mukimName']); ?></td>
                                <td><?= $row['Week']; ?></td>
                                <td><?= $row['Year']; ?></td>
                                <td><?= $row['predictedCases']; ?></td>
                                <td class="risk-<?= strtolower($row['riskLevel']); ?>">
                                <?= htmlspecialchars($row['riskLevel']); ?>
                            </td>            
                        </tr>        
                        <?php endwhile; ?>    
                        <?php else: ?>        
                            <tr>            
                            <td colspan="4" class="no-data">No predictions available</td>        
                        </tr>    
                        <?php endif; ?>
                    </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT COLUMN : ALERT TABLE -->
    <div class="dashboard-column">
        <div class="card">
            <h3>User Alert Notifications</h3>
            <br>

            <table class="alert-table">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>Mukim</th>
                        <th>Week</th>
                        <th>Year</th>
                        <th>Risk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($alerts->num_rows > 0): ?>
                        <?php while ($row = $alerts->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['cityName']); ?></td>
                                <td><?= htmlspecialchars($row['mukimName']); ?></td>
                                <td><?= $row['Week']; ?></td>
                                <td><?= $row['Year']; ?></td>
                                <td class="risk-<?= strtolower($row['riskLevel']); ?>">
                                    <?= htmlspecialchars($row['riskLevel']); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">No alerts received</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</main>

<script src="admindashboard.js"></script>
<script src="authentication.js"></script> 
</body>
</html>
