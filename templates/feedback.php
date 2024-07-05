<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <a class="logo">CleanImageAI</a>
    <nav>
      <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="denoise.php">Denoise</a></li>
        <li><a href="extract.php">Extract</a></li>
        <li><a href="feedback.php">Feedback</a></li>
      </ul>
    </nav>
    <?php if (isset($_SESSION["user"])): ?>
      <a href="profile.php" class="account"><img src="images/icons8-account-32.png" alt="Account"></a>
    <?php endif; ?>
    <a href="signup.php" class="signup-btn">Sign Up</a>
</header>

    <div class="feedback-container">
        <h1>FEEDBACK</h1>
        <hr style="width:50%;text-align:center;margin: 0 auto 40px;">
        <p class="feed"> Feel free to share you concerns, feedback, or suggestions with us here! </p>


<?php

if(isset($_POST["submit"])) {

    // Feedback form was filled
    $name = $_POST['name'];
    $email = $_POST['mail']; 
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // To check if fields are empty
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {

        // Include the database connection file
        require_once "connection.php";

        $sql = "INSERT INTO feedback (name, mail, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
            mysqli_stmt_execute($stmt);
            echo "Thank You! ";
        } else {
            echo "Error: " . mysqli_error($conn); 
          
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt);

        // Send the feedback via email
        require "send-email.php";

        // Close the database connection
        mysqli_close($conn);
    } else {
        echo "All the fields are required!";
    }
}

?>
        <form method = "POST" action="feedback.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>
