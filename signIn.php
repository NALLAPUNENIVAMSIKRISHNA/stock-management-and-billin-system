<?php
// Database configuration
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'root';
$dbName = 'seproject';

// Start the session
session_start();

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formEmail = $_POST['email'];
    $formPassword = $_POST['password'];

    // Create a new MySQLi instance
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check for connection errors
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute the SQL statement to check the user's credentials
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $formEmail);
    $stmt->execute();
    $stmt->store_result();

    // Fetch the user data
    $stmt->bind_result($id, $username, $password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // User found, verify the password
        if ($formPassword === $password) {
            // Password is correct, set session variables and redirect to the home page or any other desired page
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header('Location: ../dashboard.php');
            exit();
        } else {
            // Password is incorrect
            echo '<p style="color: red;">Invalid email or password.</p>';
        }
    } else {
        // User not found
        echo '<p style="color: red;">Invalid email or password.</p>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
