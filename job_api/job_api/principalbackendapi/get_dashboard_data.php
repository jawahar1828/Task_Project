<?php
header('Content-Type: application/json');
include 'db.php'; // 🔁 update with your DB credentials
$visibility = $_GET['visibility'] ?? 'active';
$softDeletedCondition = $visibility === 'deleted' ? 1 : 0;


$response = [];

// Get summary stats
$stats_query = "
  SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue
  FROM task_recipients
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$response['stats'] = [
  'total' => (int)($stats['total'] ?? 0),
  'completed' => (int)($stats['completed'] ?? 0),
  'pending' => (int)($stats['pending'] ?? 0),
  'overdue' => (int)($stats['overdue'] ?? 0),
];

// Get task details
$task_query = "
  SELECT 
    t.id AS id, 
    t.title,
    tr.recipient_name,
    t.due_date,
    t.priority,
    tr.status,
    tr.created_at
  FROM task_recipients tr
  JOIN tasks t ON t.id = tr.task_id
  WHERE t.soft_deleted = $softDeletedCondition
  ORDER BY tr.created_at DESC
";



$result = mysqli_query($conn, $task_query);
$tasks = [];

while ($row = mysqli_fetch_assoc($result)) {
  $tasks[] = $row;
}

$response['tasks'] = $tasks;

echo json_encode($response);