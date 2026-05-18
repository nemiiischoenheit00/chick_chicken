<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$action = $_GET['action'] ?? '';

// ── DISCOUNT HELPER ──────────────────────────────────────────────────────────
// Returns the discount multiplier to apply to a user's order total.
// Matches the same logic used in order.php.
// The subquery pattern below is used directly in SQL via a CASE expression.
//
// Discount percent by type (must match order.php's getDiscountPercent):
//   senior / senior citizen → 20%
//   pwd / person with disability → 20%
//
// In SQL we express this as:  raw_total * (1 - discount_fraction)
// where discount_fraction is computed with a CASE on da.type.
//
// Reusable SQL fragment — call discountMultiplierSql() wherever needed.
function discountMultiplierSql(): string {
    // Returns a SQL expression that evaluates to the discount multiplier (0.0–1.0)
    // for the order's user, e.g. 0.80 for senior/pwd, 1.00 for no discount.
    return "
        CASE
            WHEN da_rev.status = 'approved'
                 AND LOWER(TRIM(da_rev.type)) IN ('senior','senior citizen','pwd','person with disability')
            THEN 0.80
            ELSE 1.00
        END
    ";
}

// Reusable LEFT JOIN fragment to attach the approved discount application
// for the order's user.  Alias: da_rev (to avoid clashing with other joins).
function discountJoinSql(): string {
    return "
        LEFT JOIN users u_rev ON u_rev.id = o.user_id
        LEFT JOIN discount_applications da_rev
               ON da_rev.user_id = o.user_id
              AND da_rev.status = 'approved'
    ";
}

switch ($action) {

    // ── TOTAL ORDERS ────────────────────────────────────────
    case 'total_orders':
        $total = $pdo->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'];

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
    // CHANGED: multiply each order's raw item total by the user's discount
    // multiplier so revenue reflects what customers actually paid.
    case 'revenue':
        $multiplier = discountMultiplierSql();
        $join       = discountJoinSql();

        $total = $pdo->query("
            SELECT COALESCE(SUM(oi.price * oi.quantity * ({$multiplier})), 0) AS total
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            {$join}
            WHERE o.status = 'completed'
        ")->fetch()['total'];

        $thisWeek = $pdo->query("
            SELECT COALESCE(SUM(oi.price * oi.quantity * ({$multiplier})), 0) AS total
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            {$join}
            WHERE o.status = 'completed'
              AND o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ")->fetch()['total'];

        $lastWeek = $pdo->query("
            SELECT COALESCE(SUM(oi.price * oi.quantity * ({$multiplier})), 0) AS total
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            {$join}
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
    // CHANGED: total now reflects the discounted amount the customer paid.
    case 'recent_orders':
        $multiplier = discountMultiplierSql();
        $join       = discountJoinSql();

        $stmt = $pdo->query("
            SELECT
                o.id,
                o.name,
                o.status,
                COALESCE(SUM(oi.price * oi.quantity * ({$multiplier})), 0) AS total
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            {$join}
            GROUP BY o.id, o.name, o.status
            ORDER BY o.created_at DESC
            LIMIT 8
        ");
        echo json_encode($stmt->fetchAll());
        break;

    // ── SALES OVERVIEW (last 7 days) ────────────────────────
    // CHANGED: revenue per day is now the discounted amount actually collected.
    case 'sales_overview':
        $multiplier = discountMultiplierSql();
        $join       = discountJoinSql();

        $stmt = $pdo->query("
            SELECT
                DATE(o.created_at) AS day,
                CAST(COALESCE(SUM(oi.price * oi.quantity * ({$multiplier})), 0) AS DECIMAL(10,2)) AS revenue,
                COUNT(DISTINCT o.id) AS orders
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            {$join}
            WHERE DATE(o.created_at) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
              AND o.status = 'completed'
            GROUP BY DATE(o.created_at)
            ORDER BY day ASC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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