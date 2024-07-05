<?php
session_start();

?>
<?php
$login = false;
include('connection.php');

if (isset($_POST['login'])) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM user WHERE user_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if (password_verify($password, $row["password"])) {
            $_SESSION['user'] = $user_name;
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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
    <h1>LOGIN</h1>
    <hr style="width:50%;text-align:center;margin: 0 auto 40px;">
    <?php
    if (isset($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }
    ?>
    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="user_name">Username</label>
        <input type="text" id="user_name" name="user_name" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <div class="form-btn">
        <button type="submit" name="login" class="btn btn-primary">Login</button>
      </div>
    </form>
    <p><a href="password-reset.php">Forgot Password?</a></p>
    <p>Don't have an account? <a href="signup.php">Signup</a></p>
  </div>
</body>
</html>
