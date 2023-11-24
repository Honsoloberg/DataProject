<?php
include ("restaurantDetailsPopulate.php");
session_start();
$pop = new rDetailsPop();

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
        $imageName = $restaurantName . ".png";
        echo "<img src='$imageName' alt='$restaurantName' id='restaurant-image'>";
    ?>
    </div>
    <?php
            if (!empty($_SESSION["uname"])) {
                echo "<h3 style='text-align:center;padding-bottom:30px'> Welcome, " . $_SESSION["uname"] . "</h3>";
            } else {
                echo "<h3 style='text-align:center;'> Welcome, User</h3>";
            }
        ?>
    <div class="navBar">
        <ul class="navList">
            <li class="navItems"><a href="main.php">Home</a></li>
            <li class="navItems"><a href="accountPage.php">Account</a></li>
            <li class="navItems"><a href="orderPage.php">Orders</a></li>
            <li class="navItems" id="loginBut"><a href="index.php">Logout</a></li>
        </ul>
    </div>


    <div>
    <?php if (isset($_POST['get_all_items'])) : ?>
        <h2 style="text-align:center;">All Available Items</h2> 
        <!--Query 8-->
        <?php $pop->popAllItems($restaurantName); ?>
    <?php else : ?>
        <form method="post" action="">
            <input type="hidden" name="get_all_items" value="1">
            <button type="submit">Get All</button>
        </form>
    <?php endif; ?>
</div>



<div>
    <?php if (isset($_POST['get_top_list'])) : ?>
        <h2 style="text-align:center;">Feeling Frivolous?</h2>
        <!--Query 2-->
        <?php $pop->popTopList(); ?>
    <?php else : ?>
        <form method="post" action="">
            <input type="hidden" name="get_top_list" value="1">
            <button type="submit">Get Top List</button>
        </form>
    <?php endif; ?>
</div>


<div>
    <h2 style="text-align:center;">Wish to compare <?php echo $restaurantName; ?> with any other? </h2>
<!--Query 5-->
    <form method="post" action="">
        <input type="radio" name="restaurant" value="Starbucks"> Starbucks
        <input type="radio" name="restaurant" value="Wendys"> Wendys
        <input type="radio" name="restaurant" value="Osmows"> Osmows
        <input type="radio" name="restaurant" value="Tim Hortons"> Tim Hortons
        <input type="radio" name="restaurant" value="Mary Browns"> Mary Browns
        <input type="radio" name="restaurant" value="McDonalds"> McDonalds
        <button type="submit" name="compare_restaurants">Compare</button>
    </form>

    <?php
    if (isset($_POST['compare_restaurants'])) {
        $restaurantName1 = $restaurantName;
        $restaurantName2 = $_POST['restaurant'];
        $pop->popCompareList($restaurantName1, $restaurantName2);
    }
    ?>
</div>


<div>
    <?php if (isset($_POST['get_rest_address'])) : ?>
        <h2 style="text-align:center;">Query 7</h2>
        <?php $pop->popRestAddress($restaurantName); ?>
    <?php else : ?>
        <form method="post" action="">
            <button type="submit" name="get_rest_address">Get Restaurant Address</button>
        </form>
    <?php endif; ?>
</div>



    
<div>
    <?php if (isset($_POST['get_most_expensive'])) : ?>
        <h2 style="text-align:center;">Query 9</h2>
        <?php $pop->popMostExpensive($restaurantName); ?>
    <?php else : ?>
        <form method="post" action="">
            <input type="hidden" name="get_most_expensive" value="1">
            <button type="submit">Get Most Expensive</button>
        </form>
    <?php endif; ?>
</div>


<div>
    <?php if (isset($_POST['get_least_expensive'])) : ?>
        <h2 style="text-align:center;">Query 10</h2>
        <?php $pop->popLeastExpensive($restaurantName); ?>
    <?php else : ?>
        <form method="post" action="">
            <input type="hidden" name="get_least_expensive" value="1">
            <button type="submit">Get Least Expensive</button>
        </form>
    <?php endif; ?>
</div>

    
    <div>
    <br>
    <form method="get" style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
        <label for="search">Search By Keyword:</label>
        <input type='hidden' name="style" value= "<?php echo $style ?>">
        <input type='hidden' name="Clear" value= "<?php echo $Clear ?>">
        <input type="text" id="search" name="query" placeholder="What are you hungry for?">
        <button type="submit">Search</button> 
    </form>

    <?php
    if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
    $searchTerm = $conn->real_escape_string($searchTerm);
    $pop->popSearch($searchTerm,$restaurantName);}
    ?>
    </div>
            <div>
           <form method='get' style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
            <input type='hidden' name='style' value= '<?php echo $style ?>'>
            <input type='hidden' name='Clear' value='5'>
            <button type='submit'>Clear Results</button>
            </form>
            </div>

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
                Users.UserName = '$usernameVariable' AND
                Restaurant.Rname = '$restaurantName'";
                

        $result = $conn->query($sql);
        $conn->close();

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



</body>
</html>
