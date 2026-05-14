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

/* ── ADMIN SHORTCUT ── */
if ($email === 'admin@gmail.com' && $password === 'admin') {
    $_SESSION['username']   = 'Admin';
    $_SESSION['first_name'] = 'Admin';
    $_SESSION['email']      = $email;
    $_SESSION['user_id']    = 0;
    $_SESSION['is_admin']   = true;
    header("Location: admin.php");
    exit;
}

/* ── NORMAL USER ── */
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    header("Location: login.php?error=invalid_credentials");
    exit;
}

// Support both old schema (name) and new schema (first_name + last_name)
$firstName   = $user['first_name'] ?? '';
$lastName    = $user['last_name']  ?? '';
$displayName = $firstName
    ? trim("$firstName $lastName")
    : ($user['name'] ?? 'User');

$_SESSION['username']   = $displayName;       // legacy — keeps old pages working
$_SESSION['first_name'] = $firstName ?: $displayName; // nav.php uses this
$_SESSION['email']      = $user['email'];
$_SESSION['user_id']    = $user['id'];
$_SESSION['is_admin']   = false;

header("Location: index.php");
exit;
