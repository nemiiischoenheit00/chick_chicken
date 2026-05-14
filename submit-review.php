<?php
session_start();
require 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
  echo json_encode(["error" => "No data received"]);
  exit;
}

$name   = trim($data['name']);
$rating = intval($data['rating']);
$review = trim($data['review']);

if (!$name || $rating < 1 || $rating > 5 || !$review) {
  echo json_encode(["error" => "Invalid input"]);
  exit;
}

$stmt = $pdo->prepare("
  INSERT INTO reviews (name, rating, review_text, created_at)
  VALUES (?, ?, ?, NOW())
");

if ($stmt->execute([$name, $rating, $review])) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "Insert failed"]);
}
?>