<?php
header('Content-Type: application/json');

// ✅ Database connection (no include)
$servername = "localhost";
$username = "root";
$password = ""; // change if needed
$database = "job_allocation"; // ← update this if your DB name is different

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
  exit;
}

// ✅ Validate input
if (!isset($_GET['id'])) {
  echo json_encode(['error' => 'Task ID not provided']);
  exit;
}

$task_id = (int)$_GET['id'];
$response = [];

// ✅ Fetch task details
$task_sql = "SELECT * FROM tasks WHERE id = $task_id";
$task_result = $conn->query($task_sql);
if (!$task_result || $task_result->num_rows === 0) {
  echo json_encode(['error' => 'Task not found']);
  exit;
}
$task = $task_result->fetch_assoc();
$response = $task;

// ✅ Fetch principal attachments
$attachments = [];
$att_sql = "SELECT file_path FROM task_attachments WHERE task_id = $task_id";
$att_result = $conn->query($att_sql);
while ($row = $att_result->fetch_assoc()) {
  $attachments[] = $row['file_path'];
}
$response['attachments'] = $attachments;

// ✅ Fetch recipients and their submissions
$recipients = [];
$rec_sql = "SELECT id, recipient_name, recipient_type, status FROM task_recipients WHERE task_id = $task_id";
$rec_result = $conn->query($rec_sql);

while ($row = $rec_result->fetch_assoc()) {
  $recipient_id = $row['id'];

  $submission_sql = "SELECT description, file_path AS attachments FROM task_submissions 
                     WHERE task_id = $task_id AND recipient_id = $recipient_id 
                     ORDER BY submitted_at DESC LIMIT 1";
  $submission_result = $conn->query($submission_sql);

  if ($submission_result && $submission_result->num_rows > 0) {
  $submission = $submission_result->fetch_assoc();
  $submission['files'] = array_filter(array_map('trim', explode(',', $submission['attachments'])));
  unset($submission['attachments']); // optional: keep only 'files' key
  $row['submission'] = $submission;
}
 else {
    $row['submission'] = null;
  }

  $recipients[] = $row;
}
$response['recipients'] = $recipients;

// ✅ Output final response
echo json_encode($response);
$conn->close();
?>
