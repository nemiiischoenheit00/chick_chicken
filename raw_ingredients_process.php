<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// ── POST body (JSON) ──────────────────────────────────────────
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true) ?? [];

// ══════════════════════════════════════════════════════════════
//  GET ACTIONS
// ══════════════════════════════════════════════════════════════

if ($action === 'stats') {
    $row = $pdo->query("
        SELECT
            COUNT(*)                                         AS total,
            SUM(stock_status = 'ok')                         AS ok,
            SUM(stock_status = 'low')                        AS low,
            SUM(stock_status = 'out')                        AS `out`
        FROM raw_ingredients
    ")->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
    exit;
}

if ($action === 'categories') {
    $rows = $pdo->query("SELECT DISTINCT category FROM raw_ingredients WHERE category IS NOT NULL ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($rows);
    exit;
}

if ($action === 'list') {
    $rows = $pdo->query("
        SELECT
            id, name, category, unit,
            initial_stock, remaining, low_stock_threshold,
            supplier, notes,
            stock_status, stock_pct,
            created_at, updated_at
        FROM raw_ingredients
        ORDER BY category, name
    ")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($action === 'products') {
    // Returns all products for the link-modal dropdown
    $rows = $pdo->query("SELECT id, name FROM products ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($action === 'links') {
    // Returns existing product_ingredients links for a product
    $productId = intval($_GET['product_id'] ?? 0);
    if (!$productId) { echo json_encode([]); exit; }
    $stmt = $pdo->prepare("
        SELECT ingredient_id, quantity_used
        FROM product_ingredients
        WHERE product_id = ?
    ");
    $stmt->execute([$productId]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ══════════════════════════════════════════════════════════════
//  POST ACTIONS
// ══════════════════════════════════════════════════════════════

if ($action === 'add') {
    $name      = trim($data['name']      ?? '');
    $unit      = trim($data['unit']      ?? 'kg');
    $category  = trim($data['category']  ?? '');
    $supplier  = trim($data['supplier']  ?? '');
    $initial   = floatval($data['initial_stock']       ?? 0);
    $remaining = floatval($data['remaining']            ?? 0);
    $threshold = floatval($data['low_stock_threshold'] ?? 10);
    $notes     = trim($data['notes']     ?? '');

    if (!$name) { echo json_encode(['success' => false, 'error' => 'Name is required.']); exit; }

    $stmt = $pdo->prepare("
        INSERT INTO raw_ingredients
            (name, unit, category, supplier, initial_stock, remaining, low_stock_threshold, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $unit, $category ?: null, $supplier ?: null, $initial, $remaining, $threshold, $notes ?: null]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

if ($action === 'update') {
    $id        = intval($data['id'] ?? 0);
    $name      = trim($data['name']      ?? '');
    $unit      = trim($data['unit']      ?? 'kg');
    $category  = trim($data['category']  ?? '');
    $supplier  = trim($data['supplier']  ?? '');
    $initial   = floatval($data['initial_stock']       ?? 0);
    $remaining = floatval($data['remaining']            ?? 0);
    $threshold = floatval($data['low_stock_threshold'] ?? 10);
    $notes     = trim($data['notes']     ?? '');

    if (!$id || !$name) { echo json_encode(['success' => false, 'error' => 'Invalid data.']); exit; }

    $stmt = $pdo->prepare("
        UPDATE raw_ingredients
        SET name=?, unit=?, category=?, supplier=?, initial_stock=?, remaining=?, low_stock_threshold=?, notes=?
        WHERE id=?
    ");
    $stmt->execute([$name, $unit, $category ?: null, $supplier ?: null, $initial, $remaining, $threshold, $notes ?: null, $id]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'restock') {
    $id     = intval($data['id']     ?? 0);
    $amount = floatval($data['amount'] ?? 0);
    if (!$id || $amount <= 0) { echo json_encode(['success' => false, 'error' => 'Invalid input.']); exit; }

    $stmt = $pdo->prepare("
        UPDATE raw_ingredients
        SET initial_stock = initial_stock + ?,
            remaining     = remaining     + ?
        WHERE id = ?
    ");
    $stmt->execute([$amount, $amount, $id]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'delete') {
    $id = intval($data['id'] ?? 0);
    if (!$id) { echo json_encode(['success' => false, 'error' => 'Invalid ID.']); exit; }
    // product_ingredients rows cascade via FK
    $pdo->prepare("DELETE FROM raw_ingredients WHERE id=?")->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'save_links') {
    // Replace all links for this product with the submitted set
    $productId = intval($data['product_id'] ?? 0);
    $links     = $data['links'] ?? [];
    if (!$productId) { echo json_encode(['success' => false, 'error' => 'No product selected.']); exit; }

    try {
        $pdo->beginTransaction();

        // Delete existing links for this product
        $pdo->prepare("DELETE FROM product_ingredients WHERE product_id = ?")->execute([$productId]);

        // Insert new links
        $ins = $pdo->prepare("
            INSERT INTO product_ingredients (product_id, ingredient_id, quantity_used)
            VALUES (?, ?, ?)
        ");
        foreach ($links as $lk) {
            $ingId = intval($lk['ingredient_id'] ?? 0);
            $qty   = floatval($lk['quantity_used'] ?? 0);
            if (!$ingId || $qty <= 0) continue;
            $ins->execute([$productId, $ingId, $qty]);
        }

        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// ── Deduct raw ingredients (called internally by place-order.php) ──
// This function is used directly by place-order.php via include,
// but can also be called as an action for manual adjustments.
if ($action === 'deduct') {
    $productId = intval($data['product_id'] ?? 0);
    $qty       = intval($data['quantity']   ?? 0);
    if (!$productId || $qty < 1) { echo json_encode(['success' => false, 'error' => 'Invalid input.']); exit; }

    deductRawIngredients($pdo, $productId, $qty);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Unknown action']);

// ══════════════════════════════════════════════════════════════
//  HELPER — reusable by place-order.php
// ══════════════════════════════════════════════════════════════
function deductRawIngredients(PDO $pdo, int $productId, int $orderedQty): void {
    $stmt = $pdo->prepare("
        SELECT ingredient_id, quantity_used
        FROM   product_ingredients
        WHERE  product_id = ?
    ");
    $stmt->execute([$productId]);
    $links = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $deduct = $pdo->prepare("
        UPDATE raw_ingredients
        SET    remaining = GREATEST(remaining - ?, 0)
        WHERE  id = ?
    ");

    foreach ($links as $lk) {
        $totalUsed = $lk['quantity_used'] * $orderedQty;
        $deduct->execute([$totalUsed, $lk['ingredient_id']]);
    }
}