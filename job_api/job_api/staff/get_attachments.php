<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$taskId = $_GET['task_id'] ?? null;

if (!$taskId) {
    echo json_encode(["error" => "Missing task_id"]);
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=job_allocation", "root", "");
    $stmt = $conn->prepare("SELECT file_path FROM task_attachments WHERE task_id = ?");
    $stmt->execute([$taskId]);
    $attachments = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(["attachments" => $attachments]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
