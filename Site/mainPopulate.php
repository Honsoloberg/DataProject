<?php
include "config.php";
class Mpopulate extends config{

    public function populate(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT Rname FROM restaurant";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<form method='post' action='restaurantDetails.php'>";

            while ($row = $result->fetch_assoc()) {
                $imageName = $row["Rname"] . ".jpg";
                echo "<button class='rButton' name='Rname' type='submit' value='". $row['Rname'] . "'>";
                echo "<img src='$imageName' alt='Restaurant Image' width='250' height='200' style='margin-right: 10px;'>";
                echo "<strong>" . $row["Rname"] . "</strong>";
                echo "</button>";
                
            }

            echo "</form>";
        } else {
            echo "<p style='text-align:center;padding-top:20px;'>0 resutls</p>";
        }

        $conn->close();
    }
}
?>