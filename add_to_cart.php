<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// ── Auth check ───────────────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

// ── Parse JSON body ──────────────────────────────────────────────────────────
$data = json_decode(file_get_contents('php://input'), true);

$product_id = intval($data['product_id'] ?? 0);
$quantity   = max(1, intval($data['quantity'] ?? 1));
$option     = trim($data['option'] ?? '');
$sauce      = trim($data['sauce']  ?? '');
$extra      = trim($data['extra']  ?? '');
$mix        = trim($data['mix']    ?? '');

if ($product_id <= 0) {
    echo json_encode(['error' => 'Invalid product.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// ── Block if user already has an active order ────────────────────────────────
$activeOrder = $pdo->prepare("
    SELECT id FROM orders
    WHERE user_id = ? AND status IN ('pending','confirmed','cooking','in_transit')
    LIMIT 1
");
$activeOrder->execute([$user_id]);
if ($activeOrder->fetch()) {
    echo json_encode(['error' => 'active_order']);
    exit;
}

// ── Verify product exists ────────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$product_id]);
if (!$stmt->fetch()) {
    echo json_encode(['error' => 'Product not found.']);
    exit;
}

// ── Check for existing identical cart line ───────────────────────────────────
$check = $pdo->prepare("
    SELECT id, quantity FROM cart
    WHERE user_id = ? AND product_id = ?
      AND COALESCE(option_selected, '') = ?
      AND COALESCE(sauce, '')           = ?
      AND COALESCE(extra_flavor, '')    = ?
      AND COALESCE(mix_preference, '')  = ?
    LIMIT 1
");
$check->execute([$user_id, $product_id, $option, $sauce, $extra, $mix]);
$existing = $check->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // Increment quantity on the existing line
    $upd = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
    $upd->execute([$quantity, $existing['id']]);
} else {
    // Insert a new cart line
    $ins = $pdo->prepare("
        INSERT INTO cart (user_id, product_id, quantity, option_selected, sauce, extra_flavor, mix_preference)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $ins->execute([$user_id, $product_id, $quantity, $option, $sauce, $extra, $mix]);
}

echo json_encode(['success' => true]);