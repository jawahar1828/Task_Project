<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$response = [
    "active" => 0,
    "inactive" => 0,
    "total" => 0
];

// Count Active Users
$activeQuery = $conn->query("SELECT COUNT(*) AS count FROM users WHERE active_mode = 0");
if ($activeRow = $activeQuery->fetch_assoc()) {
    $response["active"] = (int) $activeRow["count"];
}

// Count Inactive Users
$inactiveQuery = $conn->query("SELECT COUNT(*) AS count FROM users WHERE active_mode = 1");
if ($inactiveRow = $inactiveQuery->fetch_assoc()) {
    $response["inactive"] = (int) $inactiveRow["count"];
}

$response["total"] = $response["active"] + $response["inactive"];

echo json_encode($response);
?>
