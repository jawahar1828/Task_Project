<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../utils/mail_helper.php';  // Path to your mail helper

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "job_allocation";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  echo json_encode(['success' => false, 'message' => 'Connection failed']);
  exit;
}

$task_id      = $_POST['task_id'];
$recipient_id = $_POST['recipient_id'];
$status       = $_POST['status'];
$reason       = $_POST['reason'] ?? '';

$update_status = "UPDATE task_recipients SET status = '$status' WHERE id = $recipient_id AND task_id = $task_id";

if ($conn->query($update_status)) {

  // ✅ If Reassign, store reason as comment in tasks table
  if ($status === "Pending") {
    $safe_reason = $conn->real_escape_string($reason);
    $conn->query("UPDATE tasks SET comment = '$safe_reason' WHERE id = $task_id");
  }

  // 🧠 Send mail to staff
  // 1️⃣ Get task title
  $taskResult = $conn->query("SELECT title FROM tasks WHERE id = $task_id");
  $taskRow    = $taskResult->fetch_assoc();
  $task_title = $taskRow['title'] ?? 'Untitled Task';

  // 2️⃣ Get recipient name
  $recipientResult = $conn->query("SELECT recipient_name FROM task_recipients WHERE id = $recipient_id");
  $recipientRow    = $recipientResult->fetch_assoc();
  $recipient_name  = $recipientRow['recipient_name'] ?? '';

  // Clean name (remove "(HOD)" or "(Dean)" if present)
  $pureName = trim(explode("(", $recipient_name)[0]);

  // 3️⃣ Get staff email
  $emailStmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
  $emailStmt->bind_param("s", $pureName);
  $emailStmt->execute();
  $emailResult = $emailStmt->get_result();

  if ($emailResult && $emailRow = $emailResult->fetch_assoc()) {
    $staff_email = $emailRow['email'];

    // 📧 Prepare subject/body
    if ($status === 'Completed') {
  $cleaned = htmlspecialchars($reason, ENT_QUOTES);
  $subject = "Your Task Was Approved";
  $body    = "
    Hello <strong>$pureName</strong>,<br><br>
    Your task <strong>'$task_title'</strong> has been <strong>verified</strong> and marked as <strong>completed</strong> by the Principal.<br><br>";

  if (!empty($reason)) {
    $body .= "<strong>Principal's Note:</strong><br><em>\"$cleaned\"</em><br><br>";
  }

  $body .= "Thank you for your submission.<br><br>Regards,<br>TaskFlow System";
}
 elseif ($status === 'Pending') {
      $subject = "Task Resubmitted - Action Required";
      $body    = "
        Hello <strong>$pureName</strong>,<br><br>
        Your submitted task <strong>'$task_title'</strong> has been <strong>resubmitted</strong> with the following reason:<br><br>
        <em>\"$reason\"</em><br><br>
        Please review and resubmit accordingly.<br><br>
        Regards,<br>TaskFlow System
      ";
    }

    // 📤 Send the email
    if (!sendMail($staff_email, $subject, $body)) {
      error_log("❌ Failed to send email to $staff_email");
    }
  } else {
    error_log("❗ No email found for user: $pureName");
  }

  echo json_encode(['success' => true, 'message' => "Status updated to $status"]);
} else {
  echo json_encode(['success' => false, 'message' => "Failed to update status"]);
}

$conn->close();
?>
