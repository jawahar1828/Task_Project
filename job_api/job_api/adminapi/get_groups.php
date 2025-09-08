<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$groups = [];
$groupQuery = $conn->query("SELECT id, name FROM groups");

while ($group = $groupQuery->fetch_assoc()) {
    $groupId = $group['id'];
    $members = [];

    $memberQuery = $conn->prepare("
        SELECT u.username 
        FROM group_members gm 
        JOIN users u ON gm.user_id = u.id 
        WHERE gm.group_id = ?
    ");
    $memberQuery->bind_param("i", $groupId);
    $memberQuery->execute();
    $memberResult = $memberQuery->get_result();

    while ($row = $memberResult->fetch_assoc()) {
        $members[] = $row['username'];
    }

    $groups[] = [
        "id" => $group['id'],
        "name" => $group['name'],
        "members" => $members
    ];
}

echo json_encode($groups);
$conn->close();
