<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
  echo json_encode(["success" => false, "message" => "DB connection failed"]);
  exit;
}

$sql = "SELECT DISTINCT LOWER(role) AS role FROM users WHERE role IS NOT NULL AND role != ''";
$result = $conn->query($sql);

$roles = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $role = strtolower(trim($row['role']));
    if (!in_array($role, $roles)) {
      $roles[] = $role;
    }
  }
  echo json_encode(["success" => true, "roles" => $roles]);
} else {
  echo json_encode(["success" => false, "message" => "Query error"]);
}
?>
