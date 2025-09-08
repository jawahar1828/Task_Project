<?php
header("Content-Type: application/json");

function customEncrypt($password) {
    return "#123!" . md5($password) . "@#$%)(jjdxbh";
}

$conn = new mysqli("localhost", "root", "", "job_allocation");

$data = json_decode(file_get_contents("php://input"), true);
$email = $conn->real_escape_string($data['email']);
$password = $conn->real_escape_string(customEncrypt($data['password']));

$res = $conn->query("SELECT * FROM users WHERE email = '$email'");
if ($res && $res->num_rows > 0) {
    $conn->query("UPDATE users SET password = '$password' WHERE email = '$email'");
    $conn->query("DELETE FROM otp_verifications WHERE email = '$email'");
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Email not found."]);
}
