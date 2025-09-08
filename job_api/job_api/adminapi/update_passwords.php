<?php
// update_passwords.php - Run only once

$conn = new mysqli("localhost", "root", "", "job_allocation");

function customEncrypt($password) {
    return "#123!" . md5($password) . "@#$%)(jjdxbh";
}

// Read all users
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $plain = $row['password'];

        // Skip already-encrypted passwords
        if (strpos($plain, "#123!") === 0) continue;

        $encrypted = customEncrypt($plain);
        $update = $conn->query("UPDATE users SET password='$encrypted' WHERE id=$id");

        if ($update) {
            echo "User ID $id updated.<br>";
        } else {
            echo "Failed to update User ID $id.<br>";
        }
    }
    echo "<br>✅ All passwords updated.";
} else {
    echo "No users found.";
}

$conn->close();
?>
