<?php
include("config.php");
session_start();

$config = new Config();
$conn = new mysqli($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(!isset($_SESSION['style'])){
    $_SESSION['style'] = $_GET['Rname'];
}


switch ($_SESSION['style']) {
    case "Starbucks":
        $restaurantName = "Starbucks";
        $style = 1;
        break;
    case "Wendys":
        $restaurantName = "Wendys";
        $style = 2;
        break;
    case "Osmows":
        $restaurantName = "Osmows";
        $style = 3;
        break;
    case "Tim Hortons":
        $restaurantName = "Tim Hortons";
        $style = 4;
        break;
    case "Mary Browns":
        $restaurantName = "Mary Browns";
        $style = 5;
        break;
    case "McDonalds":
        $restaurantName = "McDonalds";
        $style = 6;
        break;
    // Add more cases if needed

    default:
        $restaurantName = "Unknown Restaurant";
        }  
        $sortOrder = "ASC"; 
        $searchResults = [];

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
    <link rel="stylesheet" href="nav.css">
</head>
<body>

    <div id="image-container">
    <h1>Welcome to <?php echo $restaurantName; ?></h1>
    <?php
        $imageName = $restaurantName . ".jpg";
        echo "<img src='$imageName' alt='$restaurantName' id='restaurant-image'>";
    ?>
    </div>
    <div class="navBar">
        <ul class="navList">
            <li class="navItems"><a href="main.php">Home</a></li>
            <li class="navItems"><a href="accountPage.php">Account</a></li>
        </ul>
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

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
    $searchTerm = $conn->real_escape_string($searchTerm);
    

    if (isset($_GET['sort'])) {
        $sortOrder = $_GET['sort'];
    }

    $sql = "SELECT Items.*
            FROM Items
            INNER JOIN Restaurant ON Items.RID = Restaurant.ID
            WHERE Restaurant.Rname = '$restaurantName' AND Items.Iname LIKE '%$searchTerm%'
            ORDER BY Items.Price $sortOrder";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Add each result to the searchResults array
        if(isset($_SESSION['search'])){
            $searchResults = $_SESSION['search'];
        }
        if($_GET['query'] != ""){
            while ($row = $result->fetch_assoc()) {
                $searchResults[] = [
                    'name' => $row["Iname"],
                    'price' => $row["Price"]
                ];
            }
            $_SESSION['search'] = $searchResults;
        }
    }

}
?>

<div>
    <p>Sort by Price:</p>
    <form method='get'>
    <input type='hidden' name="style" value="<?php echo $style ?>">
    <input type='hidden' name="query" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
    <button type='submit' name='sort' value='ASC'>Lowest to Highest</button>
    <button type='submit' name='sort' value='DESC'>Highest to Lowest</button>
    </form>


</div>

<table border='1'>
    <tr><th>Item</th><th>Price</th></tr>
    <?php foreach ($searchResults as $result) { ?>
        <tr>
            <td><?php echo $result['name']; ?></td>
            <td>$<?php echo $result['price']; ?></td>
        </tr>
    <?php } ?>
</table>
           <form method='get'>
            <input type='hidden' name='style' value= '<?php echo $style ?>'>
            <button type='submit'>Clear Results</button>
            </form>

</div>


















<h2>Active Orders</h2>

<table border="1">
    <thead>
        <tr>

            <th>Order Number</th>
            <th>Restaurant</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody id="orderTableBody">
    <?php
    // Check if $_SESSION["uname"] is set and not empty before using it
    if (isset($_SESSION["uname"]) && !empty($_SESSION["uname"])) {
        // Use the value stored in $_SESSION["uname"] as the username variable
        $usernameVariable = $_SESSION["uname"];

        $sql = "SELECT
                O_Items.O_ID AS Order_Number,
                O_Items.Quant AS Quantity,
                Items.Iname AS ItemName,
                Items.Decript AS Descriptions,
                Items.Price,
                Restaurant.Rname AS RestaurantName
    
            FROM
                O_Items
            JOIN
                Items ON O_Items.Item_ID = Items.ID
            JOIN
                Orders ON O_Items.O_ID = Orders.ID
            JOIN
                Restaurant ON Orders.RID = Restaurant.ID
            JOIN
                Users ON Orders.UID = Users.ID
            WHERE
                Users.UserName = '$usernameVariable'";

        $result = $conn->query($sql);

        // Check if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['Order_Number'] . '</td>';
                echo '<td>' . $row['RestaurantName'] . '</td>';
                echo '<td>' . $row['ItemName'] . '</td>';
                echo '<td>' . $row['Quantity'] . '</td>';
                echo '<td>' . $row['Descriptions'] . '</td>';
                echo '<td>' . $row['Price'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">No active orders found</td></tr>';
        }
    } else {
        // Handle the case where $_SESSION["uname"] is not set or empty
        echo '<tr><td colspan="5">Invalid username</td></tr>';
    }

    // Close the database connection

    ?>
</tbody>
</table>




























<!-- You can add a button or link to trigger the addition of a new row -->
<button onclick="addNewRow()">Add Item to Oder</button>

<script>
function addNewRow() {
    // You can add logic here to fetch data or get input from the user
    var newRowData = [' ', ' ', '', 0,'',0];

    var tableBody = document.getElementById('orderTableBody');
    var newRow = document.createElement('tr');

    newRowData.forEach(function(value) {
        var cell = document.createElement('td');
        cell.textContent = value;
        newRow.appendChild(cell);
    });

    tableBody.appendChild(newRow);
}
</script>


<div>
    <h2>Other Nearby Restaurants</h2>
    <?php
    $sql = "SELECT Rname FROM restaurant";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
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
