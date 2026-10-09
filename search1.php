<?php include 'search.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Search Location</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="form.css">
</head>
<body>

<aside class="sidebar">
    <div class="brand">
        <img src="logo.png">
        <span>DOPAS</span>
    </div>
    <nav class="menu">
        <a href="publicdashboard1.php">Dashboard</a>
        <a href="search1.php" class="active">Search</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>

<main class="main">

    <div class="topbar">
        <span class="date"><?= date("d M Y"); ?></span>
        <div class="user">
        <div class="avatar">
            <?= strtoupper(substr($username, 0, 1)); ?>
        </div>
        <span><?= htmlspecialchars($username); ?></span>
</div>

    </div>

    <div class="form-page">
        <div class="form-container search-card-custom">

            <h2>DENGUE SEARCH</h2>

            <form method="GET">
            <h3>Search Location</h3>
            <div class="search-flex-box">
                <input type="text" name="location" placeholder="Enter city name" required>
                <button type="submit">Search</button>
            </div>
        </form>

            <?php if (isset($error)): ?>    
                <p class="form-error"><?= $error ?></p>
                <?php endif; ?>
                <?php if (isset($locationName)): ?>    
                    <hr class="form-divider">    
                    <p class="form-result"><strong>Location:</strong> <?= htmlspecialchars($locationName) ?></p>
                    <p class="form-result"><strong>Year:</strong> <?= $currentYear ?></p>    
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
                    <td><?= $predictedCases ?></td>
                </tr>
            </tbody>
        </table>
        <?php endif; ?>

        </div>
    </div>
</main>
<script src="authentication.js"></script> 
</body>
</html>
