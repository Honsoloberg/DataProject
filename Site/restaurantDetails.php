<?php
include("config.php");
session_start();

$config = new Config();
$conn = new mysqli($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume a variable containing the restaurant name is set (you need to retrieve this from your application logic)
$restaurantName = "Starbucks";


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $restaurantName; ?></title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
        }

        /* Style the image container */
        #image-container {
            text-align: center;
        }

        /* Style the image */
        #restaurant-image {
            width: 100%;
            max-width: 600px; /* Adjust the maximum width as needed */
            height: auto;
        }
    </style>
</head>
<body>
    <div id="image-container">
        <h1><?php echo $restaurantName; ?></h1>
        <img src="Starbucks.jpg" alt="<?php echo $restaurantName; ?>" id="restaurant-image">
    </div>

    <!-- Add your additional content below this line -->

    <div>
        <h2>Other Nearby Restaurants</h2>
        <?php
        // Assuming you have a 'restaurants' table with a 'name' column
        $sql = "SELECT Rname FROM restaurant";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row in an unordered list
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row["Rname"] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "0 results";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <!-- Add more sections or content as needed -->

</body>
</html>







