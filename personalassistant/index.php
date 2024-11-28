<?php
session_start();
include('php/db_connect.php');  // Include database connection

// Handle form submissions for login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's the login form
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate data
        if (empty($username) || empty($password)) {
            $login_message = "Please fill in all fields!";
        } else {
            // Prepare the SQL statement to check for username
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    // Successful login
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: homepage.php");  
                    exit();
                } else {
                    $login_message = "Incorrect password!";
                }
            } else {
                $login_message = "No user found with that username!";
            }
        }
    }

    // Handle registration form submission
    if (isset($_POST['register'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];

        // Validate data
        if (empty($new_username) || empty($new_password)) {
            $register_message = "Please fill in all fields!";
        } else {
            // Check if username already exists
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $new_username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $register_message = "Username already exists!";
            } else {
                // Hash the password
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Insert the new user into the database
                $sql = "INSERT INTO users (username, password_hash) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $new_username, $password_hash);

                if ($stmt->execute()) {
                    $register_message = "Registration successful! You can now log in.";
                } else {
                    $register_message = "Error: " . $stmt->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Smart Personal Assistant</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Login to Your Account</h1>
    </header>

    <main>
        <section id="login-section">
            <h2>Login</h2>
            <form action="index.php" method="POST">
                <input type="text" name="username" placeholder="Enter Username" required>
                <input type="password" name="password" placeholder="Enter Password" required>
                <button type="submit" name="login">Login</button>
            </form>

            <?php
            if (isset($login_message)) {
                echo "<p>$login_message</p>";
            }
            ?>
        </section>

        <section id="register-section">
            <h2>Sign Up</h2>
            <form action="index.php" method="POST">
                <input type="text" name="new_username" placeholder="Enter Username" required>
                <input type="password" name="new_password" placeholder="Enter Password" required>
                <button type="submit" name="register">Sign Up</button>
            </form>

            <?php
            if (isset($register_message)) {
                echo "<p>$register_message</p>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; Project</p>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
