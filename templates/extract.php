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
    
    <h1>TEXT EXTRACTION</h1>
    <p>Welcome to CleanImageAI's Text Extraction feature! </p>
      <p>Our advanced technology enables you to effortlessly extract text from images with precision and accuracy. Whether you need to digitize printed documents, convert handwritten notes into editable text, or retrieve text from complex images, our tool simplifies the process. </p>
         <form action="/text_extract" method="POST" enctype="multipart/form-data" id="uploadForm">
          <!-- to open file picker dialog -->
          <input class="choose_file" name="file" type="file" accept="image/JPEG, image/JPG, image/PNG" required>
          <button class = "image_upload_btn" type="submit" >Extract Text</button>
        </form>
    <p>*JPG, PNG, and JPEG are supported.</p>
    <p id="message"></p>

</div>

    
</body>
</html>




