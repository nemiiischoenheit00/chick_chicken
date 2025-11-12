<?php
$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];

filter_var($email, FILTER_VALIDATE_EMAIL)
    or die("Invalid email format");

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "chickchicken";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    header("Location: signup.php"); 
    exit;
}

$insertQuery = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$hashed_password')";
if ($conn->query($insertQuery) === TRUE) {
    header("Location: login.php");
    exit;
} else {
    header("Location: signup.php"); 
    exit;
}

$conn->close();
?>