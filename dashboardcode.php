<?php
// Start the session to store the cart data
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./signIn.html"); // Redirect to the login page if the user is not logged in
    exit();
}

// Check if the cart array is already initialized in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve grocery data from the JSON file
$groceryData = file_get_contents('grocery.json');
$groceryItems = json_decode($groceryData, true);


// Check if the add-to-cart button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add-to-cart'])) {
        $index = $_POST['item-index'];

        // Check if the item is not already in the cart
        // Check if the user's cart is initialized as an array
        if (!isset($_SESSION['cart'][$_SESSION['user_id']]) || !is_array($_SESSION['cart'][$_SESSION['user_id']])) {
            $_SESSION['cart'][$_SESSION['user_id']] = [];
        }

        // Check if the item is not already in the cart
        if (!in_array($index, $_SESSION['cart'][$_SESSION['user_id']])) {
            // Add the item to the cart
            $_SESSION['cart'][$_SESSION['user_id']][] = $index;
            $addedToCart = true;
        } else {
            // Remove the item from the cart
            $itemIndex = array_search($index, $_SESSION['cart'][$_SESSION['user_id']]);
            if ($itemIndex !== false) {
                unset($_SESSION['cart'][$_SESSION['user_id']][$itemIndex]);
                $addedToCart = false;
            }
        }

        // Return a JSON response indicating success
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'addedToCart' => $addedToCart]);
        exit();
    } elseif (isset($_POST['checkout'])) {
        // Display the cart contents
        echo '<pre>';
        print_r($_SESSION['cart']);
        echo '</pre>';
        exit();
    }
}
?>

<!-- Display the grocery items -->
<div class="card-wrapper">
    <?php
    foreach ($groceryItems as $index => $item) {
        $name = $item['name'];
        $image = $item['image'];
        $price = $item['price'];

        // Check if the item is already in the cart
        $isInCart = isset($_SESSION['cart'][$_SESSION['user_id']]) && in_array($index, $_SESSION['cart'][$_SESSION['user_id']]);

        // Generate the HTML code for the card
        echo '
            <div class="card">
                <img src="images/' . $image . '" alt="' . $name . '">
                <div class="card-content">
                    <h3>' . $name . '</h3>
                    <p>₹' . $price . '</p>
                    <form method="POST">
                        <input type="hidden" name="item-index" value="' . $index . '">
                        <button type="button" class="add-to-cart-btn ' . ($isInCart ? 'added-to-cart' : '') . '" onclick="addToCart(' . $index . ', this)">' . ($isInCart ? 'Remove from Cart' : 'Add to Cart') . '</button>
                    </form>
                </div>
            </div>';
    }
    ?>
</div>

<script>
    function addToCart(index, button) {
        // Create an AJAX request to add/remove the item from the cart
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    if (response.addedToCart) {
                        button.innerHTML = 'Remove from Cart';
                        button.classList.add('added-to-cart');
                    } else {
                        button.innerHTML = 'Add to Cart';
                        button.classList.remove('added-to-cart');
                    }
                    location.reload(); // Reload the page to reflect the changes
                }
            } else {
                // Handle the error response here (optional)
            }
        };
        xhr.send('add-to-cart=true&item-index=' + index);
    }
</script>
