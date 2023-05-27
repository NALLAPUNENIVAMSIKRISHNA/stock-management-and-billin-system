<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/dashboard.css">
</head>

<body>
  <h1 class="logo">
    Grocery Store
    <div class="right-buttons">
      <a href="contact.php" class="btn">Contact</a>
      <a href="cartcode.php" class="btn">Cart</a>
      <a href="./php/logout.php" class="btn">Logout</a>
    </div>
  </h1>
  <div class="container">
    <div class="card-wrapper">
      <?php
      // Include the PHP file
      include './php/dashboardcode.php';
      ?>
    </div>
  </div>
</body>

</html>