<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error   = '';

// ── SVG HELPERS ─────────────────────────────────────────────
function eyeIcon() {
    return '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
}
function eyeOffIcon() {
    return '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
}

// ── FETCH USER ──────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// ── FETCH LATEST DISCOUNT APPLICATION ──────────────────────
$appStmt = $pdo->prepare("SELECT * FROM discount_applications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$appStmt->execute([$user_id]);
$discountApp = $appStmt->fetch(PDO::FETCH_ASSOC);

$discountStatus = $discountApp ? $discountApp['status'] : 'none';

// ── HANDLE PROFILE UPDATE ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_profile') {
    $first_name   = trim($_POST['first_name']   ?? '');
    $last_name    = trim($_POST['last_name']    ?? '');
    $phone        = trim($_POST['phone']        ?? '');
    $email        = trim($_POST['email']        ?? '');
    $current_pass = $_POST['current_pass']      ?? '';
    $new_pass     = $_POST['new_pass']          ?? '';
    $confirm_pass = $_POST['confirm_pass']      ?? '';

    $changing_password = $current_pass !== '' || $new_pass !== '' || $confirm_pass !== '';

    if (!$first_name || !$last_name || !$phone || !$email) {
        $error = 'First name, last name, phone, and email are all required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($changing_password && !$current_pass) {
        $error = 'Enter your current password to set a new one.';
    } elseif ($changing_password && !password_verify($current_pass, $user['password'])) {
        $error = 'Your current password is incorrect.';
    } elseif ($changing_password && strlen($new_pass) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($changing_password && $new_pass !== $confirm_pass) {
        $error = 'New passwords do not match.';
    } else {
        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $chk->execute([$email, $user_id]);
        if ($chk->fetch()) {
            $error = 'That email is already used by another account.';
        } else {
            if ($changing_password) {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, phone=?, email=?, password=? WHERE id=?");
                $upd->execute([$first_name, $last_name, $phone, $email, $hashed, $user_id]);
            } else {
                $upd = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, phone=?, email=? WHERE id=?");
                $upd->execute([$first_name, $last_name, $phone, $email, $user_id]);
            }
            $_SESSION['username'] = $first_name . ' ' . $last_name;
            $user['first_name'] = $first_name;
            $user['last_name']  = $last_name;
            $user['phone']      = $phone;
            $user['email']      = $email;
            $success = $changing_password
                ? 'Profile and password updated successfully!'
                : 'Profile updated successfully!';
        }
    }
}

// ── HANDLE DISCOUNT APPLICATION ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'apply_discount') {
    $type = trim($_POST['discount_type'] ?? '');

    if (!$type) {
        $error = 'Please select a discount type.';
    } elseif (empty($_FILES['id_image']['name'])) {
        $error = 'Please upload a valid ID image.';
    } else {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime    = mime_content_type($_FILES['id_image']['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $error = 'Only JPG, PNG, WEBP, or GIF images are accepted.';
        } elseif ($_FILES['id_image']['size'] > 5 * 1024 * 1024) {
            $error = 'Image must be under 5MB.';
        } else {
            $uploadDir = 'uploads/discount_ids/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext      = pathinfo($_FILES['id_image']['name'], PATHINFO_EXTENSION);
            $filename = 'discount_' . $user_id . '_' . time() . '.' . $ext;
            $dest     = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['id_image']['tmp_name'], $dest)) {
                $del = $pdo->prepare("DELETE FROM discount_applications WHERE user_id = ? AND status = 'pending'");
                $del->execute([$user_id]);

                $ins = $pdo->prepare("INSERT INTO discount_applications (user_id, type, id_image_path) VALUES (?, ?, ?)");
                $ins->execute([$user_id, $type, $dest]);

                $appStmt->execute([$user_id]);
                $discountApp    = $appStmt->fetch(PDO::FETCH_ASSOC);
                $discountStatus = $discountApp ? $discountApp['status'] : 'none';

                $success = 'Your discount application has been submitted and is under review!';
            } else {
                $error = 'Failed to upload the image. Please try again.';
            }
        }
    }
}

