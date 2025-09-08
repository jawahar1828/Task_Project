<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "DB connection failed"]);
  exit;
}

$id = $_GET['id'] ?? 0;
if (!$id) {
  echo json_encode(["success" => false, "message" => "Invalid ID"]);
  exit;
}

$conn->query("UPDATE tasks SET soft_deleted = 1 WHERE id = $id");

echo json_encode(["success" => true, "message" => "Task hidden from dashboard"]);
?>
