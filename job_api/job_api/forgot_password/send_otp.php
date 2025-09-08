<?php
header("Content-Type: application/json");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../vendor/autoload.php";

$conn = new mysqli("localhost", "root", "", "job_allocation");

$data = json_decode(file_get_contents("php://input"), true);
$email = $conn->real_escape_string($data['email']);

$res = $conn->query("SELECT * FROM users WHERE email = '$email' AND active_mode = 0");

if ($res && $res->num_rows > 0) {
    $otp = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", time() + 300); // 5 mins

    $conn->query("DELETE FROM otp_verifications WHERE email = '$email'");
    $conn->query("INSERT INTO otp_verifications (email, otp, expires_at) VALUES ('$email', '$otp', '$expires_at')");

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bbhavan3007@gmail.com';
        $mail->Password = 'ksjl bwht mymg hdgb';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('bbhavan3007@gmail.com', 'TaskFlow');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "TaskFlow Password Reset OTP";
        $mail->Body    = "Your OTP to reset password is: <strong>$otp</strong>. It will expire in 5 minutes.";

        $mail->send();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Failed to send OTP."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Email not found."]);
}
