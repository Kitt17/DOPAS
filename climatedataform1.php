<?php ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Climate Data Form</title>

    <!-- Dashboard layout -->
    <link rel="stylesheet" href="dashboard.css">

    <!-- Shared form styling -->
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
        <a href="admindashboard1.php">Dashboard</a>
        <a href="denguecaseform1.php">Update Dengue Data</a>
        <a href="climatedataform1.php" class="active">Update Climate Data</a>
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

    <!-- ===== FORM WRAPPER ===== -->
    <div class="form-page">

        <div class="form-container">
            <h2>CLIMATE DATA FORM</h2>

            <form id="climateForm" action="climatedataform.php" method="POST" enctype="multipart/form-data">

                <!-- ===== OPTION 1 ===== -->
                <h3>Option 1: Upload CSV</h3>

                <div class="option-box">
                    <input type="file" name="climate" accept=".csv">
                </div>

                <hr>

                <!-- ===== OPTION 2 ===== -->
                <h3>Option 2: Manual Entry (Weekly Average)</h3>

                <label>Week</label>
                <select name="week">
                    <option value="">Select Week</option>
                    <script>
                        for (let i = 1; i <= 53; i++) {
                            document.write(`<option value="${i}">Week ${i}</option>`);
                        }
                    </script>
                </select>

                <label>Year</label>
                <input type="number" name="year" value="2026">

                <label>Mukim</label>
                <select name="mukimId">
                    <option value="">Select Mukim</option>
                    <option value="1">Mukim Sungai Buloh</option>
                    <option value="2">Mukim Petaling</option>
                    <option value="3">Mukim Damansara</option>
                    <option value="4">Mukim Bukit Raja</option>
                </select>

                <label>Avg Temperature (°C)</label>
                <input type="number" step="0.1" name="temperature">

                <label>Total Rainfall (mm)</label>
                <input type="number" step="0.1" name="rainfall">

                <label>Avg Humidity (%)</label>
                <input type="number" step="0.1" name="humidity">

                <button type="submit">Submit Climate Data</button>
            </form>
        </div>

    </div>
</main>

<script src="climatedataform.js"></script>
<script src="authentication.js"></script> 
</body>
</html>
