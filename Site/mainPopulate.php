<?php
include "config.php";
class Mpopulate extends config{

    public function populate(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT Rname FROM restaurant";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<form method='get' action='restaurantDetails.php'>";

            while ($row = $result->fetch_assoc()) {
                $imageName = $row["Rname"] . ".png";
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

    public function popWait(){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT r.Rname AS RestaurantName, COUNT(o.ID) AS OrderCount, AVG(o.TotalPrice) AS AvgTotalPrice
        FROM Orders o
        JOIN Restaurant r ON o.RID = r.ID
        WHERE (
            SELECT AVG(i.Price)
            FROM Items i
            WHERE i.RID = o.RID
        ) < ANY (
            SELECT io.Price
            FROM O_Items oi
            JOIN Items io ON oi.Item_ID = io.ID
            WHERE oi.O_ID = o.ID
        )
        GROUP BY r.Rname;";
    
    
        $result = $conn->query($sql);
        $conn->close();
        
        if ($result->num_rows > 0) {
            // Output data in a table
            echo "<table border='1' style='margin-left:auto;margin-right:auto;text-align: center'>
                    <tr>
                        <th>Restaurant Name</th>
                        <th>Order Count</th>
                        <th>Average Total Price</th>
                    </tr>";
        
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["RestaurantName"] . "</td>
                        <td>" . $row["OrderCount"] . "</td>
                        <td>" . "$" . number_format($row["AvgTotalPrice"], 2, '.'. '') . "</td>
                    </tr>";
            }
        
            echo "</table>";
        } else {
            echo "0 results";
        }  
    }

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














}
?>