<?php
// admin_login_process.php

session_start();
require_once 'db.php';

// ── Only accept POST ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_login.php");
    exit;
}

// ── Collect inputs ──
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ── Basic presence check ──
if ($email === '' || $password === '') {
    header("Location: admin_login.php?error=missing_fields");
    exit;
}

// ── Validate email format ──
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: admin_login.php?error=invalid_email");
    exit;
}

try {
    // ── Look up admin by email ──
    $stmt = $pdo->prepare("
        SELECT id, first_name, last_name, email, password 
        FROM admins 
        WHERE email = ? 
        LIMIT 1
    ");
    $stmt->execute([$email]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        // No admin found
        header("Location: admin_login.php?error=invalid_credentials");
        exit;
    }

    // ── Verify password ──
    if (!password_verify($password, $admin['password'])) {
        header("Location: admin_login.php?error=invalid_credentials");
        exit;
    }

    // ── Set session ──
    session_regenerate_id(true); // prevent session fixation

    $_SESSION['admin_id']         = $admin['id'];
    $_SESSION['admin_first_name'] = $admin['first_name'];
    $_SESSION['admin_last_name']  = $admin['last_name'];
    $_SESSION['admin_email']      = $admin['email'];
    $_SESSION['is_admin']         = true;

    // ── Redirect to dashboard ──
    header("Location: admin.php");
    exit;

} catch (PDOException $e) {
    // Optional: log error
    // error_log($e->getMessage());

    header("Location: admin_login.php?error=server_error");
    exit;
}