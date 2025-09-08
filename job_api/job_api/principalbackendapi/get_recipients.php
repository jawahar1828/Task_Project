<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "job_allocation");
if ($conn->connect_error) { echo json_encode(["success"=>false]); exit; }

$sql = "SELECT id,name FROM recipients";
$res = $conn->query($sql);

$list = [];
while($r = $res->fetch_assoc()) $list[] = $r;
echo json_encode(["success"=>true, "recipients"=>$list]);
?>
