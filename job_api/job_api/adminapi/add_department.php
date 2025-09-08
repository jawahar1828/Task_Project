<?php
// File: adminapi/add_department.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name']) || trim($data['name']) === "") {
    echo json_encode(["status" => "error", "message" => "Department name is required"]);
    exit;
}

$name = trim($data['name']);

// Check if department already exists
$stmt = $conn->prepare("SELECT id FROM departments WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Department already exists"]);
    exit;
}

$stmt->close();

// Insert into table
$stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Department added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add department"]);
}

$stmt->close();
$conn->close();
?>
