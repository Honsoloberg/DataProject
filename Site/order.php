<?php
include "config.php";
class order extends config{

    public function populate(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT  Driver.ID, Driver.Fname AS Fname, Driver.Lname AS Lname, Driver.CarModel AS Car, Driver.Plate AS Plate, Driver.Insurance,
                Orders.ID AS OrderID, Orders.TotalPrice AS Price, Restaurant.Rname AS RestaurantName, Users.Fname AS UserName
                FROM Driver
                LEFT JOIN Orders ON Driver.ID = Orders.DID
                LEFT JOIN Restaurant ON Orders.RID = Restaurant.ID
                LEFT JOIN Users ON Orders.UID = Users.ID
                WHERE Users.ID = '" . $_SESSION["uid"] . "'";
        
        $result = $conn->query($sql);

        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){
                $button = "<form method='post' action=''>
                <input type='hidden' name='order' value='". $row["OrderID"] ."'>
                <button type=submit>Cancel</button>
                </form>";

                echo "<tr>";
                echo "
                <td>" . $row["OrderID"] . "</td>
                <td>" . $row["RestaurantName"] . "</td>
                <td>" . $row["Price"] . "</td>
                <td>" . $row["Fname"] . " " . $row["Lname"] . "</td>
                <td>" . $row["Car"] . "</td>
                <td>" . $row["Plate"] . "</td>
                <td>" . $row['UserName'] . "</td>
                ";
                echo "</tr>";
            }
        }
    }
}
?>