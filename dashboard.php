<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$action = $_GET['action'] ?? '';

switch ($action) {

    // ── TOTAL ORDERS ────────────────────────────────────────
    case 'total_orders':
        $total = $pdo->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'];

        // % change vs last week
        $thisWeek = $pdo->query("
            SELECT COUNT(*) as count FROM orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['count'];

        $lastWeek = $pdo->query("
            SELECT COUNT(*) as count FROM orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
              AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['count'];

        $change = $lastWeek > 0 ? round((($thisWeek - $lastWeek) / $lastWeek) * 100) : 0;
        echo json_encode(['total' => $total, 'change' => $change]);
        break;

    // ── TOTAL CUSTOMERS ─────────────────────────────────────
    case 'total_customers':
        $total = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];

        $thisWeek = $pdo->query("
            SELECT COUNT(*) as count FROM users
            WHERE id IN (
                SELECT DISTINCT user_id FROM orders
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            )
        ")->fetch()['count'];

        $lastWeek = $pdo->query("
            SELECT COUNT(*) as count FROM users
            WHERE id IN (
                SELECT DISTINCT user_id FROM orders
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
                  AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
            )
        ")->fetch()['count'];

        $change = $lastWeek > 0 ? round((($thisWeek - $lastWeek) / $lastWeek) * 100) : 0;
        echo json_encode(['total' => $total, 'change' => $change]);
        break;

    // ── REVENUE ─────────────────────────────────────────────
    case 'revenue':
        $total = $pdo->query("
            SELECT COALESCE(SUM(oi.price * oi.quantity), 0) as total
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE o.status = 'completed'
        ")->fetch()['total'];

        $thisWeek = $pdo->query("
            SELECT COALESCE(SUM(oi.price * oi.quantity), 0) as total
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE o.status = 'completed'
              AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['total'];

        $lastWeek = $pdo->query("
            SELECT COALESCE(SUM(oi.price * oi.quantity), 0) as total
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE o.status = 'completed'
              AND o.created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
              AND o.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['total'];

        $change = $lastWeek > 0 ? round((($thisWeek - $lastWeek) / $lastWeek) * 100) : 0;
        echo json_encode(['total' => round((float)$total, 2), 'change' => $change]);
        break;

    // ── PENDING ORDERS ──────────────────────────────────────
    case 'pending_orders':
        $total = $pdo->query("
            SELECT COUNT(*) as count FROM orders WHERE status = 'pending'
        ")->fetch()['count'];

        $thisWeek = $pdo->query("
            SELECT COUNT(*) as count FROM orders
            WHERE status = 'pending'
              AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['count'];

        $lastWeek = $pdo->query("
            SELECT COUNT(*) as count FROM orders
            WHERE status = 'pending'
              AND created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
              AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['count'];

        $change = $lastWeek > 0 ? round((($thisWeek - $lastWeek) / $lastWeek) * 100) : 0;
        echo json_encode(['total' => $total, 'change' => $change]);
        break;

    // ── RECENT ORDERS ───────────────────────────────────────
    case 'recent_orders':
        $stmt = $pdo->query("
            SELECT o.id, o.name, o.status,
                   COALESCE(SUM(oi.price * oi.quantity), 0) as total
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.created_at DESC
            LIMIT 8
        ");
        echo json_encode($stmt->fetchAll());
        break;

    // ── SALES OVERVIEW (last 7 days) ────────────────────────
    case 'sales_overview':
        $stmt = $pdo->query("
            SELECT DATE(o.created_at) as day,
                   CAST(COALESCE(SUM(oi.price * oi.quantity), 0) AS DECIMAL(10,2)) as revenue,
                   COUNT(DISTINCT o.id) as orders
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            WHERE DATE(o.created_at) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
              AND o.status = 'completed'
            GROUP BY DATE(o.created_at)
            ORDER BY day ASC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Ensure revenue is always a float, never a string
        $rows = array_map(function($r) {
            $r['revenue'] = (float) $r['revenue'];
            $r['orders']  = (int)   $r['orders'];
            return $r;
        }, $rows);
        echo json_encode($rows);
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
}
?>