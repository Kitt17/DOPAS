<?php ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dengue Data Form</title>

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
        <a href="admindashboard1.php">Dashboard</a>
        <a href="denguecaseform1.php" class="active">Update Dengue Data</a>
        <a href="climatedataform1.php">Update Climate Data</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<main class="main">

    <div class="topbar">
        <span class="date"><?= date("d M Y"); ?></span>
        <div class="user">
            <div class="avatar">AD</div>
        </div>
    </div>

    <div class="form-page">
        <div class="form-container">
            <h2>DENGUE DATA FORM</h2>

            <form id="dengueForm" action="dengueform.php" method="POST" enctype="multipart/form-data">

             <!-- REQUIRED -->
                <input type="hidden" name="data_option" id="data_option" value="form">


                <!-- ===== OPTION 1 ===== -->
                <h3>Option 1: Upload CSV</h3>

                <div class="option-box">
                    <input type="file" name="csv_file" accept=".csv">
                </div>

                <hr>

                <!-- ===== OPTION 2 ===== -->
                <h3>Option 2: Manual Entry</h3>

                <label>Week</label>
                <select name="week">
                    <option value="">Select Week</option>
                    <script>
                        for (let i = 1; i <= 53; i++) {
                            document.write(`<option value="${i}">Week ${i}</option>`);
                        }
                    </script>
                </select>

                <label>Mukim</label>
                <select name="mukimId">
                    <option value="">Select Mukim</option>
                    <option value="1">Mukim Sungai Buloh</option>
                    <option value="2">Mukim Petaling</option>
                    <option value="3">Mukim Damansara</option>
                    <option value="4">Mukim Bukit Raja</option>
                </select>

                <label>Number of Dengue Cases</label>
                <input type="number" name="dengueCases" min="0">

                <button type="submit">Submit Dengue Data</button>
            </form>
        </div>
    </div>

</main>

<script src="denguecaseform.js"></script>

<script src="authentication.js"></script> 
</body>
</html>
