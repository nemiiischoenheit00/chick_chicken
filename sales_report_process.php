<?php
header('Content-Type: application/json');
require_once 'db.php';

$from     = $_GET['from']     ?? '';
$to       = $_GET['to']       ?? '';
$status   = $_GET['status']   ?? '';
$payment  = $_GET['payment']  ?? '';
$branch   = $_GET['branch']   ?? '';
$discount = $_GET['discount'] ?? '';

$where  = [];
$params = [];

if ($from)    { $where[] = 'o.created_at >= :from';   $params[':from']   = $from . ' 00:00:00'; }
if ($to)      { $where[] = 'o.created_at <= :to';     $params[':to']     = $to   . ' 23:59:59'; }
if ($status)  { $where[] = 'o.status = :status';      $params[':status'] = $status; }
if ($payment) { $where[] = 'o.payment_method = :pay'; $params[':pay']    = $payment; }
if ($branch)  { $where[] = 'o.branch = :branch';      $params[':branch'] = $branch; }
if ($discount === 'yes') { $where[] = 'COALESCE(o.discount_amount, 0) > 0'; }
if ($discount === 'no')  { $where[] = 'COALESCE(o.discount_amount, 0) = 0'; }

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Always SUM from order_items for the raw subtotal — this is the source of truth.
// Use stored discount columns if present, otherwise fall back to 0.
$sql = "
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
        COALESCE(o.discount_amount, 0) AS discount_amount,
        COALESCE(o.discount_rate,   0) AS discount_rate,
        COALESCE(o.discount_type,  '') AS discount_type,
        -- Always recalculate item subtotal from order_items
        COALESCE(
            (SELECT SUM(oi.price * oi.quantity)
             FROM order_items oi
             WHERE oi.order_id = o.id), 0
        ) AS original_total,
        -- Final total = item subtotal minus stored discount (0 if no discount column yet)
        COALESCE(
            (SELECT SUM(oi.price * oi.quantity)
             FROM order_items oi
             WHERE oi.order_id = o.id), 0
        ) - COALESCE(o.discount_amount, 0) AS total
    FROM orders o
    $whereSQL
    ORDER BY o.created_at DESC
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($transactions as &$row) {
        $row['original_total']  = (float)$row['original_total'];
        $row['discount_amount'] = (float)$row['discount_amount'];
        $row['discount_rate']   = (float)$row['discount_rate'];
        $row['total']           = (float)$row['total'];
        $row['discount_pct']    = round((float)$row['discount_rate'] * 100);
    }
    unset($row);

    echo json_encode(['transactions' => $transactions]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>