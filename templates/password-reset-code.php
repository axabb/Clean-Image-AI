<?php
session_start();
$conn = require __DIR__ . "/connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function send_password_reset($get_name, $get_email, $token)
{
    $mail = new PHPMailer(true);
    
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
    $mail->isSMTP();                        
    $mail->SMTPAuth   = true;               

    $mail->Host = "smtp-mail.outlook.com";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Username = "20F20754@mec.edu.om";
    $mail->Password = "******"; #REPLACE EMAIL AND PASSWORD

    $mail->setFrom("20F20754@mec.edu.om", $get_name);
    $mail->addAddress($get_email);

    $mail->isHtml(true);
    $mail->Subject = "Reset Password Notification";

    $email_template = "
        <h2>Hello</h2>
        <h3>You are receiving this email because we received a password reset request from your account.</h3>
        <br/><br/>
        <a href='http://localhost/cleanimageai/templates/password-change.php?token=$token&email=$get_email'>Click Me</a>";

    $mail->Body = $email_template;
    $mail->send();
}

if (isset($_POST['password_reset_link'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        
        $row = mysqli_fetch_array($check_email_run);
        
        // Combine first_name and last_name to form the full name
        $get_name = $row['first_name'] . ' ' . $row['last_name'];
        $get_email = $row['email'];

        $update_token = "UPDATE user SET reset_token_hash = '$token' WHERE email = '$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);

        if ($update_token_run) {
            send_password_reset($get_name, $get_email, $token);
            $_SESSION['status'] = "Email was sent. Kindly check your inbox.";
            header("Location: password-reset.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Something went wrong. #1";
            header("Location: password-reset.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "No Email Found";
        header("Location: password-reset.php");
        exit(0);
    }
}

// password-change code

if (isset($_POST['password_update'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['password_token']);

    if (!empty($token)) {
        if (!empty($new_password) && !empty($confirm_password)) {
            // to check token validity
            $check_token = "SELECT reset_token_hash FROM user WHERE reset_token_hash = '$token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);

            if (mysqli_num_rows($check_token_run) > 0) {
                if ($new_password == $confirm_password) {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    $update_password = "UPDATE user SET password = '$hashed_password' WHERE reset_token_hash = '$token' LIMIT 1"; 
                    $update_password_run = mysqli_query($conn, $update_password);

                    if ($update_password_run) {
                        $new_token = md5(rand())."funda";
                        $update_to_new_token = "UPDATE user SET reset_token_hash = '$new_token' WHERE reset_token_hash = '$token' LIMIT 1"; 
                        $update_to_new_token_run = mysqli_query($conn, $update_to_new_token);
                        
                        $_SESSION['status'] = "New password was successfully updated.";
                        header("Location: login.php");
                        exit(0);
                    } else {
                        $_SESSION['status'] = "Something went wrong.";
                        header("Location: password-change.php?token=$token&email=$email");
                        exit(0);
                    }
                } else {
                    $_SESSION['status'] = "Both passwords must match.";
                    header("Location: password-change.php?token=$token&email=$email");
                    exit(0);
                }
            } else {
                $_SESSION['status'] = "Invalid Token";
                header("Location: password-change.php?token=$token&email=$email");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "All fields are mandatory";
            header("Location: password-change.php?token=$token&email=$email");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "No Token Available";
        header("Location: password-change.php");
        exit(0);
    }
}
?>
