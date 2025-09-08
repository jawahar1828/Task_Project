<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Input
$data = json_decode(file_get_contents("php://input"), true);
$groupName = trim($data['name']);
$usernames = $data['users'] ?? [];

if (!$groupName || empty($usernames)) {
    echo json_encode(["status" => "error", "message" => "Missing group name or users"]);
    exit;
}

// Check if group exists
$check = $conn->prepare("SELECT id FROM groups WHERE name = ?");
$check->bind_param("s", $groupName);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Group already exists"]);
    exit;
}
$check->close();

// Insert group
$insertGroup = $conn->prepare("INSERT INTO groups (name) VALUES (?)");
$insertGroup->bind_param("s", $groupName);
if (!$insertGroup->execute()) {
    echo json_encode(["status" => "error", "message" => "Failed to create group"]);
    exit;
}
$groupId = $conn->insert_id;
$insertGroup->close();

// Assign users
$failedUsers = [];
$insertUser = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");

foreach ($usernames as $username) {
    $username = trim($username);
    $userQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $userQuery->bind_param("s", $username);
    $userQuery->execute();
    $userQuery->bind_result($userId);
    if ($userQuery->fetch()) {
        $userQuery->close();
        $insertUser->bind_param("ii", $groupId, $userId);
        if (!$insertUser->execute()) {
            $failedUsers[] = $username;
        }
    } else {
        $userQuery->close();
        $failedUsers[] = $username;
    }
}

$insertUser->close();
$conn->close();

// Result
if (empty($failedUsers)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to assign one or more users",
        "failed_users" => $failedUsers
    ]);
}
