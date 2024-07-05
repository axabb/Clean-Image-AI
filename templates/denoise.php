<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Denoise</title>
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

<div class="denoise_sect">
    
    <h1>IMAGE DENOISER</h1>
    <p>Welcome to CleanImageAI's Image Denoising feature! </p>
      <p>With our state-of-the-art noise reduction technology, achieve crystal-clear images even in low-light conditions. 
      CleanImageAI intelligently differentiates between noise and important details to deliver the best results. </p>
    <form action="/denoise_img" method="POST" enctype="multipart/form-data">
        <!-- Input to select file -->
        <input class="choose_file" name="file" type="file" accept="image/JPEG, image/JPG, image/PNG" required>
        <!-- Button to submit form -->
        <button class="image_upload_btn" type="submit">Denoise Image</button>
    </form>
    <p>*JPG, PNG, and JPEG are supported.</p>
    <p id="message"></p>

</div>

</body>
</html>


