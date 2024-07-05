<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

// Email settings
$mail->isSMTP();
$mail->Host = "smtp-mail.outlook.com";
$mail->SMTPAuth = true;
$mail->Username = "20F20754@mec.edu.om";
$mail->Password = "******"; #REPLACE EMAIL AND PASSWORD
$mail->SMTPSecure = "tls";
$mail->Port = 587;

// Email content
$mail->setFrom("20F20754@mec.edu.om", "CleanImageAI");
$mail->addAddress("20F20754@mec.edu.om"); 
$mail->Subject = $_POST['subject'];
$mail->Body = "Name: " . $_POST['name'] . "\n" . 
              "Email: " . $_POST['mail'] . "\n" . 
              "Message: " . $_POST['message'];

try {
    $mail->send();
    echo "Your feedback was successfully sent.";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
