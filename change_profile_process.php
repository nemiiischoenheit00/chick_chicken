<?php
session_start();
require_once 'db.php'; // adjust path to your DB connection file

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: change_profile.php');
    exit;
}

$userId       = $_SESSION['user_id'];
$firstName    = trim($_POST['first_name']    ?? '');
$lastName     = trim($_POST['last_name']     ?? '');
$phone        = trim($_POST['phone']         ?? '');
$email        = trim($_POST['email']         ?? '');
$currentPw    = $_POST['current_password']   ?? '';
$newPw        = $_POST['new_password']       ?? '';
$confirmPw    = $_POST['confirm_password']   ?? '';

// ── Validate required profile fields ──
if (!$firstName || !$lastName || !$phone || !$email) {
    header('Location: change_profile.php?error=missing_fields');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: change_profile.php?error=invalid_email');
    exit;
}

// ── Fetch current user from DB ──
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit;
}

// ── Check email uniqueness (allow own email) ──
$stmtEmail = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
$stmtEmail->execute([$email, $userId]);
if ($stmtEmail->fetch()) {
    header('Location: change_profile.php?error=email_taken');
    exit;
}

// ── Handle optional password change ──
$changePassword = ($currentPw !== '' || $newPw !== '' || $confirmPw !== '');

if ($changePassword) {
    if (!password_verify($currentPw, $user['password'])) {
        header('Location: change_profile.php?error=wrong_password');
        exit;
    }
    if ($newPw !== $confirmPw) {
        header('Location: change_profile.php?error=password_match');
        exit;
    }
    if (strlen($newPw) < 8) {
        header('Location: change_profile.php?error=password_short');
        exit;
    }
    $hashedPw = password_hash($newPw, PASSWORD_DEFAULT);
}

// ── Update DB ──
try {
    if ($changePassword) {
        $update = $pdo->prepare('
            UPDATE users
            SET first_name = ?, last_name = ?, phone = ?, email = ?, password = ?
            WHERE id = ?
        ');
        $update->execute([$firstName, $lastName, $phone, $email, $hashedPw, $userId]);
    } else {
        $update = $pdo->prepare('
            UPDATE users
            SET first_name = ?, last_name = ?, phone = ?, email = ?
            WHERE id = ?
        ');
        $update->execute([$firstName, $lastName, $phone, $email, $userId]);
    }
} catch (PDOException $e) {
    header('Location: change_profile.php?error=db_error');
    exit;
}

// ── Update session ──
$_SESSION['first_name'] = $firstName;
$_SESSION['last_name']  = $lastName;
$_SESSION['phone']      = $phone;
$_SESSION['email']      = $email;
$_SESSION['username']   = $firstName . ' ' . $lastName; // keeps nav display name in sync

header('Location: change_profile.php?success=1');
exit;