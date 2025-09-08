<?php
header('Content-Type: application/json');
include 'db.php';

$task_id = $_GET['id'] ?? 0;

$response = [];

// Get task main info
$task_sql = "SELECT * FROM tasks WHERE id = $task_id";
$task_res = mysqli_query($conn, $task_sql);
$task = mysqli_fetch_assoc($task_res);

$response['title'] = $task['title'];
$response['description'] = $task['description'];
$response['due_date'] = $task['due_date'];
$response['priority'] = $task['priority'];

// Get recipient info (type: dean/department → faculty)
$recipient_sql = "SELECT * FROM task_recipients WHERE task_id = $task_id";
$rec_res = mysqli_query($conn, $recipient_sql);

$recipients = [];

while ($row = mysqli_fetch_assoc($rec_res)) {
    $recipients[] = [
        'recipient_type' => $row['recipient_type'], // dean, department, faculty
        'recipient_id' => $row['recipient_id'],
        'recipient_name' => $row['recipient_name']
    ];
}

$response['recipients'] = $recipients;

echo json_encode($response);
?>
