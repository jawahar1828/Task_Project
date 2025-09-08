<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$username = $_GET['username'] ?? null;

try {
    $conn = new PDO("mysql:host=localhost;dbname=job_allocation", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($username) {
        // Debug log
        error_log("FETCH_TASKS.php: called with username = $username");

        $stmt = $conn->prepare("
            SELECT 
                tr.id AS recipient_id,
                t.id AS task_id,
                t.title,
                t.description,
                t.priority,
                t.due_date,
                t.created_at,
                tr.status,
                t.comment,
                'Principal' AS assigned_by,
                (
                    SELECT file_path 
                    FROM task_attachments 
                    WHERE task_id = t.id 
                    ORDER BY uploaded_at DESC 
                    LIMIT 1
                ) AS attachment
            FROM task_recipients tr
            INNER JOIN tasks t ON tr.task_id = t.id
            WHERE tr.recipient_name = :username AND t.soft_deleted = 0
            ORDER BY t.created_at DESC
        ");

        $stmt->execute([':username' => trim($username)]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($tasks);
        exit;
    }

    echo json_encode(["error" => "Missing 'username' parameter"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
