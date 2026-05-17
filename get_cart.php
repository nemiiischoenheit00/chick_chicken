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
  SELECT
    c.*,
    p.name,
    p.image,
    (
      p.price
      + IF(c.option_selected LIKE '%Double%', 100, 0)
      + IF(c.extra_flavor IS NOT NULL AND c.extra_flavor != '', 20, 0)
    ) AS price
  FROM cart c
  JOIN products p ON c.product_id = p.id
  WHERE c.user_id = ?
");

$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($items);
?>