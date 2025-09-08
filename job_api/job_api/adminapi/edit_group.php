<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Input data
$data = json_decode(file_get_contents("php://input"), true);
$groupId = $data['group_id'] ?? null;
$groupName = trim($data['name'] ?? '');
$usernames = $data['users'] ?? [];

if (!$groupId || !$groupName || empty($usernames)) {
    echo json_encode(["status" => "error", "message" => "Missing group ID, name, or users"]);
    exit;
}

// Update group name
$updateGroup = $conn->prepare("UPDATE groups SET name = ? WHERE id = ?");
$updateGroup->bind_param("si", $groupName, $groupId);
if (!$updateGroup->execute()) {
    echo json_encode(["status" => "error", "message" => "Failed to update group name"]);
    exit;
}
$updateGroup->close();

// Clear old members
$deleteMembers = $conn->prepare("DELETE FROM group_members WHERE group_id = ?");
$deleteMembers->bind_param("i", $groupId);
$deleteMembers->execute();
$deleteMembers->close();

// Insert updated members
$insertMember = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
$failedUsers = [];

foreach ($usernames as $username) {
    $username = trim($username);
    $getUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $getUser->bind_param("s", $username);
    $getUser->execute();
    $getUser->bind_result($userId);
    
    if ($getUser->fetch()) {
        $getUser->close();
        $insertMember->bind_param("ii", $groupId, $userId);
        if (!$insertMember->execute()) {
            $failedUsers[] = $username;
        }
    } else {
        $getUser->close();
        $failedUsers[] = $username;
    }
}
$insertMember->close();
$conn->close();

// Final response
if (empty($failedUsers)) {
    echo json_encode(["status" => "success"]);
    exit; 
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Some users could not be added",
        "failed_users" => $failedUsers
    ]);
    exit; 
}

?>
