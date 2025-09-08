<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT name FROM departments";
$result = $conn->query($sql);

$departments = [];
while($row = $result->fetch_assoc()) {
    $departments[] = $row['name'];
}

echo json_encode($departments);
?>
