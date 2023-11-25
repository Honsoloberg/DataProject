<?php
include("order.php");
//Create session to utilize session variables
session_start();

//Create object to link functionality
$order = new order();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Food Ferry</title>
        <link rel="stylesheet" href="nav.css">
    </head>

    <body>
    <div>
        <img src="Food_Ferry.png" alt="Food Ferry" width="175" height="210" style="display:block;margin-left:auto;margin-right:auto;">

        <?php
            //Generates customised message for user
            if (!empty($_SESSION["uname"])) {
                echo "<h3 style='text-align:center;padding-bottom:30px'> Welcome, " . $_SESSION["uname"] . "</h3>";
            } else {
                echo "<h3 style='text-align:center;'> Welcome, User</h3>";
            }
        ?>
    </div>

    <div class="navBar">
        <ul class="navList">
            <li class="navItems"><a href="main.php">Home</a></li>
            <li class="navItems"><a href="accountPage.php">Account</a></li>
            <li class="navItems"><a href="orderPage.php">Orders</a></li>            
            <li class="navItems" id="loginBut"><a href="index.php">Logout</a></li>
        </ul>
    </div>
    <br>

    <h3 style='text-align:center;padding-bottom:20px;'>Orders</h3>
    <?php
        //Calls function to cancel orders
        if(isset($_POST['order'])){
            $order->cancel($_POST['order']);
        }
    ?>
    <table border=1 style='margin:0 auto;text-align:center;'>
        <tr>
            <th>Order Number</th>
            <th>Restaurant</th>
            <th>Total Price</th>
            <th>Driver Name</th>
            <th>Drivers Car</th>
            <th>Drivers Plate</th>
            <th>Cancellation</th>
            <?php
                //calls funciton to populate orders table
                $order->populate();
            ?>
        </tr>
        
    </table>
    </body>
</html>