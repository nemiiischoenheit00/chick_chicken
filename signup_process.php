<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signup.php");
    exit;
}

$fname    = trim($_POST['Fname']            ?? '');
$lname    = trim($_POST['Lname']            ?? '');
$phone    = trim($_POST['phone']            ?? '');
$email    = trim($_POST['email']            ?? '');
$password =      $_POST['password']         ?? '';
$confirm  =      $_POST['confirm_password'] ?? '';

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: signup.php?error=invalid_email");
    exit;
}

// Password match
if ($password !== $confirm) {
    header("Location: signup.php?error=password_mismatch");
    exit;
}

// Password strength: min 6 chars, has letter and number, no spaces
if (
    strlen($password) < 6 ||
    !preg_match('/[A-Za-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    preg_match('/\s/', $password)
) {
    header("Location: signup.php?error=weak_password");
    exit;
}

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header("Location: signup.php?error=email_exists");
    exit;
}

$hashed    = password_hash($password, PASSWORD_DEFAULT);
$full_name = trim("$fname $lname");

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$full_name, $email, $hashed]);
} catch (PDOException $e) {
    error_log("Signup insert failed: " . $e->getMessage());
    header("Location: signup.php?error=server_error");
    exit;
}

header("Location: login.php?registered=1");
exit;
?>
