<?php
session_start();
include('php/db_connect.php');

// Add Task Handling (for AJAX request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_text'])) {
    $task_text = mysqli_real_escape_string($conn, $_POST['task_text']);  // Prevent SQL Injection

    if (!empty($task_text)) {
        $query = "INSERT INTO tasks (task_text) VALUES ('$task_text')";
        if (mysqli_query($conn, $query)) {
            $task_id = mysqli_insert_id($conn);
            echo json_encode(['task_id' => $task_id, 'task_text' => $task_text]);
        } else {
            echo json_encode(['error' => 'Failed to add task.']);
        }
    }
    exit; // Ensure that the script stops after responding
}

// Fetch existing tasks
$query = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

mysqli_close($conn);
?>
