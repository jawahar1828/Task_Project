<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

function customEncrypt($password) {
    return "#123!" . md5($password) . "@#$%)(jjdxbh";
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username'], $data['email'], $data['password'], $data['role'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$username = $data['username'];
$email = $data['email'];
$password = customEncrypt($data['password']);
$role = $data['role'];
$department = isset($data['department']) ? $data['department'] : null;

// Insert into users table
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt->bind_param("ssss", $username, $email, $password, $role);
if (!$stmt->execute()) {
    echo json_encode(["error" => "User insert failed: " . $stmt->error]);
    exit;
}

// Insert into role-specific tables
if ($role === 'dean') {
    $stmt = $conn->prepare("INSERT INTO deans (name) VALUES (?)");
    if (!$stmt) {
        echo json_encode(["error" => "Dean prepare failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Dean insert failed: " . $stmt->error]);
        exit;
    }
} elseif ($role === 'hod' || $role === 'staff') {
    $stmt = $conn->prepare("INSERT INTO faculties (name, department) VALUES (?, ?)");
    if (!$stmt) {
        echo json_encode(["error" => "Faculty prepare failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("ss", $username, $department);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Faculty insert failed: " . $stmt->error]);
        exit;
    }
}

echo json_encode(["status" => "success"]);
?>
