<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['update'])) {
    $currentUser = $_SESSION['user'];
    $newUserName = mysqli_real_escape_string($conn, $_POST['updateUserName'] ?? '');
    $newUserEmail = mysqli_real_escape_string($conn, $_POST['updateUserEmail'] ?? '');
    $newUserPass = mysqli_real_escape_string($conn, $_POST['updateUserPass'] ?? '');
    $newUserFirstName = mysqli_real_escape_string($conn, $_POST['updateUserFirstName'] ?? '');
    $newUserSecName = mysqli_real_escape_string($conn, $_POST['updateUserSecName'] ?? '');
    $newUserTel = mysqli_real_escape_string($conn, $_POST['updateUserTel'] ?? '');
    $newUserCont = mysqli_real_escape_string($conn, $_POST['updateUserCont'] ?? '');
    $newUserCity = mysqli_real_escape_string($conn, $_POST['updateUserCity'] ?? '');
    $newUserPost = mysqli_real_escape_string($conn, $_POST['updateUserPost'] ?? '');

    if (!empty($newUserPass)) {
        $hashedPassword = password_hash($newUserPass, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE user SET 
                        user_name = '$newUserName', 
                        email = '$newUserEmail', 
                        password = '$hashedPassword', 
                        first_name = '$newUserFirstName', 
                        last_name = '$newUserSecName', 
                        phone = '$newUserTel', 
                        country = '$newUserCont', 
                        city = '$newUserCity', 
                        postal = '$newUserPost' 
                      WHERE user_name = '$currentUser'";
    } else {
        $sqlUpdate = "UPDATE user SET 
                        user_name = '$newUserName', 
                        email = '$newUserEmail', 
                        first_name = '$newUserFirstName', 
                        last_name = '$newUserSecName', 
                        phone = '$newUserTel', 
                        country = '$newUserCont', 
                        city = '$newUserCity', 
                        postal = '$newUserPost' 
                      WHERE user_name = '$currentUser'";
    }

    if (mysqli_query($conn, $sqlUpdate)) {
        $_SESSION['status'] = "Profile updated successfully";
        $_SESSION['user'] = $newUserName; // Update session username
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['status'] = "Profile update failed";
    }
}

$currentUser = $_SESSION['user'];
$sql = "SELECT * FROM user WHERE user_name = '$currentUser'";
$gotResults = mysqli_query($conn, $sql);
$userData = mysqli_fetch_assoc($gotResults);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">Account settings</h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">Account</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-subscription">Subscription</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-logout">Logout</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body">
                                <form action="profile.php" method="POST">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="updateUserName" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['user_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="updateUserEmail" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="updateUserPass" class="form-control mb-1">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="updateUserFirstName" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['first_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="updateUserSecName" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['last_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="updateUserTel" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['phone']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="updateUserCont" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['country']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <input type="text" name="updateUserCity" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['city']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="updateUserPost" class="form-control mb-1" value="<?php echo htmlspecialchars($userData['postal']); ?>">
                                    </div>
                                    <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary" name="update" style="background-color: #800080; border-color: #800080;">Save changes</button>&nbsp;
                                    <button type="button" class="btn btn-default">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="account-subscription">
                            <div class="card-body pb-2">
                                <div class="pricing-plans">
                                    <div class="pricing-plan">
                                        <h3>Monthly Plan</h3>
                                        <h4>OMR/month</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        <ul>
                                            <li>Feature 1</li>
                                            <li>Feature 2</li>
                                            <li>Feature 3</li>
                                        </ul>
                                        <button class="subscribe-btn">Subscribe</button>
                                    </div>
                                    <div class="pricing-plan">
                                        <h3>Yearly Plan</h4>
                                        <h4>OMR/year</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        <ul>
                                            <li>Feature 1</li>
                                            <li>Feature 2</li>
                                            <li>Feature 3</li>
                                        </ul>
                                        <button class="subscribe-btn">Subscribe</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-logout">
                            <div class="card-body pb-2">
                                <form action="logout.php" method="POST">
                                    <p>You are attempting to logout of Clean Image AI website. Are you sure?</p>
                                    <button type="submit" class="btn btn-danger">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
