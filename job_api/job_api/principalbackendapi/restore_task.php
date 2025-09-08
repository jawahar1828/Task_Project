<?php
header("Content-Type: application/json");
include 'db.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
  echo json_encode(["success" => false, "message" => "Invalid task ID"]);
  exit;
}

$query = "UPDATE tasks SET soft_deleted = 0 WHERE id = $id";
if (mysqli_query($conn, $query)) {
  echo json_encode(["success" => true, "message" => "Task restored to dashboard"]);
} else {
  echo json_encode(["success" => false, "message" => "Failed to restore task"]);
}
