<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// ── DB CONFIG ─────────────────────────────────────────────
$host = 'localhost';
$db   = 'chickchicken';
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

// ── AUTO-MIGRATE: add unit column to inventory if missing ────────────
try {
    $cols = $pdo->query("SHOW COLUMNS FROM inventory LIKE 'unit'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE inventory ADD COLUMN unit VARCHAR(30) NOT NULL DEFAULT 'pcs' AFTER low_stock_threshold");
    }
} catch (PDOException $e) {
    // Silently ignore if table doesn't exist yet
}

// ── AUTO-MIGRATE: create raw_ingredients table if missing ────────────
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS raw_ingredients (
            id                  INT AUTO_INCREMENT PRIMARY KEY,
            name                VARCHAR(120) NOT NULL,
            category            VARCHAR(60)  NOT NULL DEFAULT 'Ingredient',
            unit                VARCHAR(30)  NOT NULL DEFAULT 'pcs',
            initial_stock       DECIMAL(10,2) NOT NULL DEFAULT 0,
            remaining           DECIMAL(10,2) NOT NULL DEFAULT 0,
            low_stock_threshold DECIMAL(10,2) NOT NULL DEFAULT 10,
            supplier            VARCHAR(120) DEFAULT NULL,
            notes               TEXT         DEFAULT NULL,
            created_at          TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
            updated_at          TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
} catch (PDOException $e) {
    // table may already exist
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'list':                getInventory($pdo);           break;
            case 'stats':               getStats($pdo);               break;
            case 'categories':          getCategories($pdo);          break;
            // Raw ingredients
            case 'ri_list':             getRawIngredients($pdo);      break;
            case 'ri_stats':            getRawStats($pdo);            break;
            case 'ri_categories':       getRawCategories($pdo);       break;
            default:                    getInventory($pdo);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        switch ($action) {
            case 'add':          addItem($pdo, $data);          break;
            case 'update':       updateItem($pdo, $data);       break;
            case 'restock':      restockItem($pdo, $data);      break;
            case 'delete':       deleteItem($pdo, $data);       break;
            // Raw ingredients
            case 'ri_add':       riAdd($pdo, $data);            break;
            case 'ri_update':    riUpdate($pdo, $data);         break;
            case 'ri_restock':   riRestock($pdo, $data);        break;
            case 'ri_delete':    riDelete($pdo, $data);         break;
            default: echo json_encode(['error' => 'Unknown action']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

// ════════════════════════════════════════════
// PRODUCT INVENTORY HANDLERS
// ════════════════════════════════════════════

function getInventory(PDO $pdo) {
    $search   = $_GET['search']   ?? '';
    $category = $_GET['category'] ?? '';
    $status   = $_GET['status']   ?? '';

    $sql = "
        SELECT 
            i.id, i.product_id, p.name, p.category, p.price,
            i.initial_stock, i.remaining, i.low_stock_threshold,
            COALESCE(i.unit, 'pcs') AS unit,
            i.updated_at,
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
    if (empty($data['product_id'])) {
        echo json_encode(['error' => 'product_id required']); return;
    }
    $unit = trim($data['unit'] ?? 'pcs') ?: 'pcs';
    $stmt = $pdo->prepare("
        INSERT INTO inventory (product_id, initial_stock, remaining, low_stock_threshold, unit)
        VALUES (:pid, :init, :rem, :thresh, :unit)
        ON DUPLICATE KEY UPDATE
            initial_stock=:init, remaining=:rem,
            low_stock_threshold=:thresh, unit=:unit
    ");
    $stmt->execute([
        ':pid'    => $data['product_id'],
        ':init'   => $data['initial_stock'] ?? 50,
        ':rem'    => $data['remaining']     ?? 50,
        ':thresh' => $data['low_stock_threshold'] ?? 10,
        ':unit'   => $unit,
    ]);
    echo json_encode(['success' => true]);
}

function updateItem(PDO $pdo, $data) {
    if (empty($data['id'])) { echo json_encode(['error' => 'id required']); return; }
    $unit = trim($data['unit'] ?? 'pcs') ?: 'pcs';
    $stmt = $pdo->prepare("
        UPDATE inventory
        SET initial_stock=:init, remaining=:rem, low_stock_threshold=:thresh, unit=:unit
        WHERE id=:id
    ");
    $stmt->execute([
        ':init'   => $data['initial_stock'],
        ':rem'    => $data['remaining'],
        ':thresh' => $data['low_stock_threshold'] ?? 10,
        ':unit'   => $unit,
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

// ════════════════════════════════════════════
// RAW INGREDIENTS HANDLERS
// ════════════════════════════════════════════

function getRawIngredients(PDO $pdo) {
    $search   = $_GET['search']   ?? '';
    $category = $_GET['category'] ?? '';
    $status   = $_GET['status']   ?? '';

    $sql = "
        SELECT
            id, name, category, unit,
            initial_stock, remaining, low_stock_threshold,
            supplier, notes, updated_at,
            ROUND((remaining / NULLIF(initial_stock,0)) * 100, 1) AS stock_pct,
            CASE
                WHEN remaining = 0 THEN 'out'
                WHEN remaining <= low_stock_threshold THEN 'low'
                ELSE 'ok'
            END AS stock_status
        FROM raw_ingredients
        WHERE 1=1
    ";
    $params = [];

    if ($search) {
        $sql .= " AND (name LIKE :search OR supplier LIKE :search2)";
        $params[':search']  = "%$search%";
        $params[':search2'] = "%$search%";
    }
    if ($category) {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }
    if ($status === 'low') {
        $sql .= " AND remaining <= low_stock_threshold AND remaining > 0";
    } elseif ($status === 'out') {
        $sql .= " AND remaining = 0";
    } elseif ($status === 'ok') {
        $sql .= " AND remaining > low_stock_threshold";
    }

    $sql .= " ORDER BY stock_status DESC, name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll());
}

function getRawStats(PDO $pdo) {
    $total = $pdo->query("SELECT COUNT(*) FROM raw_ingredients")->fetchColumn();
    $low   = $pdo->query("SELECT COUNT(*) FROM raw_ingredients WHERE remaining <= low_stock_threshold AND remaining > 0")->fetchColumn();
    $out   = $pdo->query("SELECT COUNT(*) FROM raw_ingredients WHERE remaining = 0")->fetchColumn();

    echo json_encode([
        'total' => (int)$total,
        'low'   => (int)$low,
        'out'   => (int)$out,
        'ok'    => (int)$total - (int)$low - (int)$out,
    ]);
}

function getRawCategories(PDO $pdo) {
    $rows = $pdo->query("SELECT DISTINCT category FROM raw_ingredients WHERE category IS NOT NULL ORDER BY category")->fetchAll();
    echo json_encode(array_column($rows, 'category'));
}

function riAdd(PDO $pdo, $data) {
    if (empty($data['name'])) {
        echo json_encode(['error' => 'name required']); return;
    }
    $unit = trim($data['unit'] ?? 'pcs') ?: 'pcs';
    $stmt = $pdo->prepare("
        INSERT INTO raw_ingredients (name, category, unit, initial_stock, remaining, low_stock_threshold, supplier, notes)
        VALUES (:name, :cat, :unit, :init, :rem, :thresh, :supplier, :notes)
    ");
    $stmt->execute([
        ':name'     => trim($data['name']),
        ':cat'      => trim($data['category'] ?? 'Ingredient'),
        ':unit'     => $unit,
        ':init'     => $data['initial_stock'] ?? 0,
        ':rem'      => $data['remaining']     ?? 0,
        ':thresh'   => $data['low_stock_threshold'] ?? 10,
        ':supplier' => $data['supplier'] ?? null,
        ':notes'    => $data['notes']    ?? null,
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
}

function riUpdate(PDO $pdo, $data) {
    if (empty($data['id'])) { echo json_encode(['error' => 'id required']); return; }
    $unit = trim($data['unit'] ?? 'pcs') ?: 'pcs';
    $stmt = $pdo->prepare("
        UPDATE raw_ingredients
        SET name=:name, category=:cat, unit=:unit,
            initial_stock=:init, remaining=:rem,
            low_stock_threshold=:thresh,
            supplier=:supplier, notes=:notes
        WHERE id=:id
    ");
    $stmt->execute([
        ':name'     => trim($data['name']),
        ':cat'      => trim($data['category'] ?? 'Ingredient'),
        ':unit'     => $unit,
        ':init'     => $data['initial_stock'],
        ':rem'      => $data['remaining'],
        ':thresh'   => $data['low_stock_threshold'] ?? 10,
        ':supplier' => $data['supplier'] ?? null,
        ':notes'    => $data['notes']    ?? null,
        ':id'       => $data['id'],
    ]);
    echo json_encode(['success' => true]);
}

function riRestock(PDO $pdo, $data) {
    if (empty($data['id']) || !isset($data['amount'])) {
        echo json_encode(['error' => 'id and amount required']); return;
    }
    $pdo->prepare("
        UPDATE raw_ingredients SET remaining = remaining + :amt WHERE id = :id
    ")->execute([':amt' => (float)$data['amount'], ':id' => $data['id']]);
    echo json_encode(['success' => true]);
}

function riDelete(PDO $pdo, $data) {
    if (empty($data['id'])) { echo json_encode(['error' => 'id required']); return; }
    $pdo->prepare("DELETE FROM raw_ingredients WHERE id = :id")->execute([':id' => $data['id']]);
    echo json_encode(['success' => true]);
}
?>