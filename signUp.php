<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'seproject';

// Create a new PDO instance
try {
    // Create the database if it doesn't exist
    $conn = new PDO("mysql:host=$host;", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("CREATE DATABASE IF NOT EXISTS $database");
    $conn->exec("USE $database");

    // Create the users table if it doesn't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate form data
        // You can add additional validation rules as per your requirements
        if ($password !== $confirmPassword) {
            echo 'Passwords do not match.';
            exit;
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo '<style>.email-error { color: red; }</style>';
            echo 'Email already exists.';
            exit;
        }

        // Hash the password

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Registration successful
        echo '<script>
            setTimeout(function() {
                alert("Registration successful. You can now sign in.");
                window.location.href = "../signIn.html";
            }, 2000);
        </script>';
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
