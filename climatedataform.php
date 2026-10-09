<?php
session_start();

/* ===== PREVENT BACK BUTTON ===== */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* ===== ADMIN AUTH CHECK ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "unauthorized";
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";
require "email_alert.php";

// ================= CHECK FORM =================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Check CSV upload
$csvUploaded = !empty($_FILES['climate']['name']);

// Check manual inputs
$manualFilled =
    !empty($_POST['week']) &&
    !empty($_POST['year']) &&
    !empty($_POST['mukimId']) &&
    $_POST['temperature'] !== '' &&
    $_POST['rainfall'] !== '' &&
    $_POST['humidity'] !== '';

if (!$csvUploaded && !$manualFilled) {
    echo "error_empty";
    exit;
}

    if (!empty($_FILES['climate']['name'])) {


        $file = fopen($_FILES['climate']['tmp_name'], "r");

        fgetcsv($file);


        while (($row = fgetcsv($file)) !== false) {

            $mukimId     = (int)$row[0];
            $week        = (int)$row[1];
            $year        = (int)$row[2];
            $temperature = (float)$row[3];
            $rainfall    = (float)$row[4];
            $humidity    = (float)$row[5];

            // ================= INSERT CLIMATE DATA =================
            $stmt = $conn->prepare("
                INSERT INTO climatedata
                (mukimId, Week, Year, Temperature, Rainfall, Humidity)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "iiiddd",
                $mukimId,
                $week,
                $year,
                $temperature,
                $rainfall,
                $humidity
            );
            $stmt->execute();

            // ================= FETCH PREVIOUS DENGUE CASES FOR LAG =================
            $prev_week = $week - 1;
            if ($prev_week < 1) {
                $prev_week = 1; // or handle year change if needed
            }

            $stmt_prev = $conn->prepare("SELECT dengueCases FROM denguerecords WHERE mukimId=? AND Week=? AND Year=?");
            $stmt_prev->bind_param("iii", $mukimId, $prev_week, $year);
            $stmt_prev->execute();
            $result_prev = $stmt_prev->get_result();

            if ($row_prev = $result_prev->fetch_assoc()) {
                $previous_cases = (int)$row_prev['dengueCases'];
            } else {
                $previous_cases = 0; 
            }

            // ================= PREPARE FEATURES FOR PREDICTION =================
            $features = [
                'Temperature' => $temperature,
                'Rainfall' => $rainfall,
                'Humidity' => $humidity,
            ];

            // ================= CALL PYTHON PREDICTION SCRIPT =================
           $python = "C:\\Users\\Kirtnaa\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";

           $script = "C:\\xampp\\htdocs\\PSM\\model\\predict_single.py";


           $command = "\"$python\" \"$script\" $mukimId $week $year";

           $python_output = shell_exec($command);

            error_log("Python script output: " . $python_output);
            $predictedCases = (int)trim($python_output);
            

            // ================= DETERMINE RISK LEVEL =================
            if ($predictedCases < 33) {
                $riskLevel = "LOW";
            } elseif ($predictedCases < 62) {
                $riskLevel = "MEDIUM";
            } else {
                $riskLevel = "HIGH";
            }

            // ================= SAVE PREDICTION =================
            $stmt_pred = $conn->prepare("
                INSERT INTO predictionresults
                (mukimId, Week, Year, predictedCases, riskLevel)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt_pred->bind_param(
                "iiiis",
                $mukimId,
                $week,
                $year,
                $predictedCases,
                $riskLevel
            );
            $stmt_pred->execute();

            // ================= SEND EMAIL ALERT =================
            if ($riskLevel !== "LOW") {

                $users = $conn->prepare("
                    SELECT Email FROM userdetails WHERE mukimId = ?
                ");
                $users->bind_param("i", $mukimId);
                $users->execute();
                $result = $users->get_result();

                while ($user = $result->fetch_assoc()) {
                    sendDengueAlert(
                        $user['Email'],
                        "Mukim $mukimId",
                        $predictedCases,
                        $riskLevel
                    );
                }
            }
        }

        fclose($file);
        echo "csv_success";
        exit;
        

    }

    /*
    =====================================================
    =============== MANUAL ENTRY PATH ===================
    =====================================================
    */

    else {

        $week    = (int) $_POST['week'];
        $year    = (int) $_POST['year'];
        $mukimId = $_POST['mukimId'];

        $temperature = $_POST['temperature'];
        $rainfall    = $_POST['rainfall'];
        $humidity    = $_POST['humidity'];

        // ================= INSERT CLIMATE DATA =================
        $stmt = $conn->prepare("
            INSERT INTO climatedata
            (mukimId, Week, Year, Temperature, Rainfall, Humidity)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiiddd",
            $mukimId,
            $week,
            $year,
            $temperature,
            $rainfall,
            $humidity
        );
        $stmt->execute();

        // ================= FETCH PREVIOUS DENGUE CASES FOR LAG =================
        $prev_week = $week - 1;
        if ($prev_week < 1) {
            $prev_week = 1; 
        }

        $stmt_prev = $conn->prepare("SELECT dengueCases FROM denguerecords WHERE mukimId=? AND Week=? AND Year=?");
        $stmt_prev->bind_param("iii", $mukimId, $prev_week, $year);
        $stmt_prev->execute();
        $result_prev = $stmt_prev->get_result();

        if ($row_prev = $result_prev->fetch_assoc()) {
            $previous_cases = (int)$row_prev['dengueCases'];
        } else {
            $previous_cases = 0; 
        }

        // ================= PREPARE FEATURES FOR PREDICTION =================
        $features = [
            'Temperature' => $temperature,
            'Rainfall' => $rainfall,
            'Humidity' => $humidity,
        ];

        // ================= CALL PYTHON PREDICTION SCRIPT =================
        $python = "C:\\Users\\Kirtnaa\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";
        $script = "C:\\xampp\\htdocs\\PSM\\model\\predict_single.py";

        $command = "\"$python\" \"$script\" $mukimId $week $year";
        $python_output = shell_exec($command);

        error_log("Python script output: " . $python_output);
        $predictedCases = (int)trim($python_output);
        echo "Predicted Cases: " . $predictedCases . "<br>";

        // ================= DETERMINE RISK LEVEL =================
        if ($predictedCases < 10) {
            $riskLevel = "LOW";
        } elseif ($predictedCases < 30) {
            $riskLevel = "MEDIUM";
        } else {
            $riskLevel = "HIGH";
        }

        // ================= SAVE PREDICTION =================
        $stmt = $conn->prepare("
            INSERT INTO predictionresults
            (mukimId, Week, Year, predictedCases, riskLevel)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiiis",
            $mukimId,
            $week,
            $year,
            $predictedCases,
            $riskLevel
        );
        $stmt->execute();

        // ================= SEND EMAIL ALERT =================
        if ($riskLevel !== "LOW") {

            $users = $conn->prepare("
                SELECT Email FROM userdetails WHERE mukimId = ?
            ");
            $users->bind_param("i", $mukimId);
            $users->execute();
            $result = $users->get_result();

            while ($user = $result->fetch_assoc()) {
                sendDengueAlert(
                    $user['Email'],
                    "Mukim $mukimId",
                    $predictedCases,
                    $riskLevel
                );
            }
        }

        echo "manual_success";
        exit;
    }
}
?>