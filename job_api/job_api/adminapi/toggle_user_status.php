<?php
// File: toggle_user_status.php

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

$username = $conn->real_escape_string($data['username'] ?? '');
if (!$username) {
    echo json_encode(["status" => "error", "message" => "Username is required"]);
    exit;
}

// Fetch current status and role
$get = $conn->query("SELECT active_mode, role FROM users WHERE username = '$username' LIMIT 1");
if (!$get || $get->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

$user = $get->fetch_assoc();
$newStatus = $user['active_mode'] == 0 ? 1 : 0;
$role = $user['role'];

// Update in `users` table
$conn->query("UPDATE users SET active_mode = $newStatus WHERE username = '$username'");

// If staff or hod → update faculties table
if ($role === 'staff' || $role === 'hod') {
    $conn->query("UPDATE faculties SET active_mode = $newStatus WHERE name = '$username'");
}

// If dean → update deans table
if ($role === 'dean') {
    $conn->query("UPDATE deans SET active_mode = $newStatus WHERE name = '$username'");
}

echo json_encode(["status" => "success", "newStatus" => $newStatus == 0 ? "Active" : "Inactive"]);
$conn->close();
?>
