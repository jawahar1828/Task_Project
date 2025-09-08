<?php
header('Content-Type: application/json');
include 'db.php';

$query = "SELECT name FROM staff"; // Use your actual table
$result = mysqli_query($conn, $query);

$recipients = [];
while ($row = mysqli_fetch_assoc($result)) {
  $recipients[] = $row;
}
echo json_encode($recipients);
?>
