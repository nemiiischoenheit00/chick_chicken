<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "not_logged_in"]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$action  = $_GET['action'] ?? 'active_orders';

try {
    if ($action === 'active_orders') {
        getActiveOrders($user_id);
    } else {
        echo json_encode(["error" => "Unknown action"]);
    }
} catch (Throwable $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

function getActiveOrders(int $user_id): void {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT
            o.id,
            o.status,
            o.created_at,
            o.total,
            o.discount_amount,
            o.original_total
        FROM orders o
        WHERE o.user_id = ?
          AND o.status NOT IN ('cancelled', 'completed')
        ORDER BY o.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(["orders" => []]);
        return;
    }

    $order['id']            = (int)$order['id'];
    $order['total']         = (float)$order['total'];
    $order['discount']      = (float)$order['discount_amount'];
    $order['status']        = $order['status'] === 'cooking' ? 'preparing' : $order['status'];
    unset($order['discount_amount'], $order['original_total']);

    $itemStmt = $pdo->prepare("
        SELECT
            oi.quantity,
            oi.price,
            p.name  AS product_name,
            p.image AS product_image
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
    ");
    $itemStmt->execute([$order['id']]);
    $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($order['items'] as &$item) {
        $item['quantity'] = (int)$item['quantity'];
        $item['price']    = (float)$item['price'];
    }

    echo json_encode(["orders" => [$order]]);
}
?>