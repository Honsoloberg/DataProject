<?php
include("config.php");
session_start();

$config = new Config();
$conn = new mysqli($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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
<?php 
// Assume a variable containing the restaurant name is set (you need to retrieve this from your application logic)
$style = isset($_GET["style"]) ? $_GET["style"] : 1;
if (!isset($_SESSION['style'])) {
    $_SESSION['style'] = $style;
}


switch ($style) {
    case 1:
        $restaurantName = "Starbucks";

        break;
    case 2:
        $restaurantName = "Wendys";
        break;
    case 3:
        $restaurantName = "Osmows";
        break;
    case 4:
        $restaurantName = "Tim Hortons";
        break;
    case 5:
        $restaurantName = "Mary Browns";
        break;
    case 6:
        $restaurantName = "McDonalds";
        break;
    // Add more cases if needed

    default:
        $restaurantName = "Unknown Restaurant";
        }       
?>
    <div id="image-container">
    <h1>Welcome to <?php echo $restaurantName; ?></h1>
    <?php
        $imageName = $restaurantName . ".jpg";
        echo "<img src='$imageName' alt='$restaurantName' id='restaurant-image'>";
    ?>
    </div>
    <?php

    // SQL query to retrieve items based on the provided restaurant name
    $sql = "SELECT Items.*
        FROM Items
        INNER JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Restaurant.Rname = '$restaurantName'";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row
    echo "<ul>";
    echo "<strong>Featured Items</strong>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "Item: " . $row["Iname"] . ", Price: $" . $row["Price"];
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "No items found for the provided restaurant name.";
}
?>


<div>


    <form method="get">
        <label for="search">Search By Keyword:</label>
        <input type='hidden' name="style" value= "<?php echo $style ?>">
        <input type="text" id="search" name="query" placeholder="What are you hungry for?">
        <button type="submit">Search</button>
        
    </form>

    <?php
    // Initialize an array to store search results
    $searchResults = [];

    // Check if the form is submitted with a search term
    if (isset($_GET['query'])) {
        $searchTerm = $_GET['query'];

        // Sanitize the user input to prevent SQL injection
        $searchTerm = $conn->real_escape_string($searchTerm);

        // Define the default sorting order
        $sortOrder = "ASC";

        // Check if a sorting order is specified in the URL
        if (isset($_GET['sort'])) {
            $sortOrder = $_GET['sort'];
        }

        // SQL query to retrieve items based on the search term and sorting order
        $sql = "SELECT Items.*
                FROM Items
                INNER JOIN Restaurant ON Items.RID = Restaurant.ID
                WHERE Restaurant.Rname = '$restaurantName' AND Items.Iname LIKE '%$searchTerm%'
                ORDER BY Items.Price $sortOrder";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Add each result to the searchResults array
            while ($row = $result->fetch_assoc()) {
                $searchResults[] = [
                    'name' => $row["Iname"],
                    'price' => $row["Price"]
                ];
            }

            // Output sorting buttons
            echo "<div>";
            echo "<p>Sort by Price:</p>";
            echo "<form method='get'>";
            echo "<input type='hidden' name='query' value='$searchTerm'>";
            echo "<button type='submit' name='sort' value='ASC'>Lowest to Highest</button>";
            echo "<button type='submit' name='sort' value='DESC'>Highest to Lowest</button>";
            echo "</form>";
            echo "</div>";

            // Output search results in a table
            echo "<table border='1'>";
            echo "<tr><th>Item</th><th>Price</th></tr>";
            foreach ($searchResults as $result) {
                echo "<tr>";
                echo "<td>" . $result['name'] . "</td>";
                echo "<td>$" . $result['price'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Button to clear the results
            echo "<form method='get'>";
            echo '<input type="hidden" name="style" value= "<?php echo $style ?>"';
            echo "<button type='submit' name='clear'>Clear Results</button>";
            echo "</form>";
        } else {
            echo "No items found for the provided search term.";
        }
    } else {
        echo "Please enter a search term.";
    }

    // Close the database connection
    ?>
</div>










<div>
    <h2>Other Nearby Restaurants</h2>
    <?php
    // Assuming you have a "restaurants" table with a "name" column
    $sql = "SELECT Rname FROM restaurant";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row in an unordered list, with each value as a link to "main.php"
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $otherRestaurantName = $row["Rname"];
            if ($otherRestaurantName != $restaurantName) {
                echo "<li><a href='main.php?restaurantName=" . urlencode($otherRestaurantName) . "'>" . $otherRestaurantName . "</a></li>";
            }
        }
        echo "</ul>";
    } else {
        echo "0 results";
    }

    // Close the database connection
    $conn->close();
    ?>
</div>






</body>
</html>







