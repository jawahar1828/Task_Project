<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

// Inputs
$task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
$title = $conn->real_escape_string($_POST['title'] ?? '');
$description = $conn->real_escape_string($_POST['description'] ?? '');
$priority = $conn->real_escape_string($_POST['priority'] ?? 'Medium');
$due_date = $conn->real_escape_string($_POST['due_date'] ?? '');
$recipients_raw = $_POST['recipients'] ?? '';

if (!$task_id || !$title || !$description || !$due_date || !$recipients_raw) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

// Convert recipients string to array
$recipients = array_filter(array_map('trim', explode(',', $recipients_raw)));

// 🟡 1. Update Task
$update_sql = "UPDATE tasks SET title='$title', description='$description', priority='$priority', due_date='$due_date' WHERE id=$task_id";
if (!$conn->query($update_sql)) {
    echo json_encode(["success" => false, "error" => "Task update failed: " . $conn->error]);
    exit;
}

// 🟡 2. Replace Recipients
$conn->query("DELETE FROM task_recipients WHERE task_id = $task_id");

foreach ($recipients as $name) {
    $type = 'Faculty';
    if (stripos($name, '(Dean)') !== false) $type = 'Dean';
    else if (stripos($name, 'Department') !== false || stripos($name, 'HOD') !== false) $type = 'HOD';

    $cleaned_name = $conn->real_escape_string($name);
    $conn->query("INSERT INTO task_recipients (task_id, recipient_name, recipient_type, status, created_at)
                  VALUES ($task_id, '$cleaned_name', '$type', 'Pending', NOW())");
}

// 🟡 3. Replace Attachments (if uploaded)
$upload_dir = "C:/xampp/htdocs/job_api/job_api/uploads/tasks/";
$relative_base = "job_api/uploads/tasks/";

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (!empty($_FILES['attachments']['name'][0])) {
    // Delete previous attachments
    $conn->query("DELETE FROM task_attachments WHERE task_id = $task_id");

    foreach ($_FILES['attachments']['tmp_name'] as $index => $tmpName) {
        $originalName = basename($_FILES['attachments']['name'][$index]);
        $fileName     = time() . "_" . $originalName;
        $targetPath   = $upload_dir . $fileName;
        $relativePath = $relative_base . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $stmt = $conn->prepare("INSERT INTO task_attachments (task_id, file_path, uploaded_by, uploaded_at)
                                    VALUES (?, ?, 'principal', NOW())");
            $stmt->bind_param("is", $task_id, $relativePath);
            $stmt->execute();
        } else {
            echo json_encode(["success" => false, "error" => "Failed to upload file: $originalName"]);
            exit;
        }
    }
}

echo json_encode(["success" => true, "message" => "Task updated successfully"]);
?>
