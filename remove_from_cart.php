<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "not_logged_in"]);
  exit;
}

$data    = json_decode(file_get_contents("php://input"), true);
$cart_id = intval($data['cart_id']);
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([$cart_id, $user_id]);

echo json_encode(["success" => true]);
?>