<?php
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) {
  die(json_encode(["success" => false, "message" => "DB connection failed"]));
}

$group_id = intval($_GET['group_id'] ?? 0);
if ($group_id === 0) {
  echo json_encode(["success" => false, "message" => "Invalid group ID"]);
  exit;
}

$sql = "SELECT u.username, u.role, f.department
        FROM users u
        JOIN group_members gm ON gm.user_id = u.id
        LEFT JOIN faculties f ON f.name = u.username
        WHERE gm.group_id = $group_id";

$result = $conn->query($sql);
$members = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $username = $row['username'];
    $role = $row['role'];
    $department = $row['department'];

    // ✅ NEW LOGIC: Prefer department, fallback to role
    if (!empty($department)) {
      $label = "$username ($department)";
    } elseif (!empty($role)) {
      $label = "$username ($role)";
    } else {
      $label = $username;
    }

    $members[] = $label;
  }

  echo json_encode(["success" => true, "members" => $members]);
} else {
  echo json_encode(["success" => false, "message" => "No members found in this group"]);
}
?>
