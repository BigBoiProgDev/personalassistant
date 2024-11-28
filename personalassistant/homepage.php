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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Fetch existing tasks
$query = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Personal Assistant</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Welcome to Your Personal Assistant</h1>
        <p id="greeting">Hello,<?php echo htmlspecialchars($username);?>!</p>
        <a href="user_profile.php" class="profile-btn">Profile</a>
        <button id="toggle-theme">Dark Mode / Light Mode</button>
    </header>

    <main>
        <!-- To-Do List Section -->
        <section id="todo-section">
            <h2>Your Tasks</h2>
            <input type="text" id="new-task" placeholder="Add a new task...">
            <button id="add-task-btn">Add Task</button>
        </section>

        <!-- Task List Container -->
        <section id="task-container">
            <h2>Tasks</h2>
            <ul id="task-list">
                <!-- Tasks will be dynamically loaded here -->
                <?php foreach ($tasks as $task): ?>
                    <li>
                        <span><?php echo htmlspecialchars($task['task_text']); ?></span>
                        <button class="delete-btn" data-id="<?php echo $task['task_id']; ?>">Delete</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Weather Update Section -->
        <section id="weather-section">
            <h2>Weather Update</h2>
            <p id="weather-update">Loading...</p>
        </section>

        <!-- Daily Quote Section -->
        <section id="quote-section">
            <h2>Daily Quote</h2>
            <p id="daily-quote">Loading...</p>
        </section>

        <!-- Reminders Section -->
        <section id="reminder-section">
            <h2>Set a Reminder</h2>
            <input type="text" id="reminder-text" placeholder="Reminder Text">
            <input type="time" id="reminder-time">
            <button id="set-reminder-btn">Set Reminder</button>
            <ul id="reminder-list"></ul>
        </section>
    </main>

    <footer>
        <p>&copy; Project</p>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
