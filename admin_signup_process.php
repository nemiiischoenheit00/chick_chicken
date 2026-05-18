<?php
// admin_signup_process.php

session_start();
require_once 'db.php';

// ── Admin registration key ──
define('ADMIN_REGISTRATION_KEY', 'ChickAdmin@2026');

// ── Only accept POST ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_signup.php");
    exit;
}

// ── Collect & sanitize inputs ──
$first_name = trim($_POST['Fname'] ?? '');
$last_name  = trim($_POST['Lname'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$confirm    = $_POST['confirm_password'] ?? '';
$admin_key  = trim($_POST['admin_key'] ?? '');

// ── Admin key check ──
if ($admin_key !== ADMIN_REGISTRATION_KEY) {
    header("Location: admin_signup.php?error=invalid_key");
    exit;
}

// ── Validate email ──
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_signup.php?error=invalid_email");
    exit;
}

// ── Validate password ──
if (
    strlen($password) < 6 ||
    !preg_match('/[A-Za-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    strpos($password, ' ') !== false
) {
    header("Location: admin_signup.php?error=weak_password");
    exit;
}

// ── Confirm password ──
if ($password !== $confirm) {
    header("Location: admin_signup.php?error=password_mismatch");
    exit;
}

// ── Validate phone (10 digits, no leading zero) ──
if (!preg_match('/^[1-9]\d{9}$/', $phone)) {
    header("Location: admin_signup.php?error=invalid_phone");
    exit;
}

// ── Check if email already exists ──
try {
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        header("Location: admin_signup.php?error=email_exists");
        exit;
    }

    // ── Hash password ──
    $hashed = password_hash($password, PASSWORD_BCRYPT);

    // ── Insert new admin ──
    $stmt = $pdo->prepare("
        INSERT INTO admins (first_name, last_name, phone, email, password)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([$first_name, $last_name, $phone, $email, $hashed]);

    // ── Success ──
    header("Location: admin_login.php?registered=1");
    exit;

} catch (PDOException $e) {
    // Optional: log error instead of showing it
    // error_log($e->getMessage());

    header("Location: admin_signup.php?error=server_error");
    exit;
}