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
            max-width: 300px; /* Adjust the maximum width as needed */
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
            <button type="submit">View all Choices</button>
        </form>
    <?php endif; ?>
</div>

<div>

    <h2 style="text-align:left;">Wish to compare <?php echo $restaurantName; ?> with any other? </h2>
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
    <!--Query 9-->
    <?php if (isset($_POST['get_most_expensive'])) : ?>
        <h2 style="text-align:center;">Most Expensive Item</h2>
        <?php $pop->popMostExpensive($restaurantName); ?>
    <?php else : ?>
        <form method="post" action="">
            <input type="hidden" name="get_most_expensive" value="1">
            <button type="submit">Get Most Expensive</button>
        </form>
    <?php endif; ?>
</div>


<div>
    <!--Query 10-->
    <?php if (isset($_POST['get_least_expensive'])) : ?>
        <h2 style="text-align:center;">Least Expensive Item</h2>
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
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if (isset($_GET['query']) && $_GET['query'] != "") {
                    $searchTerm = $_GET['query'];
                    $searchTerm = $conn->real_escape_string($searchTerm);
                    $pop->popSearch($searchTerm,$restaurantName);
                }
            ?>
            </tbody>
    </table>
    <?php
        if(isset($_POST['OrderGEN'])){
            $pop->genOrder($restaurantName);
        }
    ?>
    <form method="post" action="">
        <input type="hidden" name="OrderGEN" value="5">
        <button type="submit">Complete Order</button>
    </form>

<div>
    <!--Query 7-->
    <br>
    <?php if (isset($_POST['get_rest_address'])) : ?>
        <h2 style="text-align:center;"></h2>
        <?php $pop->popRestAddress($restaurantName); ?>
    <?php else : ?>
        <form method="post" action="">
            <button type="submit" name="get_rest_address">Get Restaurant Address</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
