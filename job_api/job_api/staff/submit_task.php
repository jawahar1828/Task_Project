<?php
require_once __DIR__ . '/../utils/mail_helper.php'; // Include mail helper

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$title        = $_POST['title'] ?? '';
$description  = $_POST['description'] ?? '';
$taskId       = $_POST['task_id'] ?? '';
$recipientId  = $_POST['recipient_id'] ?? '';

if (!$title || !$description || !$taskId || !$recipientId || empty($_FILES['file'])) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=job_allocation", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $uploadDir = "C:/xampp/htdocs/job_api/job_api/uploads/tasks/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedPaths = [];
    foreach ($_FILES['file']['tmp_name'] as $key => $tmpName) {
        $originalName = basename($_FILES['file']['name'][$key]);
        $fileName     = time() . "_" . $originalName;
        $targetPath   = $uploadDir . $fileName;
        $relativePath = "job_api/uploads/tasks/" . $fileName;


        if (move_uploaded_file($tmpName, $targetPath)) {
            $uploadedPaths[] = $relativePath;
        }
    }

    $allFiles = implode(',', $uploadedPaths);

    // Delete any previous submissions
    $conn->prepare("DELETE FROM task_submissions WHERE task_id = ? AND recipient_id = ?")
         ->execute([$taskId, $recipientId]);

    // Insert new submission
    $stmt = $conn->prepare("INSERT INTO task_submissions (task_id, recipient_id, description, file_path, submitted_by) VALUES (?, ?, ?, ?, 'staff')");
    $stmt->execute([$taskId, $recipientId, $description, $allFiles]);

    // Update task_recipients status
    $stmt3 = $conn->prepare("UPDATE task_recipients SET status = 'Waiting for Principal Action' WHERE id = ?");
    $stmt3->execute([$recipientId]);

    // Get task title and staff name
    $infoQuery = $conn->prepare("
        SELECT t.title, tr.recipient_name
        FROM task_recipients tr
        JOIN tasks t ON t.id = tr.task_id
        WHERE tr.id = ?
    ");
    $infoQuery->execute([$recipientId]);
    $data = $infoQuery->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $staff = trim(explode("(", $data['recipient_name'])[0]);
        $taskTitle = $data['title'];

        // Get principal email
        $principalQuery = $conn->prepare("SELECT email FROM users WHERE role = 'principal' LIMIT 1");
        $principalQuery->execute();
        $principal = $principalQuery->fetch(PDO::FETCH_ASSOC);

        if ($principal) {
            $to = $principal['email'];
            $subject = "📩 Task Submitted: $taskTitle";
            $body = "
                Hello Principal,<br><br>
                <strong>$staff</strong> has submitted the task:<br>
                <strong>Title:</strong> $taskTitle<br><br>
                Please log in to TaskFlow to review the submission.<br><br>
                Regards,<br>TaskFlow System
            ";
            sendMail($to, $subject, $body);
        }
    }

    echo json_encode(["success" => true]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
