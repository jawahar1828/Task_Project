<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

function customEncrypt($password) {
    return "#123!" . md5($password) . "@#$%)(jjdxbh";
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data || !isset($data['username'], $data['password'], $data['role'])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

$email = $conn->real_escape_string($data['username']);
$password = customEncrypt($data['password']);
$role     = $conn->real_escape_string($data['role']);

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='$role' AND active_mode = 0";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];

    $recipient_name = "";

    if ($role === "staff") {
        $deptSql = "SELECT department FROM faculties WHERE name = '$username'";
        $deptResult = $conn->query($deptSql);
        if ($deptResult && $deptResult->num_rows > 0) {
            $dept = $deptResult->fetch_assoc()['department'];
            $recipient_name = "$username ($dept)";
        }
    } elseif ($role === "dean") {
        $recipient_name = "$username (Dean)";
    }

    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['id'],
            "name" => $username,
            "email" => $user['email'],
            "role" => $user['role'],
            "recipient_name" => $recipient_name
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

$conn->close();
