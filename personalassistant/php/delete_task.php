<?php
session_start();
include ('php/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $task_id = $_POST['task_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        echo "Task deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>
