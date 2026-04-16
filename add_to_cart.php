<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

// must be logged in
if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "not_logged_in"]);
  exit;
}

// read JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(["error" => "No JSON received"]);
  exit;
}

// sanitize
$user_id    = $_SESSION['user_id'];
$product_id = intval($data['product_id']);
$quantity   = intval($data['quantity']) ?: 1;
$option     = $data['option'] ?? '';
$sauce      = $data['sauce'] ?? '';
$extra      = $data['extra'] ?? '';
$mix        = $data['mix'] ?? '';

// use prepared statement (WAY safer + avoids breaking SQL)
$stmt = $conn->prepare("
  INSERT INTO cart 
  (user_id, product_id, quantity, option_selected, sauce, extra_flavor, mix_preference)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
  echo json_encode(["error" => $conn->error]);
  exit;
}

$stmt->bind_param("iiissss", $user_id, $product_id, $quantity, $option, $sauce, $extra, $mix);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => $stmt->error]);
}