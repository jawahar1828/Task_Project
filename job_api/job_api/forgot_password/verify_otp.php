<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");

$data = json_decode(file_get_contents("php://input"), true);
$email = $conn->real_escape_string($data['email']);
$otp   = $conn->real_escape_string($data['otp']);

$res = $conn->query("SELECT * FROM otp_verifications WHERE email='$email' AND otp='$otp'");

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $expires_at = strtotime($row['expires_at']);

    if (time() <= $expires_at) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "OTP expired"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid OTP"]);
}
