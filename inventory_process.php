<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// ── DB CONFIG ─────────────────────────────────────────────
$host = 'localhost';
$db   = 'chickchicken'; // change to your DB name
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}


$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ── ROUTE ─────────────────────────────────────────────────
switch ($method) {
    case 'GET':
        switch ($action) {
            case 'list':       getInventory($pdo);  break;
            case 'stats':      getStats($pdo);      break;
            case 'categories': getCategories($pdo); break;
            default:           getInventory($pdo);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        switch ($action) {
            case 'add':    addItem($pdo, $data);    break;
            case 'update': updateItem($pdo, $data); break;
            case 'restock': restockItem($pdo, $data); break;
            case 'delete': deleteItem($pdo, $data); break;
            default: echo json_encode(['error' => 'Unknown action']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

// ── HANDLERS ──────────────────────────────────────────────

function getInventory(PDO $pdo) {
    $search   = $_GET['search']   ?? '';
    $category = $_GET['category'] ?? '';
    $status   = $_GET['status']   ?? '';

    $sql = "
        SELECT 
            i.id, i.product_id, p.name, p.category, p.price,
            i.initial_stock, i.remaining, i.low_stock_threshold, i.updated_at,
            ROUND((i.remaining / NULLIF(i.initial_stock,0)) * 100, 1) AS stock_pct,
            CASE 
                WHEN i.remaining = 0 THEN 'out'
                WHEN i.remaining <= i.low_stock_threshold THEN 'low'
                ELSE 'ok'
            END AS stock_status,
            (i.initial_stock - i.remaining) AS consumed,
            COALESCE(
                (SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.product_id = p.id),
                0
            ) AS total_sold
        FROM inventory i
        JOIN products p ON p.id = i.product_id
        WHERE 1=1
    ";
    $params = [];

    if ($search) {
        $sql .= " AND p.name LIKE :search";
        $params[':search'] = "%$search%";
    }
    if ($category) {
        $sql .= " AND p.category = :category";
        $params[':category'] = $category;
    }
    if ($status === 'low') {
        $sql .= " AND i.remaining <= i.low_stock_threshold AND i.remaining > 0";
    } elseif ($status === 'out') {
        $sql .= " AND i.remaining = 0";
    } elseif ($status === 'ok') {
        $sql .= " AND i.remaining > i.low_stock_threshold";
    }

    $sql .= " ORDER BY stock_status DESC, p.name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll());
}

function getStats(PDO $pdo) {
    $total      = $pdo->query("SELECT COUNT(*) FROM inventory")->fetchColumn();
    $low        = $pdo->query("SELECT COUNT(*) FROM inventory WHERE remaining <= low_stock_threshold AND remaining > 0")->fetchColumn();
    $out        = $pdo->query("SELECT COUNT(*) FROM inventory WHERE remaining = 0")->fetchColumn();
    $totalValue = $pdo->query("SELECT SUM(i.remaining * p.price) FROM inventory i JOIN products p ON p.id = i.product_id")->fetchColumn();

    echo json_encode([
        'total'       => (int)$total,
        'low'         => (int)$low,
        'out'         => (int)$out,
        'ok'          => (int)$total - (int)$low - (int)$out,
        'total_value' => round((float)$totalValue, 2),
    ]);
}

function getCategories(PDO $pdo) {
    $rows = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category")->fetchAll();
    echo json_encode(array_column($rows, 'category'));
}

function addItem(PDO $pdo, $data) {
    // Only add inventory row for existing products (products table is source of truth)
    if (empty($data['product_id'])) {
        echo json_encode(['error' => 'product_id required']); return;
    }
    $stmt = $pdo->prepare("
        INSERT INTO inventory (product_id, initial_stock, remaining, low_stock_threshold)
        VALUES (:pid, :init, :rem, :thresh)
        ON DUPLICATE KEY UPDATE initial_stock=:init, remaining=:rem, low_stock_threshold=:thresh
    ");
    $stmt->execute([
        ':pid'    => $data['product_id'],
        ':init'   => $data['initial_stock'] ?? 50,
        ':rem'    => $data['remaining']     ?? 50,
        ':thresh' => $data['low_stock_threshold'] ?? 10,
    ]);
    echo json_encode(['success' => true]);
}

function updateItem(PDO $pdo, $data) {
    if (empty($data['id'])) { echo json_encode(['error' => 'id required']); return; }
    $stmt = $pdo->prepare("
        UPDATE inventory
        SET initial_stock=:init, remaining=:rem, low_stock_threshold=:thresh
        WHERE id=:id
    ");
    $stmt->execute([
        ':init'   => $data['initial_stock'],
        ':rem'    => $data['remaining'],
        ':thresh' => $data['low_stock_threshold'] ?? 10,
        ':id'     => $data['id'],
    ]);
    echo json_encode(['success' => true]);
}

function restockItem(PDO $pdo, $data) {
    if (empty($data['id']) || !isset($data['amount'])) {
        echo json_encode(['error' => 'id and amount required']); return;
    }
    $pdo->prepare("
        UPDATE inventory SET remaining = remaining + :amt WHERE id = :id
    ")->execute([':amt' => (int)$data['amount'], ':id' => $data['id']]);
    echo json_encode(['success' => true]);
}

function deleteItem(PDO $pdo, $data) {
    if (empty($data['id'])) { echo json_encode(['error' => 'id required']); return; }
    $pdo->prepare("DELETE FROM inventory WHERE id = :id")->execute([':id' => $data['id']]);
    echo json_encode(['success' => true]);
}
?>
