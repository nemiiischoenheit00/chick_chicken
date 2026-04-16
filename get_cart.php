<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.*, p.name, p.price, p.image
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id";

$result = $conn->query($sql);
$items = [];

while ($row = $result->fetch_assoc()) {
  $items[] = $row;
}

echo json_encode($items);
?>