<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./signIn.html"); // Redirect to the login page if user is not logged in
    exit();
}

// Retrieve grocery data from the JSON file
$groceryData = file_get_contents('grocery.json');
$groceryItems = json_decode($groceryData, true);

// Check if JSON decoding was successful
if ($groceryItems === null) {
    die('Error: Unable to decode JSON data');
}

// Function to get the item details by index
function getItemByIndex($groceryItems, $index)
{
    return $groceryItems[$index];
}

// Get the selected items for the logged-in user from the session cart
$selectedItems = [];
if (isset($_SESSION['cart'][$_SESSION['user_id']]) && is_array($_SESSION['cart'][$_SESSION['user_id']])) {
    foreach ($_SESSION['cart'][$_SESSION['user_id']] as $index) {
        $item = getItemByIndex($groceryItems, $index);
        $selectedItems[] = $item;
    }
}

// Calculate the total price of selected items
$totalPrice = 0;
foreach ($selectedItems as $item) {
    $totalPrice += $item['price'];
}

// Get user information from the database based on the session user_id
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "seproject";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to get user information
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user information
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $email = $row['email'];
} else {
    $name = "Unknown";
    $email = "Unknown";
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .thank-you {
            color: red;
            text-align: center;
            margin-top: 50px;
            font-size: 24px;
        }

        .user-info {
            margin-top: 20px;
            background-color: #e1f3d8;
            padding: 20px;
        }

        .user-info p {
            margin: 0;
        }

        .gst {
            margin-top: 20px;
            background-color: #e1f3d8;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .gst p {
            margin: 0;
        }

        .print-btn,
        .download-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #42b983;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Invoice</h1>

        <div class="thank-you">
            Thank you for shopping!
        </div>

        <div class="user-info">
            <p>Name: <?php echo $username; ?></p>
            <p>Email: <?php echo $email; ?></p>
        </div>

        <div class="gst">
            <p>GST (18%): ₹<?php echo $totalPrice * 0.18; ?></p>
            <p>Total (including GST): ₹<?php echo $totalPrice * 1.18; ?></p>
        </div>

        <a href="#" class="print-btn">Print</a>
        <a href="#" class="download-btn">Download</a>
    </div>
</body>

</html>
