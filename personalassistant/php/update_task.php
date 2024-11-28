<?php
include ('php/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $new_task_text = $_POST['task_text'];

    if (!empty($task_id) && !empty($new_task_text)) {
        $stmt = $conn->prepare("UPDATE tasks SET task_text = ? WHERE task_id = ?");
        $stmt->bind_param("si", $new_task_text, $task_id);

        if ($stmt->execute()) {
            echo "Task updated successfully";
        } else {
            echo "Error updating task: " . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