// ── FETCH ALL TRANSACTIONS (for JS pagination) ──────────────
$txStmt = $pdo->prepare("
    SELECT o.id, o.created_at, o.status, o.total, o.payment_method,
           o.discount_type, o.discount_rate, o.original_total, o.discount_amount
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$txStmt->execute([$user_id]);
$transactions = $txStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all order items for each transaction
$allItems = [];
if (!empty($transactions)) {
    $ids = array_column($transactions, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $itemStmt = $pdo->prepare("
        SELECT oi.order_id, oi.quantity, oi.price, oi.option_selected, oi.sauce, p.name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id IN ($placeholders)
    ");
    $itemStmt->execute($ids);
    foreach ($itemStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $allItems[$row['order_id']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="assets/Logo.png" />
  <title>My Profile – Chick Chicken</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Alegreya+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; }

    body {
      margin: 0; padding: 0;
      background: #f7f7f5;
      font-family: 'Alegreya Sans', sans-serif;
      color: #222;
      overflow-x: hidden;
    }

    /* ── PAGE LAYOUT ── */
    .profile-page {
      max-width: 1200px;
      margin: 0 auto;
      padding: 113px 24px 80px;
    }

    .profile-page-title {
      font-family: 'Oswald', sans-serif;
      font-size: 2rem; font-weight: 600;
      color: #1a1a1a; margin: 0 0 4px;
    }
    .profile-page-sub { font-size: 14px; color: #999; margin: 0 0 32px; }

    /* ── TWO COLUMN GRID ── */
    .profile-columns {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      align-items: start;
    }

    .col-left  { display: flex; flex-direction: column; gap: 24px; }
    .col-right { display: flex; flex-direction: column; gap: 24px; }

    /* ── CARDS ── */
    .profile-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.07);
      padding: 32px;
    }

    .card-title {
      font-family: 'Oswald', sans-serif;
      font-size: 1.1rem; font-weight: 600;
      color: #1a1a1a; margin: 0 0 4px;
      display: flex; align-items: center; gap: 10px;
    }
    .card-title svg { color: #D62828; flex-shrink: 0; }
    .card-sub { font-size: 13px; color: #bbb; margin: 0 0 24px; }

    /* ── ALERTS ── */
    .alert {
      border-radius: 10px; padding: 13px 18px;
      font-size: 14px; font-weight: 600;
      margin-bottom: 24px;
      display: flex; align-items: center; gap: 10px;
    }
    .alert-success { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
    .alert-error   { background: #fce4ec; color: #c62828; border: 1.5px solid #ef9a9a; }

    /* ── FORM ── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }

    .form-group label {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.5px; color: #777;
    }
    .form-group input,
    .form-group select {
      border: 1.5px solid #e8e8e8; border-radius: 10px;
      padding: 11px 14px; font-size: 15px;
      font-family: 'Alegreya Sans', sans-serif;
      color: #222; background: #fafafa;
      outline: none; width: 100%;
      transition: border 0.2s, background 0.2s;
    }
    .form-group input:focus,
    .form-group select:focus { border-color: #D62828; background: #fff; }

    .pass-wrap { position: relative; }
    .pass-wrap input { padding-right: 44px; }
    .pass-toggle {
      position: absolute; right: 13px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none; cursor: pointer;
      color: #bbb; padding: 0;
      display: flex; align-items: center;
      transition: color 0.15s;
    }
    .pass-toggle:hover { color: #D62828; }

    .form-section-label {
      grid-column: 1 / -1;
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.7px;
      color: #D62828; padding-top: 10px;
      border-top: 1.5px solid #f0f0f0;
      margin-top: 6px;
      display: flex; align-items: center; gap: 8px;
    }
    .form-section-label span {
      color: #bbb; font-weight: 400;
      text-transform: none; letter-spacing: 0; font-size: 12px;
    }

    /* ── BUTTONS ── */
    .btn-primary {
      background: #D62828; color: #fff; border: none;
      border-radius: 10px; padding: 12px 28px;
      font-family: 'Oswald', sans-serif; font-size: 15px; font-weight: 600;
      cursor: pointer; transition: background 0.2s, transform 0.1s;
      margin-top: 8px; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary:hover { background: #b81f1f; transform: translateY(-1px); }

    .btn-secondary {
      background: #f5f5f5; color: #555; border: none;
      border-radius: 10px; padding: 12px 20px;
      font-family: 'Oswald', sans-serif; font-size: 14px; font-weight: 600;
      cursor: pointer; transition: background 0.2s;
      margin-top: 8px; margin-left: 10px;
    }
    .btn-secondary:hover { background: #ebebeb; }

    /* ── INFO (read-only) ── */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }
    .info-item label {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.5px;
      color: #bbb; display: block; margin-bottom: 3px;
    }
    .info-item span { font-size: 15px; color: #222; font-weight: 500; }

    #editFormWrap { display: none; }

    /* ── DISCOUNT BADGE ── */
    .discount-badge {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 6px 16px; border-radius: 30px;
      font-size: 13px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.4px;
      margin-bottom: 16px;
    }
    .badge-none     { background: #f5f5f5; color: #999; }
    .badge-pending  { background: #fff8e1; color: #e65c00; }
    .badge-approved { background: #e8f5e9; color: #2e7d32; }
    .badge-rejected { background: #fce4ec; color: #c62828; }

    .discount-type-chip {
      display: inline-flex; align-items: center; gap: 6px;
      background: #fff8e1; color: #b45309;
      border: 1.5px solid #f5c800; border-radius: 20px;
      padding: 4px 12px; font-size: 12px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.3px;
      margin-left: 8px; vertical-align: middle;
    }

    .upload-zone {
      border: 2px dashed #e0e0e0; border-radius: 12px;
      padding: 28px 16px; text-align: center;
      cursor: pointer; position: relative; background: #fafafa;
      transition: border-color 0.2s, background 0.2s;
    }
    .upload-zone:hover { border-color: #D62828; background: #fff5f5; }
    .upload-zone.drag-over { border-color: #D62828; background: #fff5f5; box-shadow: 0 0 0 4px rgba(214,40,40,0.08); }
    .upload-zone.drag-reject { border-color: #c62828; background: #fce4ec; box-shadow: 0 0 0 4px rgba(198,40,40,0.10); }
    .upload-zone input[type="file"] { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .upload-zone-icon { font-size: 30px; margin-bottom: 6px; display: block; }
    .upload-zone-label { font-size: 13px; color: #888; display: block; }
    .upload-zone-label strong { color: #D62828; }
    #fileNameDisplay { margin-top: 8px; font-size: 13px; color: #555; font-style: italic; min-height: 16px; }
    #dragRejectMsg { display: none; margin-top: 6px; font-size: 13px; color: #c62828; font-weight: 600; }

    .card-divider { border: none; border-top: 1px solid #f0f0f0; margin: 22px 0; }

    .app-detail-table { width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 16px; }
    .app-detail-table td { padding: 7px 10px; border-bottom: 1px solid #f5f5f5; }
    .app-detail-table td:first-child { font-weight: 700; color: #999; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 120px; }
    .app-detail-table td:last-child { color: #222; }

    .submitted-id-wrap { margin-top: 14px; }
    .submitted-id-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #bbb; display: block; margin-bottom: 8px; }
    .submitted-id-img { max-width: 100%; width: 100%; border-radius: 12px; border: 1.5px solid #e8e8e8; display: block; cursor: zoom-in; transition: box-shadow 0.2s, transform 0.2s; }
    .submitted-id-img:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.13); transform: scale(1.01); }
    .submitted-id-hint { font-size: 12px; color: #bbb; margin: 5px 0 0; font-style: italic; }

    .prev-app { background: #fafafa; border-radius: 12px; padding: 14px 16px; font-size: 14px; color: #555; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 10px; }
    .prev-app strong { color: #222; }

    .field-hint { font-size: 12px; color: #999; margin-top: 2px; }

    .strength-bar-wrap {
      display: flex; gap: 5px; margin-top: 8px;
    }
    .strength-seg {
      height: 4px; flex: 1; border-radius: 4px;
      background: #e0e0e0; transition: background 0.25s;
    }
    .strength-label-row {
      display: flex; justify-content: flex-end;
      margin-top: 4px;
    }
    .strength-label-text {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.4px;
    }
    .pw-requirements {
      list-style: none; margin: 10px 0 0; padding: 0;
      display: none; flex-direction: column; gap: 5px;
    }
    .pw-requirements li {
      font-size: 12px; color: #bbb;
      display: flex; align-items: center; gap: 7px;
      transition: color 0.2s;
    }
    .pw-requirements li.met { color: #2e7d32; }
    .req-circle {
      width: 16px; height: 16px; border-radius: 50%;
      border: 1.5px solid #ddd; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      transition: background 0.2s, border-color 0.2s;
    }
    .pw-requirements li.met .req-circle {
      background: #2e7d32; border-color: #2e7d32; color: #fff;
    }
    .req-circle svg { width: 10px; height: 10px; }

    /* ── TRANSACTION TABLE ── */
    .tx-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .tx-table thead tr { border-bottom: 2px solid #f0f0f0; }
    .tx-table th { text-align: left; padding: 10px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #bbb; font-weight: 700; }
    .tx-table th.amount-col { text-align: right; }
    .tx-table th:last-child { text-align: center; }
    .tx-table tbody tr.tx-row { border-bottom: 1px solid #f5f5f5; transition: background 0.15s; }
    .tx-table tbody tr.tx-row:hover { background: #fafafa; }
    .tx-table td { padding: 13px 12px; vertical-align: middle; }
    .tx-table td.amount-col { text-align: right; font-weight: 700; color: #222; }
    .tx-table td.action-col { text-align: center; }

    .order-num { font-weight: 700; color: #D62828; }
    .tx-date { color: #555; font-size: 13px; }
    .tx-payment { color: #555; }

    .status-pill { border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; display: inline-block; }

    .view-btn { background: none; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-family: 'Oswald', sans-serif; font-weight: 600; color: #555; cursor: pointer; transition: all 0.15s; }
    .view-btn:hover { border-color: #D62828; color: #D62828; }

    .order-detail-row { display: none; }
    .order-detail-row td { padding: 0 12px 14px; background: #fafafa; }

    .order-items-box { border: 1.5px solid #f0f0f0; border-radius: 10px; overflow: hidden; margin-top: 4px; }
    .order-item-line { display: flex; justify-content: space-between; align-items: center; padding: 9px 14px; border-bottom: 1px solid #f5f5f5; background: #fff; }
    .order-item-line:last-child { border-bottom: none; }
    .order-item-name { font-size: 13px; color: #333; }
    .order-item-qty { color: #D62828; font-weight: 700; }
    .order-item-price { font-size: 13px; font-weight: 600; color: #555; }
    .order-total-line { display: flex; justify-content: flex-end; padding: 9px 14px; background: #f9f9f9; border-top: 2px solid #f0f0f0; font-family: 'Oswald', sans-serif; font-size: 14px; font-weight: 700; color: #D62828; }

    /* ── PAGINATION ── */
    .tx-empty { text-align: center; padding: 40px 20px; color: #bbb; }
    .tx-empty .tx-empty-icon { font-size: 48px; margin-bottom: 12px; display: block; }
    .tx-empty p { font-size: 15px; font-weight: 600; color: #ccc; margin: 0 0 4px; }
    .tx-empty span { font-size: 13px; }

    .tx-pagination {
      display: flex; align-items: center; justify-content: center;
      gap: 12px; margin-top: 20px; padding-top: 18px;
      border-top: 1px solid #f0f0f0;
    }
    .page-label {
      font-family: 'Oswald', sans-serif;
      font-size: 14px; font-weight: 600; color: #555;
      min-width: 90px; text-align: center;
    }
    .page-arrow {
      width: 36px; height: 36px;
      background: #f5f5f5; border: 1.5px solid #e8e8e8;
      border-radius: 9px; cursor: pointer; color: #555;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; transition: all 0.15s;
    }
    .page-arrow:hover:not(:disabled) { background: #D62828; border-color: #D62828; color: #fff; }
    .page-arrow:disabled { opacity: 0.35; cursor: default; }

    /* ── IMAGE ZOOM ── */
    #imgZoomOverlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center; cursor: zoom-out; }
    #imgZoomOverlay.active { display: flex; }
    #imgZoomTarget { max-width: 92vw; max-height: 88vh; border-radius: 14px; object-fit: contain; box-shadow: 0 12px 60px rgba(0,0,0,0.5); }
    .zoom-close { position: absolute; top: 20px; right: 26px; color: #fff; font-size: 32px; font-weight: 300; cursor: pointer; line-height: 1; opacity: 0.8; transition: opacity 0.15s; }
    .zoom-close:hover { opacity: 1; }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
      .profile-columns { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
      .form-grid { grid-template-columns: 1fr; }
      .info-grid { grid-template-columns: 1fr; }
      .form-section-label { grid-column: 1; }
      .profile-card { padding: 22px 16px; }
      .tx-table th.hide-mobile, .tx-table td.hide-mobile { display: none; }
    }
    body .logo img {
      position: relative;
      top: -10px !important;
    }
    .ordernow_button {
      padding: 5px 28px !important
    }
header {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  width: 100% !important;
  z-index: 999 !important;
}
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="profile-page">

  <h1 class="profile-page-title">My Profile</h1>
  <p class="profile-page-sub">Manage your personal information and discount applications.</p>

  <?php if ($success): ?>
    <div class="alert alert-success">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="alert alert-error">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <div class="profile-columns">

    <!-- ══════════ LEFT COLUMN ══════════ -->
    <div class="col-left">

      <!-- ── PERSONAL INFO CARD ── -->
      <div class="profile-card">
        <div class="card-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Personal Information
        </div>
        <p class="card-sub">Your account details on file.</p>

        <!-- READ-ONLY -->
        <div id="infoView">
          <div class="info-grid">
            <div class="info-item"><label>First Name</label><span><?= htmlspecialchars($user['first_name']) ?></span></div>
            <div class="info-item"><label>Last Name</label><span><?= htmlspecialchars($user['last_name']) ?></span></div>
            <div class="info-item"><label>Phone</label><span><?= htmlspecialchars($user['phone']) ?></span></div>
            <div class="info-item"><label>Email</label><span><?= htmlspecialchars($user['email']) ?></span></div>
            <div class="info-item"><label>Password</label><span>••••••••</span></div>
          </div>
          <button class="btn-primary" onclick="toggleEdit()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Profile
          </button>
        </div>

        <!-- EDIT FORM -->
        <div id="editFormWrap">
          <form method="POST" action="profile.php">
            <input type="hidden" name="action" value="update_profile">
            <div class="form-grid">
              <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
              </div>

              <div class="form-section-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Change Password
                <span>— leave blank to keep current</span>
              </div>

              <div class="form-group full">
                <label>Current Password</label>
                <div class="pass-wrap">
                  <input type="password" name="current_pass" id="currentPass" placeholder="Enter your current password" autocomplete="current-password">
                  <button type="button" class="pass-toggle" onclick="togglePass('currentPass', this)" tabindex="-1"><?= eyeIcon() ?></button>
                </div>
              </div>
              <div class="form-group">
                <label>New Password</label>
                <div class="pass-wrap">
                  <input type="password" name="new_pass" id="newPass" placeholder="Min. 8 characters" autocomplete="new-password">
                  <button type="button" class="pass-toggle" onclick="togglePass('newPass', this)" tabindex="-1"><?= eyeIcon() ?></button>
                </div>
                <div class="strength-bar-wrap">
                  <div class="strength-seg" id="pSeg0"></div>
                  <div class="strength-seg" id="pSeg1"></div>
                  <div class="strength-seg" id="pSeg2"></div>
                  <div class="strength-seg" id="pSeg3"></div>
                </div>
                <div class="strength-label-row">
                  <span class="strength-label-text" id="pStrengthLabel" style="color:#999;"></span>
                </div>
                <ul class="pw-requirements" id="pPwRequirements">
                  <li id="p-r-len">
                    <span class="req-circle"><svg viewBox="0 0 16 16" fill="none"><path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    At least 8 characters
                  </li>
                  <li id="p-r-letter">
                    <span class="req-circle"><svg viewBox="0 0 16 16" fill="none"><path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    Contains a letter
                  </li>
                  <li id="p-r-number">
                    <span class="req-circle"><svg viewBox="0 0 16 16" fill="none"><path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    Contains a number
                  </li>
                  <li id="p-r-nospace">
                    <span class="req-circle"><svg viewBox="0 0 16 16" fill="none"><path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    No spaces
                  </li>
                </ul>
              </div>
              <div class="form-group">
                <label>Confirm New Password</label>
                <div class="pass-wrap">
                  <input type="password" name="confirm_pass" id="confirmPass" placeholder="Repeat new password" autocomplete="new-password">
                  <button type="button" class="pass-toggle" onclick="togglePass('confirmPass', this)" tabindex="-1"><?= eyeIcon() ?></button>
                </div>
              </div>
            </div>
            <div style="margin-top:20px;">
              <button type="submit" class="btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Save Changes
              </button>
              <button type="button" class="btn-secondary" onclick="toggleEdit()">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- ── DISCOUNT CARD ── -->
      <div class="profile-card">
        <div class="card-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          Discount Application
        </div>
        <p class="card-sub">Apply for a senior, PWD, or student discount.</p>

        <?php
          $badgeClass = [
            'none'     => 'badge-none',
            'pending'  => 'badge-pending',
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
          ][$discountStatus] ?? 'badge-none';

          $badgeLabel = [
            'none'     => '● No Application',
            'pending'  => '⏳ Pending Review',
            'approved' => '✔ Approved',
            'rejected' => '✖ Rejected',
          ][$discountStatus] ?? '● No Application';
        ?>
        <div class="discount-badge <?= $badgeClass ?>">
          <?= $badgeLabel ?>
          <?php if ($discountApp && $discountStatus !== 'none'): ?>
            <span class="discount-type-chip"><?= htmlspecialchars($discountApp['type']) ?></span>
          <?php endif; ?>
        </div>

        <?php if ($discountStatus === 'approved'): ?>
          <div class="alert alert-success" style="margin-bottom:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            Your <strong><?= htmlspecialchars($discountApp['type']) ?></strong> discount is active. Enjoy your discounted orders!
          </div>
          <table class="app-detail-table">
            <tr><td>Type</td><td><?= htmlspecialchars($discountApp['type']) ?></td></tr>
            <tr><td>Submitted</td><td><?= date('M j, Y', strtotime($discountApp['created_at'])) ?></td></tr>
            <tr><td>Approved</td><td><?= date('M j, Y', strtotime($discountApp['updated_at'])) ?></td></tr>
            <?php if ($discountApp['notes']): ?>
              <tr><td>Notes</td><td><?= htmlspecialchars($discountApp['notes']) ?></td></tr>
            <?php endif; ?>
          </table>
          <?php if (!empty($discountApp['id_image_path'])): ?>
            <div class="submitted-id-wrap">
              <span class="submitted-id-label">Submitted ID / Proof</span>
              <img src="<?= htmlspecialchars($discountApp['id_image_path']) ?>" alt="Submitted ID" class="submitted-id-img" onclick="openImgZoom(this.src)">
              <p class="submitted-id-hint">Click image to enlarge</p>
            </div>
          <?php endif; ?>

        <?php elseif ($discountStatus === 'pending'): ?>
          <div class="prev-app">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e65c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div>
              Your <strong><?= htmlspecialchars($discountApp['type']) ?></strong> application is under review.
              Submitted on <strong><?= date('M j, Y', strtotime($discountApp['created_at'])) ?></strong>.
              <?php if ($discountApp['notes']): ?>
                <br><span style="color:#999; font-size:13px;">Note: <?= htmlspecialchars($discountApp['notes']) ?></span>
              <?php endif; ?>
            </div>
          </div>
          <?php if (!empty($discountApp['id_image_path'])): ?>
            <div class="submitted-id-wrap">
              <span class="submitted-id-label">Submitted ID / Proof</span>
              <img src="<?= htmlspecialchars($discountApp['id_image_path']) ?>" alt="Submitted ID" class="submitted-id-img" onclick="openImgZoom(this.src)">
              <p class="submitted-id-hint">Click image to enlarge</p>
            </div>
          <?php endif; ?>

        <?php elseif ($discountStatus === 'rejected'): ?>
          <div class="alert alert-error" style="margin-bottom:18px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Your previous <strong><?= htmlspecialchars($discountApp['type']) ?></strong> application was not approved.
            <?php if (!empty($discountApp['notes'])): ?>Reason: <?= htmlspecialchars($discountApp['notes']) ?><?php endif; ?>
            You may re-apply below.
          </div>
        <?php endif; ?>

        <?php if (in_array($discountStatus, ['none', 'rejected'])): ?>
          <hr class="card-divider">
          <form method="POST" action="profile.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="apply_discount">
            <div class="form-grid">
              <div class="form-group full">
                <label>Discount Type</label>
                <select name="discount_type" id="discountTypeSelect" required onchange="toggleSeniorPhone(this.value)">
                  <option value="" disabled selected>— Select a type —</option>
                  <option value="Senior Citizen">Senior Citizen (60+)</option>
                  <option value="PWD">Person with Disability (PWD)</option>
                  <option value="Student">Student</option>
                </select>
              </div>
              <div class="form-group full" id="seniorPhoneGroup" style="display:none;">
                <label>Senior Citizen Phone Number</label>
                <input type="text" name="senior_phone" id="seniorPhone" placeholder="e.g. 09xx-xxx-xxxx" value="<?= htmlspecialchars($user['phone']) ?>">
                <span class="field-hint">Used to verify your senior citizen identity.</span>
              </div>
              <div class="form-group full">
                <label>Upload Valid ID or Proof</label>
                <div class="upload-zone" id="uploadZone">
                  <input type="file" name="id_image" id="idImageInput" accept="image/jpeg,image/png,image/webp,image/gif" required>
                  <span class="upload-zone-icon" id="uploadIcon">🪪</span>
                  <span class="upload-zone-label"><strong>Click to upload</strong> or drag &amp; drop<br>JPG, PNG, WEBP, GIF · Max 5MB</span>
                  <div id="fileNameDisplay"></div>
                  <div id="dragRejectMsg">⚠ Only image files are accepted</div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn-primary" style="margin-top:18px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              Submit Application
            </button>
          </form>
        <?php endif; ?>
      </div>

    </div><!-- /col-left -->

    <!-- ══════════ RIGHT COLUMN ══════════ -->
    <div class="col-right">
      <div class="profile-card">
        <div class="card-title">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Transaction History
        </div>
        <p class="card-sub">Your recent orders and their statuses.</p>

        <?php if (empty($transactions)): ?>
          <div class="tx-empty">
            <span class="tx-empty-icon">🛒</span>
            <p>No orders yet</p>
            <span>Your completed orders will appear here.</span>
          </div>
        <?php else: ?>

          <!-- Pass all transaction data to JS -->
          <script>
            var TX_DATA = <?= json_encode(array_values($transactions)) ?>;
            var TX_ITEMS = <?= json_encode($allItems) ?>;
          </script>

          <div style="overflow-x:auto;">
            <table class="tx-table">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date</th>
                  <th class="hide-mobile">Payment</th>
                  <th>Status</th>
                  <th class="amount-col">Total</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody id="txTableBody">
                <!-- filled by JS -->
              </tbody>
            </table>
          </div>

          <div class="tx-pagination" id="txPagination" style="display:none;">
            <button class="page-arrow" id="pagePrev" onclick="changePage(-1)" disabled>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <span class="page-label" id="pageLabel">Page 1 of 1</span>
            <button class="page-arrow" id="pageNext" onclick="changePage(1)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>

        <?php endif; ?>
      </div>
    </div><!-- /col-right -->

  </div><!-- /profile-columns -->
</div><!-- /profile-page -->

<!-- ── IMAGE ZOOM LIGHTBOX ── -->
<div id="imgZoomOverlay" onclick="closeImgZoom()">
  <span class="zoom-close" onclick="closeImgZoom()">✕</span>
  <img id="imgZoomTarget" src="" alt="ID enlarged">
</div>

<script>
  // ── PROFILE EDIT TOGGLE ──
  function toggleEdit() {
    var view = document.getElementById('infoView');
    var form = document.getElementById('editFormWrap');
    var hidden = form.style.display === 'none' || form.style.display === '';
    form.style.display = hidden ? 'block' : 'none';
    view.style.display = hidden ? 'none'  : 'block';
  }

  // ── PASSWORD TOGGLE ──
  var eyeSVG    = <?= json_encode(eyeIcon()) ?>;
  var eyeOffSVG = <?= json_encode(eyeOffIcon()) ?>;
  function togglePass(fieldId, btn) {
    var input   = document.getElementById(fieldId);
    var showing = input.type === 'text';
    input.type    = showing ? 'password' : 'text';
    btn.innerHTML = showing ? eyeSVG : eyeOffSVG;
  }

  // ── SENIOR PHONE TOGGLE ──
  function toggleSeniorPhone(val) {
    var grp = document.getElementById('seniorPhoneGroup');
    if (grp) grp.style.display = val === 'Senior Citizen' ? 'flex' : 'none';
  }

  // ── IMAGE ZOOM ──
  function openImgZoom(src) {
    document.getElementById('imgZoomTarget').src = src;
    document.getElementById('imgZoomOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeImgZoom() {
    document.getElementById('imgZoomOverlay').classList.remove('active');
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeImgZoom(); });

  // ── UPLOAD ZONE ──
  (function () {
    var zone      = document.getElementById('uploadZone');
    var fileInput = document.getElementById('idImageInput');
    var display   = document.getElementById('fileNameDisplay');
    var rejectMsg = document.getElementById('dragRejectMsg');
    var uploadIcon= document.getElementById('uploadIcon');
    if (!zone) return;
    var ALLOWED = ['image/jpeg','image/png','image/webp','image/gif'];
    function isImg(t) { return ALLOWED.includes(t); }
    function setFile(file) {
      if (!isImg(file.type)) { showReject(); return; }
      var dt = new DataTransfer(); dt.items.add(file); fileInput.files = dt.files;
      display.textContent = '📎 ' + file.name;
      uploadIcon.textContent = '✅';
      rejectMsg.style.display = 'none';
      zone.classList.remove('drag-over','drag-reject');
    }
    function showReject() {
      zone.classList.remove('drag-over'); zone.classList.add('drag-reject');
      rejectMsg.style.display = 'block'; uploadIcon.textContent = '🚫';
      setTimeout(function(){ zone.classList.remove('drag-reject'); rejectMsg.style.display='none'; uploadIcon.textContent='🪪'; }, 2500);
    }
    zone.addEventListener('dragenter', function(e){ e.preventDefault(); var i=e.dataTransfer.items; var v=i.length>0&&i[0].kind==='file'&&isImg(i[0].type); zone.classList.toggle('drag-over',v); zone.classList.toggle('drag-reject',!v); rejectMsg.style.display=v?'none':'block'; });
    zone.addEventListener('dragover',  function(e){ e.preventDefault(); });
    zone.addEventListener('dragleave', function(e){ if(!zone.contains(e.relatedTarget)){ zone.classList.remove('drag-over','drag-reject'); rejectMsg.style.display='none'; } });
    zone.addEventListener('drop', function(e){ e.preventDefault(); zone.classList.remove('drag-over','drag-reject'); rejectMsg.style.display='none'; var f=e.dataTransfer.files; if(!f.length) return; setFile(f[0]); });
    fileInput.addEventListener('change', function(){ if(fileInput.files.length){ var f=fileInput.files[0]; if(!isImg(f.type)){ showReject(); fileInput.value=''; return; } display.textContent='📎 '+f.name; uploadIcon.textContent='✅'; } });
  })();

  // ── TRANSACTION PAGINATION ──
  (function () {
    if (typeof TX_DATA === 'undefined') return;

    var PER_PAGE   = 10;
    var currentPage = 1;
    var openDetailId = null;

    var statusStyles = {
      'pending':   { bg: '#fff8e1', color: '#e65c00' },
      'confirmed': { bg: '#e3f2fd', color: '#1565c0' },
      'preparing': { bg: '#f3e5f5', color: '#6a1b9a' },
      'ready':     { bg: '#e8f5e9', color: '#2e7d32' },
      'completed': { bg: '#e8f5e9', color: '#2e7d32' },
      'cancelled': { bg: '#fce4ec', color: '#c62828' },
    };

    function totalPages() {
      return Math.max(1, Math.ceil(TX_DATA.length / PER_PAGE));
    }

    function pad(n) { return String(n).padStart(4, '0'); }

    function formatDate(d) {
      var dt = new Date(d);
      return dt.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' })
           + ' · ' + dt.toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' });
    }

    function renderItems(orderId) {
      var items = TX_ITEMS[orderId] || [];
      if (!items.length) return '<div style="padding:12px 16px;color:#bbb;font-size:13px;">No items found.</div>';
      var html = '';
      var total = 0;
      items.forEach(function(it) {
        var line = it.price * it.quantity;
        total += line;
        html += '<div class="order-item-line">'
              + '<span class="order-item-name"><span class="order-item-qty">×' + it.quantity + '</span>&nbsp;' + escHtml(it.name)
              + (it.option_selected ? '<span style="color:#999;font-size:12px;"> · ' + escHtml(it.option_selected) + '</span>' : '')
              + (it.sauce ? '<span style="color:#999;font-size:12px;"> · ' + escHtml(it.sauce) + '</span>' : '')
              + '</span>'
              + '<span class="order-item-price">₱' + line.toFixed(2) + '</span>'
              + '</div>';
      });
      return html;
    }

    function escHtml(s) {
      return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function renderTable() {
      var body  = document.getElementById('txTableBody');
      var start = (currentPage - 1) * PER_PAGE;
      var slice = TX_DATA.slice(start, start + PER_PAGE);

      var html = '';
      slice.forEach(function(tx) {
        var s   = statusStyles[tx.status.toLowerCase()] || { bg: '#f5f5f5', color: '#999' };
        var oid = tx.id;
        html += '<tr class="tx-row">'
              + '<td><span class="order-num">#' + pad(oid) + '</span></td>'
              + '<td class="tx-date">' + formatDate(tx.created_at) + '</td>'
              + '<td class="tx-payment hide-mobile">' + escHtml(tx.payment_method || '—') + '</td>'
              + '<td><span class="status-pill" style="background:' + s.bg + ';color:' + s.color + ';">' + escHtml(tx.status.charAt(0).toUpperCase() + tx.status.slice(1)) + '</span></td>'
              + '<td class="amount-col">₱' + parseFloat(tx.total).toFixed(2) + '</td>'
              + '<td class="action-col"><button class="view-btn" onclick="toggleDetail(' + oid + ', this)">View</button></td>'
              + '</tr>'
              + '<tr class="order-detail-row" id="order-detail-' + oid + '">'
              + '<td colspan="6">'
              + '<div class="order-items-box">'
              + renderItems(oid)
              + '<div class="order-total-line">Total: ₱' + parseFloat(tx.total).toFixed(2)
              + (tx.discount_type && tx.discount_rate > 0
                  ? '<span style="margin-left:10px;font-size:12px;font-weight:500;color:#2e7d32;">(' + escHtml(tx.discount_type) + ' ' + tx.discount_rate + '% off — saved ₱' + parseFloat(tx.discount_amount).toFixed(2) + ')</span>'
                  : '')
              + '</div></div></td></tr>';
      });

      body.innerHTML = html;
      openDetailId = null;

      // Update pagination controls
      var tp = totalPages();
      var pagination = document.getElementById('txPagination');
      var label      = document.getElementById('pageLabel');
      var prev       = document.getElementById('pagePrev');
      var next       = document.getElementById('pageNext');

      pagination.style.display = TX_DATA.length > PER_PAGE ? 'flex' : 'none';
      label.textContent  = 'Page ' + currentPage + ' of ' + tp;
      prev.disabled      = currentPage <= 1;
      next.disabled      = currentPage >= tp;
    }

    window.changePage = function(dir) {
      var tp = totalPages();
      currentPage = Math.max(1, Math.min(tp, currentPage + dir));
      renderTable();
      // Scroll to top of card
      var card = document.getElementById('txTableBody');
      if (card) card.closest('.profile-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    window.toggleDetail = function(id, btn) {
      var row  = document.getElementById('order-detail-' + id);
      var open = row.style.display === 'table-row';

      // Close previously open row
      if (openDetailId && openDetailId !== id) {
        var prev = document.getElementById('order-detail-' + openDetailId);
        if (prev) prev.style.display = 'none';
        var prevBtn = document.querySelector('[onclick="toggleDetail(' + openDetailId + ', this)"]');
        if (prevBtn) prevBtn.textContent = 'View';
      }

      row.style.display  = open ? 'none' : 'table-row';
      btn.textContent    = open ? 'View' : 'Hide';
      openDetailId       = open ? null  : id;
    };

    // Initial render
    renderTable();
  })();
  
(function () {
  var input  = document.getElementById('newPass');
  var segs   = [0,1,2,3].map(function(i){ return document.getElementById('pSeg' + i); });
  var label  = document.getElementById('pStrengthLabel');
  var list   = document.getElementById('pPwRequirements');
  if (!input) return;

  var SEG_COLORS = ['#e53935','#fb8c00','#fdd835','#43a047'];
  var SEG_LABELS = ['Weak','Fair','Good','Strong'];

  var rules = [
    { id: 'p-r-len',     test: function(v){ return v.length >= 8; } },
    { id: 'p-r-letter',  test: function(v){ return /[a-zA-Z]/.test(v); } },
    { id: 'p-r-number',  test: function(v){ return /[0-9]/.test(v); } },
    { id: 'p-r-nospace', test: function(v){ return v.length > 0 && !/\s/.test(v); } },
  ];

  function getStrength(pw) {
    if (!pw) return 0;
    var score = 0;
    if (pw.length >= 8)                        score++;
    if (pw.length >= 12)                       score++;
    if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
    if (/[0-9]/.test(pw))                      score++;
    if (/[^A-Za-z0-9]/.test(pw))              score++;
    return Math.max(1, Math.min(Math.round(score * 4 / 5), 4));
  }

  input.addEventListener('focus', function(){ if (input.value) list.style.display = 'flex'; });
  input.addEventListener('blur',  function(){ if (!input.value) list.style.display = 'none'; });

  input.addEventListener('input', function(){
    var val = this.value;
    list.style.display = val.length > 0 ? 'flex' : 'none';

    rules.forEach(function(r){
      document.getElementById(r.id).classList.toggle('met', r.test(val));
    });

    var level = val.length === 0 ? 0 : getStrength(val);
    segs.forEach(function(seg, i){
      seg.style.backgroundColor = i < level ? SEG_COLORS[level - 1] : '#e0e0e0';
    });
    label.textContent = level > 0 ? SEG_LABELS[level - 1] : '';
    label.style.color = level > 0 ? SEG_COLORS[level - 1] : '#999';
  });
})();
</script>

</body>
</html>