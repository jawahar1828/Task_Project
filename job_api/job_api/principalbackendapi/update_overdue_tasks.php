<?php
include 'db.php'; // Database connection

// Update all tasks where due date has passed AND status is still 'pending' or 'inprogress'
$query = "
    UPDATE task_recipients tr
    JOIN tasks t ON tr.task_id = t.id
    SET tr.status = 'overdue'
    WHERE t.due_date < CURDATE()
      AND tr.status IN ('pending', 'inprogress')
";

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => 'Overdue tasks updated']);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>
