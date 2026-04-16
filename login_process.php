<?php
session_start();

$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "chickchicken";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("DB connection failed");
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: login.php");
    exit;
}

$user = $result->fetch_assoc();

/* ADMIN CHECK FIRST (but still set session) */
if ($email === "admin@gmail.com" && $password === "admin") {
    $_SESSION['username'] = "Admin";
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = 0;

    header("Location: admin.html");
    exit;
}

/* NORMAL USER LOGIN */
if (password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_id'] = $user['id'];

    header("Location: index.php");
    exit;
}

/* WRONG PASSWORD */
header("Location: login.php");
exit;
?>