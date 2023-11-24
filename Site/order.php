<?php
include "config.php";
class order extends config{

    public function populate(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT Driver.Fname AS Fname, Driver.Lname AS Lname, Driver.CarModel AS Car, Driver.Plate AS Plate,
                Orders.ID AS OrderID, Orders.TotalPrice AS Price, Restaurant.Rname AS RestaurantName
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
                LEFT JOIN Users ON Orders.UID = Users.ID
                WHERE Users.ID = '" . $_SESSION["uid"] . "'";
        
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            $button = "<form method='post' action=''>
            <input type='hidden' name='order' value='". $row["OrderID"] ."'>
            <button type=submit>Cancel</button>
            </form>"
            
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "
                <td>" . $row["OrderID"] . "</td>
                <td>" . $row["RestaurantName"] . "</td>
                <td>" . $row["Price"] . "</td>
                <td>" . $row["Fname"] . " " . $row["Lname"] . "</td>
                <td>" . $row["Car"] . "</td>
                <td>" . $row["Plate"] . "</td>
                <td>" . $button . "</td>
                ";
                echo "</tr>";
            }
        }
    }
}
?>