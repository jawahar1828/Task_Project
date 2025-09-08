<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$taskId      = $_GET['task_id'] ?? '';
$recipientId = $_GET['recipient_id'] ?? '';

if (!$taskId || !$recipientId) {
    echo json_encode(["error" => "Missing task_id or recipient_id"]);
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=job_allocation", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT description, file_path AS attachments FROM task_submissions WHERE task_id = ? AND recipient_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$taskId, $recipientId]);
    $submission = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($submission) {
        echo json_encode($submission);
    } else {
        echo json_encode(["error" => "No submission found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
