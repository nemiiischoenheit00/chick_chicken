<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "not_logged_in"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
  echo json_encode(["error" => "No data received"]);
  exit;
}

$user_id        = $_SESSION['user_id'];
$name           = $data['name'];
$phone          = $data['phone'];
$email          = $data['email'];
$address        = $data['address'];
$payment_method = $data['payment_method'] === 'online' ? 'online' : 'cod';
$card_number    = $data['card_number'] ?? '';

// 1. Fetch cart
$stmt = $pdo->prepare("
  SELECT c.*, p.price FROM cart c 
  JOIN products p ON c.product_id = p.id 
  WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
  echo json_encode(["error" => "Cart is empty"]);
  exit;
}

// 2. Insert order
$stmt = $pdo->prepare("
  INSERT INTO orders (user_id, name, phone, email, address, payment_method, card_number)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$user_id, $name, $phone, $email, $address, $payment_method, $card_number]);
$order_id = $pdo->lastInsertId();

// 3. Insert order items
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
    $item['price']
  ]);
}

// 4. Clear cart
$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

echo json_encode(["success" => true, "order_id" => $order_id]);
?>