<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

if (!isset($_GET['email'])) {
    echo json_encode(["success" => false, "message" => "Missing email"]);
    exit;
}

$email = $conn->real_escape_string($_GET['email']);

$sql = "SELECT username, email, role FROM users WHERE email = '$email' AND active_mode = 0 LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo json_encode(["success" => true, "user" => $result->fetch_assoc()]);
} else {
    echo json_encode(["success" => false, "message" => "User not found or inactive"]);
}

$conn->close();
?>
