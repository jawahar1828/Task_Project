<?php
require_once __DIR__ . '/../utils/mail_helper.php'; // Reuse same mail helper

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_POST['recipient_id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Missing recipient_id"]);
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=job_allocation", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update status
    $stmt = $conn->prepare("UPDATE task_recipients SET status = 'In Progress' WHERE id = ?");
    $stmt->execute([$id]);

    // Fetch task and staff info
    $query = "
        SELECT tr.recipient_name, t.title 
        FROM task_recipients tr
        JOIN tasks t ON t.id = tr.task_id
        WHERE tr.id = ?
    ";
    $stmt2 = $conn->prepare($query);
    $stmt2->execute([$id]);
    $data = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $staff = trim(explode("(", $data['recipient_name'])[0]);
        $taskTitle = $data['title'];

        // Get principal email
        $principalQuery = $conn->prepare("SELECT email FROM users WHERE role = 'principal' LIMIT 1");
        $principalQuery->execute();
        $principal = $principalQuery->fetch(PDO::FETCH_ASSOC);

        if ($principal) {
            $to = $principal['email'];
            $subject = "✅ Task Accepted by $staff";
            $body = "
                Hello Principal,<br><br>
                <strong>$staff</strong> has accepted the task:<br>
                <strong>Title:</strong> $taskTitle<br><br>
                Please log in to TaskFlow to view full details.<br><br>
                Regards,<br>TaskFlow System
            ";
            sendMail($to, $subject, $body);
        }
    }

    echo json_encode(["success" => true]);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
