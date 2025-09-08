<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
  echo json_encode(["status" => "error", "message" => "DB connection failed"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$username = $conn->real_escape_string($data['username']);
$email = $conn->real_escape_string($data['email']);
$role = $conn->real_escape_string($data['role']);
$password = isset($data['password']) ? $data['password'] : '';
$department = isset($data['department']) ? $conn->real_escape_string($data['department']) : '';

$sql = "UPDATE users SET email = '$email', role = '$role'";
if (!empty($password)) {
 $hashed = "#123!" . md5($password) . "@#$%)(jjdxbh";
$sql .= ", password = '$hashed'";

}
$sql .= " WHERE username = '$username'";

if (!$conn->query($sql)) {
  echo json_encode(["status" => "error", "message" => "Failed to update users table"]);
  exit;
}

// Update department table
if ($role === "staff") {
  $conn->query("UPDATE faculties SET department = '$department' WHERE name = '$username'");
} elseif ($role === "dean") {
  $conn->query("UPDATE deans SET username = '$username' WHERE username = '$username'");
}

echo json_encode(["status" => "success"]);
?>
