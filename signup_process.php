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
    exi
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

// Format phone: strip non-digits, store as +63XXXXXXXXXX
$phone_digits = preg_replace('/\D/', '', $phone);
if (strlen($phone_digits) === 10) {
    // User entered local format e.g. 9171234567
    $phone_formatted = '+63' . $phone_digits;
} elseif (strlen($phone_digits) === 12 && str_starts_with($phone_digits, '63')) {
    // User entered 639171234567
    $phone_formatted = '+' . $phone_digits;
} else {
    $phone_formatted = $phone; // store as-is if unexpected format
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("
        INSERT INTO users (first_name, last_name, phone, email, password)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$fname, $lname, $phone_formatted, $email, $hashed]);

    $new_id = $pdo->lastInsertId();

    // Log the user in immediately after signup
    $_SESSION['user_id']   = $new_id;
    $_SESSION['username']  = $fname;   // first name only for display
    $_SESSION['first_name'] = $fname;
    $_SESSION['email']     = $email;
    $_SESSION['phone']     = $phone_formatted;

} catch (PDOException $e) {
    error_log("Signup insert failed: " . $e->getMessage());
    header("Location: signup.php?error=server_error");
    exit;
}

header("Location: login.php");
exit;
?>