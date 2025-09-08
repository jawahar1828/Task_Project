<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$status    = $_GET['status'] ?? '';
$from      = $_GET['from'] ?? '';
$to        = $_GET['to'] ?? '';
$recipient = $_GET['recipient'] ?? '';

$where = [];

// Filters
if (!empty($status)) {
    $status = $conn->real_escape_string($status);
    $where[] = "tr.status = '$status'";
}

if (!empty($from)) {
    $from = $conn->real_escape_string($from);
    $where[] = "DATE(tr.created_at) >= '$from'";
}

if (!empty($to)) {
    $to = $conn->real_escape_string($to);
    $where[] = "DATE(tr.created_at) <= '$to'";
}

if (!empty($recipient)) {
    $recipient = $conn->real_escape_string($recipient);
    $where[] = "tr.recipient_name LIKE '%$recipient%'";
}

// Combine all conditions
$where_sql = "";
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$query = "
SELECT 
  t.title, 
  tr.recipient_name,
  t.priority,
  t.due_date,
  tr.status,
  tr.created_at AS assigned_at,
  ts.submitted_at
FROM task_recipients tr
JOIN tasks t ON t.id = tr.task_id
LEFT JOIN task_submissions ts ON ts.task_id = tr.task_id AND ts.recipient_id = tr.id
$where_sql
ORDER BY tr.created_at DESC
";

$result = $conn->query($query);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'Query failed']);
}

$conn->close();
?>
