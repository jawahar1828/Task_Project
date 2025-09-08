<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$username = $conn->real_escape_string($data['username']);

$conn->query("DELETE FROM faculties WHERE username = '$username'");
$conn->query("DELETE FROM deans WHERE  name = '$username'");
$conn->query("DELETE FROM principal WHERE username = '$username'");
$conn->query("DELETE FROM users WHERE username = '$username'");

echo json_encode(["status" => "success"]);
?>
