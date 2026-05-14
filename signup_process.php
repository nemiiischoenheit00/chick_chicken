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

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: signup.php?error=invalid_email"); exit;
}
if ($password !== $confirm) {
    header("Location: signup.php?error=password_mismatch"); exit;
}
if (strlen($password) < 6 || !preg_match('/[A-Za-z]/', $password) ||
    !preg_match('/[0-9]/', $password) || preg_match('/\s/', $password)) {
    header("Location: signup.php?error=weak_password"); exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header("Location: signup.php?error=email_exists"); exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

try {
    // New schema: first_name, last_name, phone
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, phone, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$fname, $lname, $phone, $email, $hashed]);
} catch (PDOException $e) {
    // Fallback: old schema with single name column
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([trim("$fname $lname"), $email, $hashed]);
    } catch (PDOException $e2) {
        error_log("Signup failed: " . $e2->getMessage());
        header("Location: signup.php?error=server_error"); exit;
    }
}

header("Location: login.php?registered=1");
exit;
