<?php
include "config.php";
class rDetailsPop extends config{



// Query 8) Query to retrieve items based on the provided restaurant name
    public function popAllItems($restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        
        $sql = "SELECT Items.*
        FROM Items
        INNER JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Restaurant.Rname = '$restaurantName'";
        $result = $conn->query($sql);
        $conn->close();
        if ($result->num_rows > 0) {
            // Output data of each row
            echo "<table style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>";
            echo "<tr><th style='border: 1px solid black;'>Item</th><th style='border: 1px solid black;'>Price</th></tr>";
        
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='border: 1px solid black;'>" . $row["Iname"] . "</td>";
                echo "<td style='border: 1px solid black;'>$" . $row["Price"] . "</td>";
                echo "</tr>";
            }
        
            echo "</table>";
        } else {
            echo "No items found for the provided restaurant name.";
        }
        
        
        

    }

    // Query 9) Query for most expensive item at each restaurant 
    public function popMostExpensive($restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        $sql = "";

        $result = $conn->query($sql);
        $conn->close();
    }







    
}
?>