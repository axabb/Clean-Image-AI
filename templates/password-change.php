<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
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

<div class="login-container">
  <h1>Reset Password</h1>
  <hr style="width:50%;text-align:center;margin: 0 auto 20px;">

  <?php
    if (isset($_SESSION['status'])) {
      echo "<h5>{$_SESSION['status']}</h5>";
      unset($_SESSION['status']);
    }
  ?>

  <form method="POST" action="password-reset-code.php">
    <input type="hidden" name="password_token" value="<?php if (isset($_GET['token'])) { echo $_GET['token']; } ?>">

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="text" id="email" name="email" value="<?php if (isset($_GET['email'])) { echo $_GET['email']; } ?>" required>
    </div>

    <div class="form-group">
      <label for="password">New Password</label>
      <input type="password" id="password" name="new_password" required>
    </div>

    <div class="form-group">
      <label for="password_confirmation">Confirm Password</label>
      <input type="password" id="password_confirmation" name="confirm_password" required>
    </div>

    <button type="submit" name="password_update">Send</button>
  </form>
</div>

</body>
</html>
