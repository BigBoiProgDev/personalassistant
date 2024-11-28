<?php
session_start();

// Database connection
include ('php/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check the user's credentials
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password using password_hash
        if (password_verify($password, $user['password_hash'])) {
            // Store user ID in session
            $_SESSION['user_id'] = $user['user_id'];
            echo 'success';  // Successful login
        } else {
            echo 'error';  // Invalid password
        }
    } else {
        echo 'error';  // User not found
    }

    $stmt->close();
    $conn->close();
}
?>
