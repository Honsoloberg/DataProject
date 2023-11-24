<?php
include("account.php");

session_start();
$account = new accountInfo();
$account->pullInitial();

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
    <div style="padding-top:20px;">
        <h3 style="padding-bottom:10px;text-align:center;">Funds:</h3>

        <?php
            if(isset($_POST['funds'])){
                $account->addFunds();
                unset($_POST['funds']);
            }
            $account->getFunds();
        ?>

        <form style="text-align:center;" method="post" action="">
            <label for="funds">Add Funds: </label>
            <input type="number" name="funds" step="0.01">
            <button type="submit">Add</button>
        </form>

    </div>
    <br>
    <div>
        <h3 style="padding-bottom:10px;text-align:center;">Address:</h3>

        <?php
            $account->getAddress();
        ?>
        <button type="button" style="display:block;margin-left:auto;margin-right:auto;margin-top:10px;" href="locationMap.php">Change Address</button>
    </div>
    </body>
</html>