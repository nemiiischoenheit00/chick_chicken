<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "not_logged_in"]);
  exit;
}

$data = $_POST;
if (empty($data)) { echo json_encode(["error" => "No data received"]); exit; }

$user_id        = $_SESSION['user_id'];
$name           = $data['name'];
$phone          = $data['phone'];
$email          = $data['email'];
$address        = $data['address'];
$payment_method = in_array($data['payment_method'], ['gcash', 'cod'])
  ? $data['payment_method']
  : 'cod';

$discount_type = $data['discount_type'] ?? '';
$discount_rate = floatval($data['discount_rate'] ?? 0);

// 1. Fetch cart — explicitly alias p.price as product_price to avoid column conflicts
$stmt = $pdo->prepare("
  SELECT 
    c.id,
    c.user_id,
    c.product_id,
    c.quantity,
    c.option_selected,
    c.sauce,
    c.extra_flavor,
    c.mix_preference,
    p.price AS product_price
  FROM cart c 
  JOIN products p ON c.product_id = p.id 
  WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
  echo json_encode(["error" => "Cart is empty"]);
  exit;
}

// 2. Calculate totals using product_price alias
$subtotal        = array_sum(array_map(fn($i) => floatval($i['product_price']) * intval($i['quantity']), $cart_items));
$discount_amount = round($subtotal * $discount_rate, 2);
$final_total     = round($subtotal - $discount_amount, 2);

// 3. Insert order with all totals stored
$branch = $data['branch'] ?? '';
$stmt = $pdo->prepare("
  INSERT INTO orders 
    (user_id, name, phone, email, address, payment_method, branch,
     discount_type, discount_rate, original_total, discount_amount, total)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([
  $user_id, $name, $phone, $email, $address, $payment_method, $branch,
  $discount_type, $discount_rate, $subtotal, $discount_amount, $final_total
]);
$order_id = $pdo->lastInsertId();

// 3b. Handle GCash proof upload
$gcash_proof_path = null;
if ($payment_method === 'gcash' && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] === 0) {
  $upload_dir = 'uploads/gcash/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
  $ext              = pathinfo($_FILES['gcash_proof']['name'], PATHINFO_EXTENSION);
  $gcash_proof_path = $upload_dir . $order_id . '_' . time() . '.' . $ext;
  move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $gcash_proof_path);

  $stmt = $pdo->prepare("UPDATE orders SET gcash_proof = ? WHERE id = ?");
  $stmt->execute([$gcash_proof_path, $order_id]);
}

// 4. Insert order items using product_price alias
$stmt = $pdo->prepare("
  INSERT INTO order_items (order_id, product_id, quantity, option_selected, sauce, extra_flavor, mix_preference, price)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
foreach ($cart_items as $item) {
  $stmt->execute([
    $order_id,
    $item['product_id'],
    $item['quantity'],
    $item['option_selected'],
    $item['sauce'],
    $item['extra_flavor'],
    $item['mix_preference'],
    $item['product_price'],   // ← use the explicit alias
  ]);
}

// 5. Clear cart
$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

echo json_encode(["success" => true, "order_id" => $order_id]);
?>