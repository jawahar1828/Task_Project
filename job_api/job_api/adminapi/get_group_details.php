<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
if ($group_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid group ID"]);
    exit;
}

// Fetch group info
$group = null;
$stmt = $conn->prepare("SELECT id, name FROM groups WHERE id = ?");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$res = $stmt->get_result();
$group = $res->fetch_assoc();
$stmt->close();

if (!$group) {
    echo json_encode(["status" => "error", "message" => "Group not found"]);
    exit;
}

// Fetch group members (usernames)
$members = [];
$stmt = $conn->prepare("
  SELECT u.username 
  FROM group_members gm
  JOIN users u ON gm.user_id = u.id
  WHERE gm.group_id = ?
");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $members[] = $row['username'];
}
$stmt->close();

$conn->close();

// Return group data
echo json_encode([
    "id" => $group['id'],
    "name" => $group['name'],
    "members" => $members
]);
?>
