<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "not_logged_in"]);
  exit;
}

$data    = json_decode(file_get_contents("php://input"), true);
$cart_id = intval($data['cart_id']);
$user_id = $_SESSION['user_id'];

// Only delete if it belongs to this user
$sql = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
$conn->query($sql);
echo json_encode(["success" => true]);
?>