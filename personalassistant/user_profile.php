<?php
session_start();
include('php/db_connect.php');

// Fetch the current user's profile data if logged in
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle username and password updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $new_username = mysqli_real_escape_string($conn, $_POST['username']);
        $new_password = mysqli_real_escape_string($conn, $_POST['password']);
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the username and password in the database
        $update_query = "UPDATE users SET username = '$new_username', password_hash = '$hashed_password' WHERE user_id = '$user_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['username'] = $new_username; // Update the session with the new username
            $update_message = "Profile updated successfully!";
        } else {
            $update_message = "Error updating profile.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>User Profile</h1>
        <a href="homepage.php" class="back-btn">Back to Homepage</a>
    </header>

    <main>
        <section id="profile-section">
            <h2>Your Profile</h2>
            <form method="POST">
                <!-- Display the current username -->
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

                <!-- Password change input -->
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required>
                
                <button type="submit">Update Profile</button>
            </form>

            <!-- Display update message -->
            <?php if (isset($update_message)): ?>
                <p><?php echo htmlspecialchars($update_message); ?></p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; Project</p>
    </footer>
    <script src="js/app.js">
            document.addEventListener("DOMContentLoaded", function() {
            if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            }
        });
    </script>
</body>
</html>
