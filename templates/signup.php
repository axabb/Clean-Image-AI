<?php
    session_start();
    
?>
<?php
    include("connection.php");
    if(isset($_POST['register'])){
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $postal = mysqli_real_escape_string($conn, $_POST['postal']);
    
        $sql="SELECT * FROM user WHERE user_name='$user_name'";
        $result = mysqli_query($conn, $sql);
        $count_user = mysqli_num_rows($result);

        $sql="SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $count_email = mysqli_num_rows($result);

        if($count_user == 0 & $count_email==0){
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (first_name, last_name, user_name, email, password , phone, country, city, postal) VALUES ('$first_name', '$last_name', '$user_name', '$email', '$hash', '$phone', '$country', '$city', '$postal')";
            $result = mysqli_query($conn, $sql);
                if($result){
                    header("Location: login.php");
                }
        }
        else{
            if($count_user>0){
                echo '<script>
                    window.location.href="signup.php";
                    alert("Username already exists!!");
                </script>';
            }
            if($count_email>0){
                echo '<script>
                    window.location.href="signup.php";
                    alert("Email already exists!!");
                </script>';
            }
        }
        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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

    <div class="signup-container">
        <h1>SIGNUP</h1>
        <hr style="width:50%;text-align:center;margin: 0 auto 40px;">

        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="user_name">Username</label>
                <input type="text" id="user_name" name="user_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="postal">Postal Code</label>
                <input type="text" id="postal" name="postal" required>
            </div>
            <button type="submit" name="register">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
