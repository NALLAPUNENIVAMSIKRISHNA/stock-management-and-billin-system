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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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

        .item-list {
            list-style-type: none;
            padding: 0;
        }

        .item-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #fff;
            margin-bottom: 10px;
        }

        .item-name {
            flex-basis: 60%;
        }

        .item-price {
            flex-basis: 20%;
        }

        .total-price {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }
        .pay-btn {
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
        <h1>Cart</h1>
        <?php if (count($selectedItems) > 0) : ?>
            <ul class="item-list">
                <?php foreach ($selectedItems as $item) : ?>
                    <li>
                        <span class="item-name"><?php echo $item['name']; ?></span>
                        <span class="item-price">₹<?php echo $item['price']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="total-price">
                Total Price: ₹<?php echo $totalPrice; ?>
            </div>
        <?php else : ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
        <a href="invoice.php" class="pay-btn">Pay</a> 
    </div>
</body>
</html>
