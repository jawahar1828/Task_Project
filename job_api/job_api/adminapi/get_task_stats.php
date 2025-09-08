<?php
header('Content-Type: application/json');

// DB credentials (self-contained)
$host = "localhost";
$user = "root";
$password = "";
$db = "job_allocation";

// DB connection
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
  echo json_encode(["error" => "Connection failed"]);
  exit;
}

// Step 1: Count tasks from task_recipients
$stats_sql = "
  SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue
  FROM task_recipients
";

$result = $conn->query($stats_sql);
if ($result && $row = $result->fetch_assoc()) {
  echo json_encode([
    'total' => (int)$row['total'],
    'completed' => (int)$row['completed'],
    'pending' => (int)$row['pending'],
    'overdue' => (int)$row['overdue']
  ]);
} else {
  echo json_encode([
    'total' => 0,
    'completed' => 0,
    'pending' => 0,
    'overdue' => 0
  ]);
}

$conn->close();
