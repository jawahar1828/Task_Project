<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Read input
$data = json_decode(file_get_contents("php://input"), true);
$department = $data['department'] ?? null;

if (!$department) {
    echo json_encode(["status" => "error", "message" => "Department name is required"]);
    exit;
}

// Delete
$stmt = $conn->prepare("DELETE FROM departments WHERE name = ?");
$stmt->bind_param("s", $department);
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete department"]);
}
$stmt->close();
$conn->close();
?>
