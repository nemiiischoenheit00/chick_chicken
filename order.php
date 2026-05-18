<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

// ── AUTH CHECK ───────────────────────────────────────────────────────────────
// if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
//     echo json_encode(["error" => "unauthorized"]);
//     exit;
// }

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ── ROUTER ───────────────────────────────────────────────────────────────────
try {
    if ($method === 'GET' && $action === 'list') {
        listOrders();
    } elseif ($method === 'GET' && $action === 'get') {
        getOrder();
    } elseif ($action === 'update_status') {
        updateStatus();
    } elseif ($action === 'delete') {
        deleteOrder();
    } else {
        respond(["error" => "Unknown action: $action"], 400);
    }
} catch (Throwable $e) {
    respond(["error" => $e->getMessage()], 500);
}

// ── LIST ALL ORDERS (with pagination + optional search) ──────────────────────
function listOrders(): void {
    global $pdo;

    $page   = max(1, (int)($_GET['page']  ?? 1));
    $limit  = max(1, min(100, (int)($_GET['limit'] ?? 20)));
    $offset = ($page - 1) * $limit;
    $search = trim($_GET['search'] ?? '');
    $status = trim($_GET['status'] ?? '');
    $sort   = in_array($_GET['sort'] ?? '', ['asc', 'desc']) ? $_GET['sort'] : 'desc';

    $where  = [];
    $params = [];

    if ($search !== '') {
        $where[]  = "(o.name LIKE ? OR o.email LIKE ? OR o.phone LIKE ? OR o.id = ?)";
        $like     = "%$search%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = is_numeric($search) ? (int)$search : -1;
    }

    $allowed_statuses = ['pending', 'confirmed', 'preparing', 'in_transit', 'completed', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        $where[]  = "o.status = ?";
        $params[] = $status;
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // Total count for pagination
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM orders o $whereSql");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // Fetch orders — read stored totals directly from the orders table
    $stmt = $pdo->prepare("
        SELECT
            o.id,
            o.user_id,
            o.name,
            o.phone,
            o.email,
            o.address,
            o.payment_method,
            o.branch,
            o.status,
            o.created_at,
            COALESCE(o.original_total,  0) AS original_total,
            COALESCE(o.discount_amount, 0) AS discount_amount,
            COALESCE(o.discount_rate,   0) AS discount_rate,
            COALESCE(o.discount_type,  '') AS discount_type,
            COALESCE(o.total,           0) AS total
        FROM orders o
        $whereSql
        ORDER BY o.created_at $sort
        LIMIT ? OFFSET ?
    ");

    $paramIndex = 1;
    foreach ($params as $val) {
        $stmt->bindValue($paramIndex++, $val);
    }
    $stmt->bindValue($paramIndex++, $limit,  PDO::PARAM_INT);
    $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast numeric fields and compute discount_pct for the frontend badge
    foreach ($orders as &$row) {
        $row['original_total']  = (float)$row['original_total'];
        $row['discount_amount'] = (float)$row['discount_amount'];
        $row['discount_rate']   = (float)$row['discount_rate'];
        $row['total']           = (float)$row['total'];
        $row['discount_pct']    = round((float)$row['discount_rate'] * 100);
    }
    unset($row);

    respond([
        "orders"      => $orders,
        "total"       => $total,
        "page"        => $page,
        "limit"       => $limit,
        "total_pages" => (int)ceil($total / $limit),
    ]);
}

// ── GET SINGLE ORDER WITH ITEMS ──────────────────────────────────────────────
function getOrder(): void {
    global $pdo;

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        respond(["error" => "Invalid order ID"], 400);
        return;
    }

    $stmt = $pdo->prepare("
        SELECT
            o.id, o.user_id, o.name, o.phone, o.email, o.address,
            o.payment_method, o.branch, o.status, o.created_at,
            o.gcash_proof, o.gcash_reference,
            COALESCE(o.original_total,  0) AS original_total,
            COALESCE(o.discount_amount, 0) AS discount_amount,
            COALESCE(o.discount_rate,   0) AS discount_rate,
            COALESCE(o.discount_type,  '') AS discount_type,
            COALESCE(o.total,           0) AS total
        FROM orders o
        WHERE o.id = ?
    ");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        respond(["error" => "Order not found"], 404);
        return;
    }

    $itemStmt = $pdo->prepare("
        SELECT
            oi.*,
            p.name  AS product_name,
            p.image AS product_image
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
    ");
    $itemStmt->execute([$id]);
    $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

    $order['original_total']  = (float)$order['original_total'];
    $order['discount_amount'] = (float)$order['discount_amount'];
    $order['discount_rate']   = (float)$order['discount_rate'];
    $order['total']           = (float)$order['total'];
    $order['discount_pct']    = round((float)$order['discount_rate'] * 100);
    $order['gcash_reference'] = $order['gcash_reference'] ?? null;
    $order['gcash_proof']     = $order['gcash_proof']     ?? null;

    respond($order);
}

// ── UPDATE ORDER STATUS ──────────────────────────────────────────────────────
function updateStatus(): void {
    global $pdo;

    $data   = json_decode(file_get_contents("php://input"), true) ?? [];
    $id     = (int)($data['id']     ?? 0);
    $status = trim($data['status'] ?? '');

    if ($id <= 0) {
        respond(["error" => "Invalid order ID"], 400);
        return;
    }

    $allowed = ['pending', 'confirmed', 'preparing', 'in_transit', 'completed', 'cancelled'];
    if (!in_array($status, $allowed)) {
        respond(["error" => "Invalid status. Must be one of: " . implode(', ', $allowed)], 400);
        return;
    }

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    if ($stmt->rowCount() === 0) {
        respond(["error" => "Order not found or status unchanged"], 404);
        return;
    }

    respond(["success" => true, "id" => $id, "status" => $status]);
}

// ── DELETE ORDER ─────────────────────────────────────────────────────────────
function deleteOrder(): void {
    global $pdo;

    $data = json_decode(file_get_contents("php://input"), true) ?? [];
    $id   = (int)($data['id'] ?? 0);

    if ($id <= 0) {
        respond(["error" => "Invalid order ID"], 400);
        return;
    }

    // Delete items first (foreign key safety)
    $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        respond(["error" => "Order not found"], 404);
        return;
    }

    respond(["success" => true, "deleted_id" => $id]);
}

// ── HELPER ───────────────────────────────────────────────────────────────────
function respond(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data);
    exit;
}
?>