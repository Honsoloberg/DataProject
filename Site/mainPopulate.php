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
                        <td>" . $row["AvgTotalPrice"] . "</td>
                    </tr>";
            }
        
            echo "</table>";
        } else {
            echo "0 results";
        }
        
            
    }

    public function popWhosDriverMyOrder(){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
  
    $sql = "SELECT Driver.ID, Driver.Fname, Driver.Lname, Driver.CarModel, Driver.Plate, Driver.Insurance,
           Orders.ID AS OrderID, Orders.TotalPrice, Restaurant.Rname AS RestaurantName, Users.Fname AS UserName
        FROM Driver
        LEFT JOIN Orders ON Driver.ID = Orders.DID
        LEFT JOIN Restaurant ON Orders.RID = Restaurant.ID
        LEFT JOIN Users ON Orders.UID = Users.ID
        UNION
        SELECT Driver.ID, Driver.Fname, Driver.Lname, Driver.CarModel, Driver.Plate, Driver.Insurance,
            Orders.ID AS OrderID, Orders.TotalPrice, Restaurant.Rname AS RestaurantName, Users.Fname AS UserName
        FROM Driver
        RIGHT JOIN Orders ON Driver.ID = Orders.DID
        LEFT JOIN Restaurant ON Orders.RID = Restaurant.ID
        LEFT JOIN Users ON Orders.UID = Users.ID;";


    $result = $conn->query($sql);
    $conn->close();

    echo "
    <table border='1' style='margin-left:auto;margin-right:auto;text-align: center'>
    <tr>
        <th>Driver ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Car Model</th>
        <th>Plate</th>
        <th>Insurance</th>
        <th>Order ID</th>
        <th>Total Price</th>
        <th>Restaurant Name</th>
        <th>User Name</th>
    </tr>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["ID"] . "</td>
                    <td>" . $row["Fname"] . "</td>
                    <td>" . $row["Lname"] . "</td>
                    <td>" . $row["CarModel"] . "</td>
                    <td>" . $row["Plate"] . "</td>
                    <td>" . $row["Insurance"] . "</td>
                    <td>" . $row["OrderID"] . "</td>
                    <td>" . $row["TotalPrice"] . "</td>
                    <td>" . $row["RestaurantName"] . "</td>
                    <td>" . $row["UserName"] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>0 results</td></tr>";
    }
    
   echo "</table>";

}
}?>