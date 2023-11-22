<?php
    include("mainPopulate.php");
    session_start();
    $pop = new MPopulate();
    if(isset($_SESSION['search'])){
        $_SESSION['search'] = NULL;
    }
    if(isset($_SESSION['style'])){
        $_SESSION['style'] = NULL;
    }
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
            <li class="navItems"><a href="???">Account</a></li>
            <li class="navItems"><a href="???">Recommendations</a></li>
            
        </ul>
    </div>
    <div>
        <h2 style="text-align:center;">Nearby Restaurants</h2>
        <?php
            $pop->populate();
        ?>        
    </div>
</body>
</html>
