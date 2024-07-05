<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
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

<section class="hero">
    <div class="container1">
      <div class="hero-content">
        <h1>Effortless Image Editing</h1>
        <p>Denoise your images effortlessly with CleanImageAI. 
          Our advanced algorithms remove unwanted noise while preserving crucial details, making your photos look clean and professional.</p>
        <div class="denoise_link">
          <a href="denoise.php"> &#9658;</a>
          <a href="denoise.php">DENOISE NOW</a>
        </div>
      </div>
      <div class="hero-image">
        <img src="images/3704081-removebg-preview.png" alt="Hero Image">
      </div>
    </div>
</section>

  <section class="features">
    <div class="container2">
      <h1>Transform your photos with CleanImageAI. Our cutting-edge technology ensures clear, high-quality photos by removing noise while keeping important details.</h1>
      <div class="feature">
        <div class="icon"><img src="images/icons8-trust-32.png" alt="Feature Icon"></div>
        <h2>Feature 1</h2>
        <p>Enhance image clarity by reducing levels of currupted pixels on the image and improving detail with just one click.</p>
      </div>
      <div class="feature">
        <div class="icon"><img src="images/icons8-trust-32.png" alt="Feature Icon"></div>
        <h2>Feature 2</h2>
        <p>Extract textual and numeric information from paper documents for digitization and archive management.</p>
      </div>
      <div class="feature">
        <div class="icon"><img src="images/icons8-trust-32.png" alt="Feature Icon"></div>
        <h2>Feature 3</h2>
        <p>Enjoy high-quality results every time to streamline your workflow, enhance productivity, and greater efficiency.</p>
      </div>
    </div>
  </section>

  <section class="how-it-works">
    <div class="container3">
      <div class="how-it-works-content">
        <h1>How does it work?</h1>
        <div class="step">
          <div class="number">1</div>
          <div class="step-content">
          <p>Upload your image in PNG, JPG, and JPEG file formats to our platform for seamless processing.</p>
          </div>
        </div>
        <div class="step">
          <div class="number">2</div>
          <div class="step-content">
            <p>Ready to process your photos? Click the Denoise or Extract button to begin your journey to perfect images and text.</p>
          </div>
        </div>
        <div class="step">
          <div class="number">3</div>
          <div class="step-content">
            <p>Download your enhanced, noise-free images and extracted text instantly, benefiting from the immediate availability of high-quality, clear visuals and accurate textual data.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <footer>
      <p>&copy; 2024 CleanImageAI. All rights reserved.</p>
  </footer>

</body>
</html>
