<?php
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "chickchicken";

if (empty($email) || empty($password)) {
        header("Location: login.php"); 
        exit;
}

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    header("Location: login.php"); 
    exit;
}

$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    header("Location: login.php"); 
    exit;
}

$passrow = $result->fetch_assoc();
$hashed_password_db = $passrow['password'];


if ($email == "admin@gmail.com" && $password == "admin") {
    header("Location: admin.html");
    exit;
}


if (password_verify($password, $hashed_password_db)) {
    header("Location: index.html");
    exit;
} else {
    header("Location: login.php");
    exit;
}

$conn->close();
?>
