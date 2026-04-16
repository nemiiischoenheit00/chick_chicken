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
$name           = $conn->real_escape_string($data['name']);
$phone          = $conn->real_escape_string($data['phone']);
$email          = $conn->real_escape_string($data['email']);
$address        = $conn->real_escape_string($data['address']);
$payment_method = $data['payment_method'] === 'online' ? 'online' : 'cod';
$card_number    = $conn->real_escape_string($data['card_number'] ?? '');

// 1. Fetch user's cart
$cart_sql = "SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id";
$cart_result = $conn->query($cart_sql);
$cart_items = [];
while ($row = $cart_result->fetch_assoc()) {
  $cart_items[] = $row;
}

if (empty($cart_items)) {
  echo json_encode(["error" => "Cart is empty"]);
  exit;
}

// 2. Insert order
$order_sql = "INSERT INTO orders (user_id, name, phone, email, address, payment_method, card_number)
              VALUES ($user_id, '$name', '$phone', '$email', '$address', '$payment_method', '$card_number')";

if (!$conn->query($order_sql)) {
  echo json_encode(["error" => $conn->error]);
  exit;
}

$order_id = $conn->insert_id;

// 3. Insert order items
$stmt = $conn->prepare("
  INSERT INTO order_items (order_id, product_id, quantity, option_selected, sauce, extra_flavor, mix_preference, price)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

foreach ($cart_items as $item) {
  $stmt->bind_param(
    "iiissssd",
    $order_id,
    $item['product_id'],
    $item['quantity'],
    $item['option_selected'],
    $item['sauce'],
    $item['extra_flavor'],
    $item['mix_preference'],
    $item['price']
  );
  $stmt->execute();
}

// 4. Clear the cart
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

echo json_encode(["success" => true, "order_id" => $order_id]);
?>