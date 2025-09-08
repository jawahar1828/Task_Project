<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit;
}

if (!isset($_GET['task_id'])) {
    echo json_encode(["error" => "No task ID provided"]);
    exit;
}

$task_id = $_GET['task_id'];

$taskRes = $conn->query("SELECT * FROM tasks WHERE id = $task_id");
if ($taskRes->num_rows == 0) {
    echo json_encode(["error" => "Task not found"]);
    exit;
}
$task = $taskRes->fetch_assoc();

$recipientsRes = $conn->query("SELECT recipient_name, recipient_type, status FROM task_recipients WHERE task_id = $task_id");
$recipients = [];
while ($row = $recipientsRes->fetch_assoc()) {
    $recipients[] = $row;
}

$attachmentsRes = $conn->query("SELECT file_path FROM task_attachments WHERE task_id = $task_id");
$attachments = [];
while ($row = $attachmentsRes->fetch_assoc()) {
    $attachments[] = $row['file_path'];
}

echo json_encode([
    "title" => $task['title'],
    "description" => $task['description'],
    "priority" => $task['priority'],
    "due_date" => $task['due_date'],
    "recipients" => $recipients,
    "attachments" => $attachments
]);
