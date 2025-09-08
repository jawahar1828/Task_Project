<?php
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
  die(json_encode(["success" => false, "message" => "DB connection failed"]));
}

$result = $conn->query("SELECT id, name FROM groups"); // Adjust table name if needed
$groups = [];
while ($row = $result->fetch_assoc()) {
  $groups[] = $row;
}

echo json_encode(["success" => true, "groups" => $groups]);
?>
