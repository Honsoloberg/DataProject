<?php
    include("mainPopulate.php");
    include("clearOrder.php");

    //Create session to utilize session variables
    session_start();

    //Create objects to link functionality
    $clear = new clearOrder();
    $clear->clearOrder();

    $pop = new MPopulate();
    if(isset($_SESSION['search'])){
        $_SESSION['search'] = NULL;
        $_SESSION['quant'] = NULL;
    }
    if(isset($_SESSION['style'])){
        $_SESSION['style'] = NULL;
    }
    //Objects^^

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Food Ferry</title>
    <link rel="stylesheet" href="nav.css">
</head>
<body>
    <div>
        <img src="Food_Ferry.png" alt="Food Ferry" width="175" height="210" style="display:block;margin-left:auto;margin-right:auto;">

        <?php
        //Generate customized message for user
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
    <div>
        <h2 style="text-align:center;">Nearby Restaurants</h2>
        <?php
            //Calls function to populate Nearby Restaurants
            $pop->populate();
        ?>        
    </div>

    <div>
        <h2 style="text-align:center;">Restaurant Workload</h2>
        <?php
            //Calls function to populate the Workload table
            $pop->popWait();
        ?>
    </div>

    <div>
    <?php if (isset($_POST['get_top_list'])) : ?>
        <h2 style="text-align:center;">Feeling Frivolous?</h2>
        <!--Query 2-->
        <?php
        //Calls function select all The most expensive items 
        $pop->popTopList(); 
        ?>
    <?php else : ?>
        <form method="post" action="">
            <input type="hidden" name="get_top_list" value="1">
            <button type="submit">High Roller Items</button>
        </form>
    <?php endif; ?>
</div>
    
</body>
</html>
