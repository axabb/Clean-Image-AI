<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "website";
$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

return $conn;
?>
