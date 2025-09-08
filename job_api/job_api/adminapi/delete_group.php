<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$groupId = intval($data['group_id']);

if (!$groupId) {
    echo json_encode(["status" => "error", "message" => "Invalid group ID"]);
    exit;
}

// Delete group members first due to FK constraint
$conn->query("DELETE FROM group_members WHERE group_id = $groupId");
$conn->query("DELETE FROM groups WHERE id = $groupId");

if ($conn->affected_rows >= 1) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Group not found or already deleted"]);
}
