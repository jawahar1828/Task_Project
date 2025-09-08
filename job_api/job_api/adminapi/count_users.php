<?php
// File: htdocs/job-api/adminapi/count_users.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT COUNT(*) AS total FROM users";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(["total" => $row['total']]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to count users"]);
}
?>
