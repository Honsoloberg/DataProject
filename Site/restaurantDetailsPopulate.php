<?php
include "config.php";
class rDetailsPop extends config{




 // Query 2) Uses nested queries with the ANY or ALL operator and uses a GROUP BY, to show user top prices at all restaurants 
 public function popTopList(){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    $sql = "SELECT R.Rname, I.Iname, I.Price, I.Decript
        FROM Restaurant R
        JOIN Items I ON R.ID = I.RID
        WHERE I.Price = ALL (
            SELECT MAX(Price)
            FROM Items
            WHERE RID = R.ID
            GROUP BY RID
        );";

    $result = $conn->query($sql);
    $conn->close();
    if ($result->num_rows > 0) {
    // Output data in a table
    echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
            <tr>
                <th>Restaurant Name</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Description</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["Rname"] . "</td>
                <td>" . $row["Iname"] . "</td>
                <td>" . $row["Price"] . "</td>
                <td>" . $row["Decript"] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}
}

 // Query 5) Uses nested queries with any of the set operations UNION, to combine items form different restuarants to compare items from to restaurants
 public function popCompareList($restaurantName1,$restaurantName2){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    $sql = "SELECT Items.ID, Items.Iname, Items.Price, Restaurant.Rname
        FROM Items
        LEFT JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Items.RID = 253617
        UNION
        SELECT Items.ID, Items.Iname, Items.Price, Restaurant.Rname
        FROM Items
        LEFT JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Items.RID = 266291";
    $result = $conn->query($sql);
    $conn->close();

    echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
    <tr>
        <th>Item ID</th>
        <th>Item Name</th>
        <th>Price</th>
        <th>Restaurant Name</th>
    </tr>";
   
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["ID"] . "</td>
                    <td>" . $row["Iname"] . "</td>
                    <td>" . $row["Price"] . "</td>
                    <td>" . $row["Rname"] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>0 results</td></tr>";
    }
 
    echo "</table>";
    }

 // Query 7) Query for restaurants address 
 public function popRestAddress($restaurantName){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    $sql = "SELECT R.Rname, R.Address
        FROM Restaurant R
        WHERE R.Rname = '$restaurantName'";

    $result = $conn->query($sql);
    $conn->close();
    if ($result->num_rows > 0) {
        // Output data in a table
        echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
                <tr>
                    <th>Restaurant Name</th>
                    <th>Address</th>
                </tr>";
    
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["Rname"] . "</td>
                    <td>" . $row["Address"] . "</td>
                  </tr>";
        }
    
        echo "</table>";
    } else {
        echo "0 results";
    }
    
}

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

    // Query 9) Query for least expensive item at each restaurant 
    public function popLeastExpensive($restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        $sql = "SELECT R.Rname, I.Iname AS ItemName, I.Price AS MinPrice
        FROM Restaurant R
        JOIN Items I ON R.ID = I.RID
        WHERE R.Rname = '$restaurantName'
        AND I.Price = (SELECT MIN(Price) FROM Items WHERE RID = R.ID);";

        $result = $conn->query($sql);
        $conn->close();

        if ($result->num_rows > 0) {
            // Output data in a table
            echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
                    <tr>
                        <th>Restaurant Name</th>
                        <th>Item Name</th>
                        <th>Min Price</th>
                    </tr>";
        
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Rname"] . "</td>
                        <td>" . $row["ItemName"] . "</td>
                        <td>" . $row["MinPrice"] . "</td>
                      </tr>";
            }
        
            echo "</table>";
        } else {
            echo "0 results";
        }
    }

    //Query 10) Query for the least expensive item at current restaurant
    public function popMostExpensive($restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        $sql = "SELECT R.Rname, I.Iname AS ItemName, I.Price AS MaxPrice
        FROM Restaurant R
        JOIN Items I ON R.ID = I.RID
        WHERE R.Rname = '$restaurantName'
        AND I.Price = (SELECT MAX(Price) FROM Items WHERE RID = R.ID)";

        $result = $conn->query($sql);
        $conn->close();
        if ($result->num_rows > 0) {
            // Output data in a table
            echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
                    <tr>
                        <th>Restaurant Name</th>
                        <th>Item Name</th>
                        <th>Max Price</th>
                    </tr>";
        
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Rname"] . "</td>
                        <td>" . $row["ItemName"] . "</td>
                        <td>" . $row["MaxPrice"] . "</td>
                      </tr>";
            }
        
            echo "</table>";
        } else {
            echo "0 results";
        }
        

    }







    
}
?>