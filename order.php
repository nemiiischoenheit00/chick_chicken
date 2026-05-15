<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

// ── AUTH CHECK ───────────────────────────────────────────────────────────────
// Adjust this check to match however you verify admin users.
// Example: check a session flag set at admin login.
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

// ── DISCOUNT PERCENTAGE HELPER ───────────────────────────────────────────────
// Returns the discount percentage for a given discount type.
// Adjust these values to match your actual discount policy.
function getDiscountPercent(string $type): float {
    return match (strtolower(trim($type))) {
        'senior', 'senior citizen' => 20.0,
        'pwd', 'person with disability' => 20.0,
        default => 0.0,
    };
}

// ── LIST ALL ORDERS (with pagination + optional search) ──────────────────────
function listOrders(): void {
    global $pdo;

    $page    = max(1, (int)($_GET['page']    ?? 1));
    $limit   = max(1, min(100, (int)($_GET['limit'] ?? 20)));
    $offset  = ($page - 1) * $limit;
    $search  = trim($_GET['search']  ?? '');
    $status  = trim($_GET['status']  ?? '');
    $sort    = in_array($_GET['sort'] ?? '', ['asc','desc']) ? $_GET['sort'] : 'desc';

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

    if (in_array($status, ['pending', 'confirmed', 'cooking', 'in_transit', 'completed', 'cancelled'])) {
        $where[]  = "o.status = ?";
        $params[] = $status;
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // Total count (for pagination)
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM orders o $whereSql");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // ── CHANGED: JOIN users + discount_applications to get discount info ──
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
            COALESCE(SUM(oi.price * oi.quantity), 0)          AS raw_total,
            COALESCE(u.discount_status, 'none')                AS discount_status,
            COALESCE(da.type, '')                              AS discount_type
        FROM orders o
        LEFT JOIN order_items oi ON oi.order_id = o.id
        LEFT JOIN users u ON u.id = o.user_id
        LEFT JOIN discount_applications da
               ON da.user_id = o.user_id
              AND da.status = 'approved'
        $whereSql
        GROUP BY o.id, u.discount_status, da.type
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

    // ── CHANGED: Compute discount_amount and final total per order ──
    foreach ($orders as &$row) {
        $rawTotal       = (float)$row['raw_total'];
        $discountType   = $row['discount_type'] ?? '';
        $discountPct    = ($row['discount_status'] === 'approved' && $discountType !== '')
                            ? getDiscountPercent($discountType)
                            : 0.0;
        $discountAmount = round($rawTotal * ($discountPct / 100), 2);

        $row['original_total']  = $rawTotal;
        $row['discount_amount'] = $discountAmount;
        $row['discount_pct']    = $discountPct;
        $row['total']           = round($rawTotal - $discountAmount, 2);

        unset($row['raw_total']);
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

    // ── CHANGED: Also fetch discount_status and discount_type from users/discount_applications ──
    $stmt = $pdo->prepare("
        SELECT
            o.*,
            COALESCE(u.discount_status, 'none') AS discount_status,
            COALESCE(da.type, '')               AS discount_type
        FROM orders o
        LEFT JOIN users u ON u.id = o.user_id
        LEFT JOIN discount_applications da
               ON da.user_id = o.user_id
              AND da.status = 'approved'
        WHERE o.id = ?
    ");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        respond(["error" => "Order not found"], 404);
        return;
    }

    // Fetch order items with product details
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

    // ── CHANGED: Compute discount and totals ──
    $rawTotal       = array_reduce(
        $order['items'],
        fn($carry, $item) => $carry + ($item['price'] * $item['quantity']),
        0.0
    );
    $discountType   = $order['discount_type'] ?? '';
    $discountPct    = ($order['discount_status'] === 'approved' && $discountType !== '')
                        ? getDiscountPercent($discountType)
                        : 0.0;
    $discountAmount = round($rawTotal * ($discountPct / 100), 2);

    $order['original_total']  = round($rawTotal, 2);
    $order['discount_amount'] = $discountAmount;
    $order['discount_pct']    = $discountPct;
    $order['total']           = round($rawTotal - $discountAmount, 2);

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

    $allowed = ['pending', 'confirmed', 'cooking', 'in_transit', 'completed', 'cancelled'];
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