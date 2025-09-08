<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "job_allocation");

if ($conn->connect_error) {
    echo json_encode(["success"=>false]);
    exit;
}

$sql = "SELECT id, name FROM deans WHERE active_mode = 0";
$res = $conn->query($sql);

$list = [];
while($r = $res->fetch_assoc()) {
    $list[] = $r;
}

echo json_encode(["success"=>true, "deans"=>$list]);
?>
