<?php
/**
 * dashboard.php  –  Admin dashboard data API
 * Called by admin.php via fetch('dashboard.php?action=...')
 *
 * Actions:
 *   total_orders    → { total, change }
 *   total_customers → { total, change }
 *   revenue         → { total, change }
 *   pending_orders  → { total, change }
 *   recent_orders   → [ {id, total, status}, … ]
 *   sales_overview  → [ {day, revenue}, … ]   (last 7 days)
 */

session_start();

// Guard: admin only
if (empty($_SESSION['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

header('Content-Type: application/json');
require 'db.php';

$action = $_GET['action'] ?? '';

/**
 * Helper: percentage change vs same period last week.
 * Returns rounded integer (positive = up, negative = down, 0 = flat).
 */
function weekChange(PDO $pdo, string $sql_this, string $sql_last, array $params = []): int {
    $s = $pdo->prepare($sql_this); $s->execute($params); $this_val = (float)$s->fetchColumn();
    $s = $pdo->prepare($sql_last); $s->execute($params); $last_val = (float)$s->fetchColumn();
    if ($last_val == 0) return $this_val > 0 ? 100 : 0;
    return (int)round((($this_val - $last_val) / $last_val) * 100);
}

try {
    switch ($action) {

        /* ── Total orders ──────────────────────────────────── */
        case 'total_orders': {
            $s = $pdo->query("SELECT COUNT(*) FROM orders");
            $total = (int)$s->fetchColumn();

            $change = weekChange($pdo,
                "SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)",
                "SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
                                              AND  created_at <  DATE_SUB(CURDATE(), INTERVAL 7 DAY)"
            );
            echo json_encode(['total' => $total, 'change' => $change]);
            break;
        }

        /* ── Total customers ───────────────────────────────── */
        case 'total_customers': {
            $s = $pdo->query("SELECT COUNT(*) FROM users");
            $total = (int)$s->fetchColumn();

            $change = weekChange($pdo,
                "SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)",
                "SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
                                              AND created_at <  DATE_SUB(CURDATE(), INTERVAL 7 DAY)"
            );
            echo json_encode(['total' => $total, 'change' => $change]);
            break;
        }

        /* ── Revenue ───────────────────────────────────────── */
        case 'revenue': {
            // Try common column names: total_amount, total, amount, grand_total
            $cols = ['total_amount', 'total', 'amount', 'grand_total'];
            $col  = 'total_amount'; // default
            foreach ($cols as $c) {
                try {
                    $pdo->query("SELECT `$c` FROM orders LIMIT 1");
                    $col = $c;
                    break;
                } catch (PDOException $e) { /* try next */ }
            }

            $s = $pdo->query("SELECT COALESCE(SUM(`$col`),0) FROM orders WHERE status NOT IN ('cancelled','canceled','Cancelled','Canceled')");
            $total = (float)$s->fetchColumn();

            $change = weekChange($pdo,
                "SELECT COALESCE(SUM(`$col`),0) FROM orders
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    AND status NOT IN ('cancelled','canceled','Cancelled','Canceled')",
                "SELECT COALESCE(SUM(`$col`),0) FROM orders
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
                    AND created_at <  DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    AND status NOT IN ('cancelled','canceled','Cancelled','Canceled')"
            );
            echo json_encode(['total' => $total, 'change' => $change]);
            break;
        }

        /* ── Pending orders ────────────────────────────────── */
        case 'pending_orders': {
            $s = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'pending'");
            $s->execute();
            $total = (int)$s->fetchColumn();

            $change = weekChange($pdo,
                "SELECT COUNT(*) FROM orders WHERE LOWER(status)='pending'
                  AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)",
                "SELECT COUNT(*) FROM orders WHERE LOWER(status)='pending'
                  AND created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
                  AND created_at <  DATE_SUB(CURDATE(), INTERVAL 7 DAY)"
            );
            echo json_encode(['total' => $total, 'change' => $change]);
            break;
        }

        /* ── Recent orders (last 5) ────────────────────────── */
        case 'recent_orders': {
            // Auto-detect amount column
            $amtCol = 'total_amount';
            foreach (['total_amount','total','amount','grand_total'] as $c) {
                try { $pdo->query("SELECT `$c` FROM orders LIMIT 1"); $amtCol = $c; break; }
                catch (PDOException $e) {}
            }

            $s = $pdo->query("
                SELECT id, `$amtCol` AS total, status
                FROM orders
                ORDER BY created_at DESC
                LIMIT 5
            ");
            echo json_encode($s->fetchAll(PDO::FETCH_ASSOC));
            break;
        }

        /* ── Sales overview (last 7 days) ──────────────────── */
        case 'sales_overview': {
            $amtCol = 'total_amount';
            foreach (['total_amount','total','amount','grand_total'] as $c) {
                try { $pdo->query("SELECT `$c` FROM orders LIMIT 1"); $amtCol = $c; break; }
                catch (PDOException $e) {}
            }

            $s = $pdo->query("
                SELECT DATE(created_at) AS day, COALESCE(SUM(`$amtCol`),0) AS revenue
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                  AND status NOT IN ('cancelled','canceled','Cancelled','Canceled')
                GROUP BY DATE(created_at)
                ORDER BY day ASC
            ");
            echo json_encode($s->fetchAll(PDO::FETCH_ASSOC));
            break;
        }

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
