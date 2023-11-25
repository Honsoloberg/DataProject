<?php
include "config.php";
class order extends config{

    //populates Order Table
    public function populate(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        //Queries all information about an order for a user
        $sql = "SELECT  Driver.ID, Driver.Fname AS Fname, Driver.Lname AS Lname, Driver.CarModel AS Car, Driver.Plate AS Plate, Driver.Insurance,
                Orders.ID AS OrderID, Orders.TotalPrice AS Price, Restaurant.Rname AS RestaurantName, Users.Fname AS UserName
                FROM Driver
                LEFT JOIN Orders ON Driver.ID = Orders.DID
                LEFT JOIN Restaurant ON Orders.RID = Restaurant.ID
                LEFT JOIN Users ON Orders.UID = Users.ID
                WHERE Users.ID = '" . $_SESSION["uid"] . "'";
        
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            //Generates a table to output data
            while($row = $result->fetch_assoc()){
                //cancellation button to cancel each order
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
                <td>" . $button . "</td>
                ";
                echo "</tr>";
            }
        }
        $conn->close();
    }

    //Cancels an order, provides functionality to cancellation buttons
    public function cancel($id){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        //deletes order from orders table, Database cascades changes
        $sql = "DELETE FROM orders WHERE ID = '" . $id . "'";

        @$conn->query($sql);

        if($conn->error != ""){
            echo "<p style='text-align:center;color:red;'>Error: For some reason that order couldn't be deleted</p>";
        }

        $conn->close();
    }
}
?>