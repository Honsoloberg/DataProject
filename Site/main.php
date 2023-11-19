<?php
    include("config.php");
    session_start();
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
        <h2 class="header">Food Ferry</h2>
        <?php
            if(!empty($_SESSION["uname"])){
            echo "<h3 style='padding-right: 25px;'> Welcome, ". $_SESSION["uname"]."</h3>";
            }else {
                echo "<h3 style='padding-right: 25px;'> Welcome, User</h3>";
            }
        ?>
    </div>
    <div class="navBar">
        <ul class="navList">
            <li class="navItems"><a href="index.php">Home</a></li>
            <li class="navItems"><a href="???">Account</a></li>
            <li class="navItems"><a href="???">Recommendations</a></li>
        </ul>
    </div>
    <?php
    
    ?>
    </body>
</html>