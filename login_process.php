<?php
session_start();
require 'db.php';

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    header("Location: login.php?error=missing_fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=invalid_email");
    exit;
}

/* ── NORMAL USER ── */
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php?error=invalid_credentials");
    exit;
}

if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=invalid_credentials");
    exit;
}

// Support both "name" (single column) and "fname"/"lname" (split columns)
$_SESSION['username']   = trim($user['first_name'] . ' ' . $user['last_name']);
$_SESSION['first_name'] = $user['first_name'];
$_SESSION['last_name']  = $user['last_name'];
$_SESSION['email']      = $user['email'];
$_SESSION['phone']      = $user['phone'] ?? '';
$_SESSION['user_id']    = $user['id'];
$_SESSION['is_admin'] = false;

header("Location: index.php");
exit;
?>
    