<?php
include("config.php");
session_start();

$config = new Config();
$conn = new mysqli($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Rname FROM restaurant";
$result = $conn->query($sql);
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
            <li class="navItems"><a href="index.php">Home</a></li>
            <li class="navItems"><a href="???">Account</a></li>
            <li class="navItems"><a href="???">Recommendations</a></li>
            
        </ul>
    </div>
    <div>
        <h2>Nearby Restaurants</h2>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row in an unordered list with dynamically changing image sources and bold restaurant names
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                $imageName = $row["Rname"] . ".jpg";
                echo "<li>";
                echo "<a href='restaurantDetails.php'>";
                echo "<img src='$imageName' alt='Restaurant Image' width='250' height='200' style='margin-right: 10px;'>";
                echo "<strong>" . $row["Rname"] . "</strong>";
                echo "</a>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "0 results";
        }
        ?>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
