<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "job_allocation");

$deptName = $_GET['department'] ?? '';
$deptName = $conn->real_escape_string($deptName);

$deptName = $conn->real_escape_string($_GET['department']); // sanitize input
$sql = "SELECT id, name FROM faculties WHERE department = '$deptName' AND active_mode = 0";
$res = $conn->query($sql);

$list = [];
while ($r = $res->fetch_assoc()) {
  $list[] = $r;
}
echo json_encode(["success" => true, "faculties" => $list]);
?>
