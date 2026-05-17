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
    }

    .profile-page {
      max-width: 860px;
      margin: 0 auto;
      padding: 48px 24px 80px;
    }

    .profile-page-title {
      font-family: 'Oswald', sans-serif;
      font-size: 2rem; font-weight: 600;
      color: #1a1a1a; margin: 0 0 4px;
    }

    .profile-page-sub { font-size: 14px; color: #999; margin: 0 0 36px; }

    /* ── CARDS ── */
    .profile-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.07);
      padding: 36px;
      margin-bottom: 24px;
    }

    .card-title {
      font-family: 'Oswald', sans-serif;
      font-size: 1.15rem; font-weight: 600;
      color: #1a1a1a; margin: 0 0 6px;
      display: flex; align-items: center; gap: 10px;
    }
    .card-title svg { color: #D62828; flex-shrink: 0; }
    .card-sub { font-size: 13px; color: #bbb; margin: 0 0 28px; }

    /* ── FORM ── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
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

    /* password show/hide */
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

    /* password section divider */
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
      border-radius: 10px; padding: 12px 30px;
      font-family: 'Oswald', sans-serif; font-size: 16px; font-weight: 600;
      cursor: pointer; transition: background 0.2s, transform 0.1s;
      margin-top: 8px; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary:hover { background: #b81f1f; transform: translateY(-1px); }

    .btn-secondary {
      background: #f5f5f5; color: #555; border: none;
      border-radius: 10px; padding: 12px 24px;
      font-family: 'Oswald', sans-serif; font-size: 15px; font-weight: 600;
      cursor: pointer; transition: background 0.2s;
      margin-top: 8px; margin-left: 10px;
    }
    .btn-secondary:hover { background: #ebebeb; }

    /* ── ALERTS ── */
    .alert {
      border-radius: 10px; padding: 13px 18px;
      font-size: 14px; font-weight: 600;
      margin-bottom: 24px;
      display: flex; align-items: center; gap: 10px;
    }
    .alert-success { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
    .alert-error   { background: #fce4ec; color: #c62828; border: 1.5px solid #ef9a9a; }

    /* ── DISCOUNT BADGE ── */
    .discount-badge {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 6px 16px; border-radius: 30px;
      font-size: 13px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.4px;
      margin-bottom: 20px;
    }
    .badge-none     { background: #f5f5f5; color: #999; }
    .badge-pending  { background: #fff8e1; color: #e65c00; }
    .badge-approved { background: #e8f5e9; color: #2e7d32; }
    .badge-rejected { background: #fce4ec; color: #c62828; }

    /* ── DISCOUNT TYPE CHIP ── */
    .discount-type-chip {
      display: inline-flex; align-items: center; gap: 6px;
      background: #fff8e1; color: #b45309;
      border: 1.5px solid #f5c800; border-radius: 20px;
      padding: 4px 12px; font-size: 12px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.3px;
      margin-left: 8px; vertical-align: middle;
    }

    /* ── FILE UPLOAD ── */
    .upload-zone {
      border: 2px dashed #e0e0e0; border-radius: 12px;
      padding: 32px 20px; text-align: center;
      cursor: pointer; position: relative; background: #fafafa;
      transition: border-color 0.2s, background 0.2s;
    }
    .upload-zone:hover { border-color: #D62828; background: #fff5f5; }
    .upload-zone input[type="file"] {
      position: absolute; inset: 0;
      width: 100%; height: 100%; opacity: 0; cursor: pointer;
    }
    .upload-zone-icon { font-size: 36px; margin-bottom: 8px; display: block; }
    .upload-zone-label { font-size: 14px; color: #888; display: block; }
    .upload-zone-label strong { color: #D62828; }
    #fileNameDisplay { margin-top: 10px; font-size: 13px; color: #555; font-style: italic; min-height: 18px; }

    .card-divider { border: none; border-top: 1px solid #f0f0f0; margin: 28px 0; }

    /* ── INFO (read-only) ── */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    .info-item label {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.5px;
      color: #bbb; display: block; margin-bottom: 4px;
    }
    .info-item span { font-size: 16px; color: #222; font-weight: 500; }

    .prev-app {
      background: #fafafa; border-radius: 12px;
      padding: 16px 20px; font-size: 14px; color: #555;
      margin-bottom: 20px; display: flex; align-items: center; gap: 12px;
    }
    .prev-app strong { color: #222; }

    /* ── APPLICATION DETAIL TABLE ── */
    .app-detail-table {
      width: 100%; border-collapse: collapse;
      font-size: 14px; margin-bottom: 20px;
    }
    .app-detail-table td {
      padding: 8px 12px; border-bottom: 1px solid #f5f5f5;
    }
    .app-detail-table td:first-child {
      font-weight: 700; color: #999; text-transform: uppercase;
      font-size: 11px; letter-spacing: 0.5px; width: 130px;
    }
    .app-detail-table td:last-child { color: #222; }

    /* ── SUBMITTED ID IMAGE ── */
    .submitted-id-wrap {
      margin-top: 16px;
    }
    .submitted-id-label {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.5px;
      color: #bbb; display: block; margin-bottom: 10px;
    }
    .submitted-id-img {
      max-width: 340px;
      width: 100%;
      border-radius: 12px;
      border: 1.5px solid #e8e8e8;
      display: block;
      cursor: zoom-in;
      transition: box-shadow 0.2s, transform 0.2s;
    }
    .submitted-id-img:hover {
      box-shadow: 0 6px 24px rgba(0,0,0,0.13);
      transform: scale(1.01);
    }
    .submitted-id-hint {
      font-size: 12px; color: #bbb;
      margin: 6px 0 0; font-style: italic;
    }

    /* ── IMAGE ZOOM LIGHTBOX ── */
    #imgZoomOverlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.85);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      cursor: zoom-out;
    }
    #imgZoomOverlay.active { display: flex; }
    #imgZoomTarget {
      max-width: 92vw;
      max-height: 88vh;
      border-radius: 14px;
      object-fit: contain;
      box-shadow: 0 12px 60px rgba(0,0,0,0.5);
    }
    .zoom-close {
      position: absolute; top: 20px; right: 26px;
      color: #fff; font-size: 32px; font-weight: 300;
      cursor: pointer; line-height: 1;
      opacity: 0.8; transition: opacity 0.15s;
    }
    .zoom-close:hover { opacity: 1; }

    /* ── SENIOR PHONE HINT ── */
    .field-hint {
      font-size: 12px; color: #999; margin-top: 2px;
    }

    #editFormWrap { display: none; }

    @media (max-width: 600px) {
      .form-grid { grid-template-columns: 1fr; }
      .info-grid { grid-template-columns: 1fr; }
      .profile-card { padding: 24px 18px; }
      .form-section-label { grid-column: 1; }
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

  <!-- ── PROFILE CARD ── -->
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

          <!-- Password section -->
          <div class="form-section-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Change Password
            <span>— leave blank to keep current password</span>
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
          </div>

          <div class="form-group">
            <label>Confirm New Password</label>
            <div class="pass-wrap">
              <input type="password" name="confirm_pass" id="confirmPass" placeholder="Repeat new password" autocomplete="new-password">
              <button type="button" class="pass-toggle" onclick="togglePass('confirmPass', this)" tabindex="-1"><?= eyeIcon() ?></button>
            </div>
          </div>

        </div>
        <div style="margin-top:24px;">
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
    <p class="card-sub">Apply for a senior, PWD, or student discount on your orders.</p>

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
        <span class="discount-type-chip">
          <?= htmlspecialchars($discountApp['type']) ?>
        </span>
      <?php endif; ?>
    </div>

    <?php if ($discountStatus === 'approved'): ?>
      <div class="alert alert-success" style="margin-bottom:16px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Your <strong><?= htmlspecialchars($discountApp['type']) ?></strong> discount has been approved! Enjoy your discounted orders.
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
          <img
            src="<?= htmlspecialchars($discountApp['id_image_path']) ?>"
            alt="Submitted ID"
            class="submitted-id-img"
            onclick="openImgZoom(this.src)"
          >
          <p class="submitted-id-hint">Click image to enlarge</p>
        </div>
      <?php endif; ?>

    <?php elseif ($discountStatus === 'pending'): ?>
      <div class="prev-app">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e65c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
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
          <img
            src="<?= htmlspecialchars($discountApp['id_image_path']) ?>"
            alt="Submitted ID"
            class="submitted-id-img"
            onclick="openImgZoom(this.src)"
          >
          <p class="submitted-id-hint">Click image to enlarge</p>
        </div>
      <?php endif; ?>

    <?php elseif ($discountStatus === 'rejected'): ?>
      <div class="alert alert-error" style="margin-bottom:20px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Your previous <strong><?= htmlspecialchars($discountApp['type']) ?></strong> application was not approved.
        <?php if (!empty($discountApp['notes'])): ?>
          Reason: <?= htmlspecialchars($discountApp['notes']) ?>
        <?php endif; ?>
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
            <input
              type="text"
              name="senior_phone"
              id="seniorPhone"
              placeholder="e.g. 09xx-xxx-xxxx"
              value="<?= htmlspecialchars($user['phone']) ?>"
            >
            <span class="field-hint">This number will be used to verify your senior citizen identity.</span>
          </div>

          <div class="form-group full">
            <label>Upload Valid ID or Proof</label>
            <div class="upload-zone">
              <input type="file" name="id_image" accept="image/*" required onchange="showFileName(this)">
              <span class="upload-zone-icon">🪪</span>
              <span class="upload-zone-label"><strong>Click to upload</strong> or drag &amp; drop<br>JPG, PNG, WEBP · Max 5MB</span>
              <div id="fileNameDisplay"></div>
            </div>
          </div>

        </div>
        <button type="submit" class="btn-primary" style="margin-top:20px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          Submit Application
        </button>
      </form>
    <?php endif; ?>
  </div>

</div>

<!-- ── IMAGE ZOOM LIGHTBOX ── -->
<div id="imgZoomOverlay" onclick="closeImgZoom()">
  <span class="zoom-close" onclick="closeImgZoom()">✕</span>
  <img id="imgZoomTarget" src="" alt="ID enlarged">
</div>

<script>
  function toggleEdit() {
    var view = document.getElementById('infoView');
    var form = document.getElementById('editFormWrap');
    var hidden = form.style.display === 'none' || form.style.display === '';
    form.style.display = hidden ? 'block' : 'none';
    view.style.display = hidden ? 'none'  : 'block';
  }

  function showFileName(input) {
    var d = document.getElementById('fileNameDisplay');
    d.textContent = input.files.length ? '📎 ' + input.files[0].name : '';
  }

  var eyeSVG    = <?= json_encode(eyeIcon()) ?>;
  var eyeOffSVG = <?= json_encode(eyeOffIcon()) ?>;

  function togglePass(fieldId, btn) {
    var input = document.getElementById(fieldId);
    var showing = input.type === 'text';
    input.type   = showing ? 'password' : 'text';
    btn.innerHTML = showing ? eyeSVG : eyeOffSVG;
  }

  function toggleSeniorPhone(val) {
    var grp = document.getElementById('seniorPhoneGroup');
    if (grp) grp.style.display = val === 'Senior Citizen' ? 'flex' : 'none';
  }

  function openImgZoom(src) {
    document.getElementById('imgZoomTarget').src = src;
    document.getElementById('imgZoomOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeImgZoom() {
    document.getElementById('imgZoomOverlay').classList.remove('active');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImgZoom();
  });
</script>

</body>
</html>