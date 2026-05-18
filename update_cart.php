<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$cartId = isset($data['cart_id'])  ? (int)$data['cart_id']  : 0;
$qty    = isset($data['quantity']) ? (int)$data['quantity']  : 1;
$option = $data['option'] ?? '';
$sauce  = $data['sauce']  ?? '';
$extra  = $data['extra']  ?? '';
$mix    = $data['mix']    ?? '';

if (!$cartId || $qty < 1) {
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

try {
    // ── Verify ownership + fetch base price from products ──
    $stmt = $pdo->prepare(
        "SELECT c.id, p.price
         FROM cart c
         JOIN products p ON p.id = c.product_id
         WHERE c.id = ? AND c.user_id = ?"
    );
    $stmt->execute([$cartId, $_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['error' => 'Item not found']);
        exit;
    }

    // ── Recalculate unit price with upcharges ──
    $basePrice = (float)$row['price'];
    $isDouble  = stripos($option, 'double') !== false;
    $hasExtra  = !empty($extra);
    $unitPrice = $basePrice + ($isDouble ? 100 : 0) + ($hasExtra ? 20 : 0);

    // ── Update the row (no price column in cart table) ──
    $upd = $pdo->prepare(
        "UPDATE cart
         SET quantity=?, option_selected=?, sauce=?, extra_flavor=?, mix_preference=?
         WHERE id=?"
    );
    $upd->execute([$qty, $option, $sauce, $extra, $mix, $cartId]);

    echo json_encode(['success' => true, 'new_price' => $unitPrice]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}