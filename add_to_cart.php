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
  echo json_encode(["error" => "No JSON received"]);
  exit;
}

$user_id    = $_SESSION['user_id'];
$product_id = intval($data['product_id']);
$quantity   = intval($data['quantity']) ?: 1;
$option     = $data['option'] ?? '';
$sauce      = $data['sauce'] ?? '';
$extra      = $data['extra'] ?? '';
$mix        = $data['mix'] ?? '';

$stmt = $pdo->prepare("
  INSERT INTO cart 
  (user_id, product_id, quantity, option_selected, sauce, extra_flavor, mix_preference)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

if ($stmt->execute([$user_id, $product_id, $quantity, $option, $sauce, $extra, $mix])) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "Insert failed"]);
}
?>