<?php
require_once __DIR__ . '/../utils/mail_helper.php'; // ✅ adjust path if needed

header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

$id = $_GET['id'] ?? 0;
if (!$id) {
    echo json_encode(["success" => false, "message" => "Invalid ID"]);
    exit;
}

// 1️⃣ Fetch task title for reference
$titleResult = $conn->query("SELECT title FROM tasks WHERE id = $id");
$titleRow = $titleResult && $titleResult->num_rows ? $titleResult->fetch_assoc() : null;
$taskTitle = $titleRow ? $titleRow['title'] : 'your assigned task';

// 2️⃣ Fetch all assigned recipients (before deleting)
$recipients = [];
$result = $conn->query("SELECT recipient_name FROM task_recipients WHERE task_id = $id");
while ($row = $result->fetch_assoc()) {
    $name = trim(explode('(', $row['recipient_name'])[0]);
    $recipients[] = $name;
}

// 3️⃣ Send mail to each recipient
foreach ($recipients as $username) {
    $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $user = $res->fetch_assoc()) {
        $email = $user['email'];
        $subject = "Your Task Deleted ";
        $body = "
            Hello <strong>$username</strong>,<br><br>
            Please be informed that your assigned task <strong>\"$taskTitle\"</strong> has been permanently deleted by the Principal.<br><br>
            If you have any questions, please contact the administration.<br><br>
            Regards,<br>TaskFlow System
        ";
        sendMail($email, $subject, $body);
    }
}

// 4️⃣ Delete related data
$conn->query("DELETE FROM task_attachments WHERE task_id = $id");
$conn->query("DELETE FROM task_recipients WHERE task_id = $id");
$conn->query("DELETE FROM tasks WHERE id = $id");

echo json_encode(["success" => true, "message" => "Task deleted permanently"]);
?>
