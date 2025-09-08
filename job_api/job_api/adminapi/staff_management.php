<?php
// File: adminapi/staff_management.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Get optional search keyword
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

// Base SQL
$sql = "SELECT username, email, role, active_mode FROM users";

// Append WHERE clause if searching
if ($search !== "") {
    $sql .= " WHERE username LIKE '%$search%'";
}

$result = $conn->query($sql);

$users = [];

while ($row = $result->fetch_assoc()) {
    $username = $conn->real_escape_string($row['username']);
    $role = $row['role'];
    $department = '---';

    if ($role === 'staff' || $role === 'hod') {
        $deptQuery = "SELECT department FROM faculties WHERE name = '$username' LIMIT 1";
        $deptResult = $conn->query($deptQuery);
        if ($deptResult && $deptRow = $deptResult->fetch_assoc()) {
            $department = $deptRow['department'];
        }
    }

    $users[] = [
        'username' => $row['username'],
        'email' => $row['email'],
        'role' => $role,
        'department' => $department,
        'status' => $row['active_mode'] == 0 ? 'Active' : 'Inactive'
    ];
}

echo json_encode($users);
?>
