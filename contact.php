<!DOCTYPE html>
<html>
<head>
    <title>Contact Page</title>
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Contact Page</h1>

    <?php
    // Define an array of people with their details
    $people = array(
        array(
            'name' => 'Vamsi ',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'email' => 'Vamsi_nallapuneni@srmap.edu.in'
        ),
        array(
            'name' => 'Aswini',
            'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem.',
            'email' => 'aswini@srmap.edu.in'
        ),
        array(
            'name' => 'Pavan',
            'description' => 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur.',
            'email' => 'Pavan@srmap.edu.in'
        )
    );

    // Loop through the people array and display the cards
    foreach ($people as $person) {
        echo '<div class="card">';
        echo '<h3>' . $person['name'] . '</h3>';
        echo '<p>' . $person['description'] . '</p>';
        echo '<p>Email: <a href="mailto:' . $person['email'] . '">' . $person['email'] . '</a></p>';
        echo '</div>';
    }
    ?>

</body>
</html>
