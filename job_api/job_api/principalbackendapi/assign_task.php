<?php
require_once __DIR__ . '/../utils/mail_helper.php';  // Make sure this path is correct

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "job_allocation";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$due_date    = $_POST['due_date'] ?? '';
$priority    = $_POST['priority'] ?? 'Medium';
$recipients  = isset($_POST['recipients']) ? json_decode($_POST['recipients'], true) : [];

$errors = [];

if (!$title || !$description || !$due_date || empty($recipients)) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

// 1️⃣ Insert Task
$stmt = $conn->prepare("INSERT INTO tasks (title, description, priority, due_date, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $title, $description, $priority, $due_date);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to insert task"]);
    exit;
}
$task_id = $stmt->insert_id;

// 2️⃣ Upload Files
$upload_dir = "C:/xampp/htdocs/job_api/job_api/uploads/tasks/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
if (!empty($_FILES['attachments']['name'][0])) {
    foreach ($_FILES['attachments']['tmp_name'] as $index => $tmpName) {
        $originalName = basename($_FILES['attachments']['name'][$index]);
        $fileName     = time() . "_" . $originalName;
        $targetPath   = $upload_dir . $fileName;
        $relativePath = "job_api/uploads/tasks/" . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $stmt2 = $conn->prepare("INSERT INTO task_attachments (task_id, file_path, uploaded_by, uploaded_at) VALUES (?, ?, 'principal', NOW())");
            $stmt2->bind_param("is", $task_id, $relativePath);
            $stmt2->execute();
        } else {
            $errors[] = "Failed to upload: " . $originalName;
        }
    }
}

// 3️⃣ Insert Recipients + Email
foreach ($recipients as $rName) {
    $recipient_type = 'Faculty';
    if (stripos($rName, 'Dean') !== false) {
        $recipient_type = 'Dean';
    } elseif (stripos($rName, 'HOD') !== false || stripos($rName, 'Department') !== false) {
        $recipient_type = 'HOD';
    }

    $stmt3 = $conn->prepare("INSERT INTO task_recipients (task_id, recipient_name, recipient_type, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt3->bind_param("iss", $task_id, $rName, $recipient_type);
    $stmt3->execute();

    // ✉ Send email
    $pureName = trim(explode("(", $rName)[0]);

    $emailStmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
    $emailStmt->bind_param("s", $pureName);
    $emailStmt->execute();
    $res = $emailStmt->get_result();

    if ($res && $row = $res->fetch_assoc()) {
        $email = $row['email'];
        $subject = " New Task Assigned: $title";
        $body = "
            Hello <strong>$pureName</strong>,<br><br>
            You have been assigned a new task:<br>
            <strong>Title:</strong> $title<br>
            <strong>Due Date:</strong> $due_date<br><br>
            Please log in to the TaskFlow app to view full details.<br><br>
            Regards,<br>TaskFlow System
        ";

        if (!sendMail($email, $subject, $body)) {
            error_log("❌ Failed to send mail to $email");
        }
    } else {
        error_log("❗ Email not found for username: $pureName");
    }
}

echo json_encode([
    "success" => true,
    "message" => "Task assigned successfully",
    "task_id" => $task_id,
    "upload_errors" => $errors
]);

$conn->close();
?>
