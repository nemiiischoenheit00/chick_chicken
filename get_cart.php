<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
  SELECT c.*, p.name, p.price, p.image
  FROM cart c
  JOIN products p ON c.product_id = p.id
  WHERE c.user_id = ?
");

$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($items);
?>