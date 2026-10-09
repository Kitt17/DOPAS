<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendDengueAlert($email, $mukim, $cases, $riskLevel) {
    $mail = new PHPMailer(true);

    try {
        // ================= SMTP CONFIG =================
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dopas.adm1n@gmail.com'; // your email
        $mail->Password = 'vyruekpebsbeooro';         // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourproject@gmail.com', 'DOPAS Alert System');
        $mail->addAddress($email);

        // ================= EMAIL CONTENT =================
        $mail->isHTML(true);
        $mail->Subject = "Dengue Outbreak Alert - $mukim";
        $mail->Body = "
            <h3>Dengue Outbreak Alert</h3>
            <p><b>Mukim:</b> $mukim</p>
            <p><b>Predicted Cases:</b> $cases</p>
            <p><b>Risk Level:</b> <span style='color:red'>$riskLevel</span></p>
            <p>Please take preventive action immediately.
            
            Petaling District Health Office
            Phone: 03-7884 0400 </p>
        ";

        $mail->send();
    } catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
    exit;
    }
}
