<?php
include "config.php";
class rDetailsPop extends config{

 // Query 5) Uses nested queries with any of the set operations UNION, to combine items form different restuarants to compare items from to restaurants
 public function popCompareList($restaurantName1,$restaurantName2){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    
    //Queries all items from the current and selected restaurant 
    $sql = "SELECT Items.ID, Items.Iname, Items.Price, Restaurant.Rname
        FROM Items
        LEFT JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Restaurant.Rname = '$restaurantName1'
        UNION
        SELECT Items.ID, Items.Iname, Items.Price, Restaurant.Rname
        FROM Items
        LEFT JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Restaurant.Rname = '$restaurantName2'";
    $result = $conn->query($sql);
    $conn->close();

    //Generates a comparative table for Queried items
    echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
    <tr>
        <th>Item Name</th>
        <th>Price</th>
        <th>Restaurant Name</th>
    </tr>";
   
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
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

// Query 6) Query for user funds 
public function popFundsTable($localID){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    $sql = "SELECT Funds, Fname, Lname, UserName
        FROM Users
        WHERE UserName = '$localID'";
    $result = $conn->query($sql);
    $conn->close();
    echo "<table border='1'>
        <tr>
        <th>Funds</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>User Name/th>
    </tr>";
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["Funds"] . "</td>
                    <td>" . $row["Fname"] . "</td>
                    <td>" . $row["Lname"] . "</td>
                    <td>" . $row["UserName"] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>0 results</td></tr>";
    }
    echo "</table>";
    }



 // Query 7) Query for restaurants address 
 public function popRestAddress($restaurantName){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    //Queries Address for current restaurant view
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
    
        //output address into a table
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
        
        //Queries all items from current restaurant view
        $sql = "SELECT Items.*
        FROM Items
        INNER JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Restaurant.Rname = '$restaurantName'";
        $result = $conn->query($sql);
        $conn->close();
        if ($result->num_rows > 0) {
            // Output data of each row into a display table
            echo "<table style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>";
            echo "<tr><th style='border: 1px solid black;'>Item</th><th style='border: 1px solid black;'>Price</th><th style='border: 1px solid black;'>Description</th</tr>";
        
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='border: 1px solid black;'>" . $row["Iname"] . "</td>";
                echo "<td style='border: 1px solid black;'>$" . $row["Price"] . "</td>";
                echo "<td style='border: 1px solid black;'>" . $row["Decript"] . "</td>";
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
        //Query the least expensive items from a reataurant

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

        //Query the most expensive item from a restaurant
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
    
    public function popSearch($searchTerm, $restaurantName) {
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        //search the database fore selected item
        $sql = "SELECT Items.*
            FROM Items
            INNER JOIN Restaurant ON Items.RID = Restaurant.ID
            WHERE Restaurant.Rname = '$restaurantName' AND Items.Iname LIKE '$searchTerm'";
        
        $result = $conn->query($sql);
    
        $row = $result->fetch_assoc();
        // Initialize $searchResults as an empty array
        $searchResult = array("First");

        //Flag used to clear the item listing
        if($_POST['Clear'] == 5){
            $_SESSION['search'] = NULL;
        }
        //save values to session
        if(isset($_SESSION['search'])){
            $searchResult = $_SESSION['search'];
        }

        if(!array_search($row['Iname'], $searchResult)){
            array_push($searchResult, $row['Iname']);
            $_SESSION['search'] = $searchResult;
        }



        if ($result->num_rows > 0) {
            //Add Items placeholder table for Selected Listing
            $sql = "INSERT INTO building_my_order (UID, Rname, Iname, Quant, Decript, Price) VALUES(
                '{$conn->real_escape_string($_SESSION['uid'])}',
                '{$conn->real_escape_string($restaurantName)}',
                '{$conn->real_escape_string($row['Iname'])}',
                '{$conn->real_escape_string(1)}',
                '{$conn->real_escape_string($row['Decript'])}',
                '{$conn->real_escape_string($row['Price'])}')";

            try {
                $conn->query($sql);
            } catch (\Throwable $th) {
                //update quantities of selected items in listing
                $sql = "UPDATE building_my_order
                        SET Quant = Quant + 1
                        WHERE UID = '" . $_SESSION['uid'] . "' AND Rname = '" . $restaurantName . "' AND Iname = '" . $row['Iname'] . "'";
                $conn->query($sql);
            }
            
        
        $quant[] = NULL;
        
        //save values in session
        if(isset($_SESSION['quant'])){
            $quant = $_SESSION['quant'];
        }
        //Retrive Total quantity of Items in item listing
        foreach ($searchResult as $value){
            $sql = "SELECT Quant
                    FROM building_my_order
                    WHERE UID = '" . $_SESSION['uid'] ."' AND Iname = '" . $value . "'";

            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $items = $result->fetch_assoc();
            }

            if($value == "First"){
                continue;
            }
            foreach($items as $var){
                $quant[$value] = $var;
            }

        }
        $_SESSION['quant'] = $quant;

        //retreive Item ID's
        $sql = "SELECT * FROM items AS I, Restaurant AS R WHERE I.RID = R.ID AND R.Rname = '" . $restaurantName . "'";

        $result = $conn->query($sql);

            while($row = $result->fetch_assoc()){
                if(array_search($row['Iname'], $searchResult) >= 1 && array_search($row['Iname'], $searchResult) != FALSE){
                    $remove = "<form method='post' action=''>
                    <input type='hidden' name='order' value='". $row["Iname"] ."'>
                    <button type=submit>Remove</button>
                    </form>";

                    echo "<tr>";

                    echo "<td>" . $row['Iname'] . "</td>
                        <td>" . $quant[$row['Iname']] . "</td>
                        <td>" . $row['Price'] . "</td>
                        <td>" . $remove . "</td>";

                    echo "</tr>";
                }
            }
        $conn->close();
    }
}
    //Generate an Order
    public function genOrder($restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        //assign a driver for the order
        $sql = "SELECT ID FROM driver ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $driver = $row['ID'];

        //Retrive all items in item listing
        $sql = "SELECT * FROM building_my_order WHERE UID = '" . $_SESSION['uid'] . "'";
        $items = $conn->query($sql);
        $BUILDitems = array();

        while($row = $items->fetch_assoc()){
            array_push($BUILDitems, $row);
        }

        $totalPrice = 0;

        //calculate total price
        while($row = $items->fetch_assoc()){
            $var = (double)$row['Price'] * (int)$rowp['Quant'];
            $totalPrice += $var;
        }   

        //Retrieve restaurant ID's
        $sql = "SELECT ID FROM restaurant WHERE Rname = '" . $restaurantName ."'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $RID = $row['ID'];

        //Generate an reference ID for the order
        $newID = $this->createOrderID();

        //Insert order record into database
        $sql = "INSERT INTO orders (ID, TotalPrice, RID, DID, UID) VALUES (
            '{$conn->real_escape_string($newID)}',
            '{$conn->real_escape_string($totalPrice)}',
            '{$conn->real_escape_string($RID)}',
            '{$conn->real_escape_string($driver)}',
            '{$conn->real_escape_string($_SESSION['uid'])}')";
        try {
            $conn->query($sql);
        } catch(\Throwable $th){
            echo "Failed to generate order";
            echo $conn->error;
        }

        //create references to restaurant items
        $sql = "SELECT * FROM items WHERE RID = '" . $RID . "'";

        $items = $conn->query($sql);

        $IID = array();

        while($row = $items->fetch_assoc()){
            foreach($BUILDitems as $BI){
                if($BI['Iname'] == $row['Iname']){
                    array_push($IID, $row['ID']);
                }
            }
        }

        $i = 0;
        //insert item references to the Order
        foreach($BUILDitems as $BI){
            $sql = "INSERT INTO O_Items (O_ID, Item_ID, Quant) VALUES (
                '{$conn->real_escape_string($newID)}',
                '{$conn->real_escape_string($IID[$i])}',
                '{$conn->real_escape_string($BI['Quant'])}')";
            $conn->query($sql);
            $i++;
        }

        $_SESSION['search'] = NULL;
        $_SESSION['quant'] = NULL;    
        header("Location: restaurantDetails.php");           
    }

    private function createOrderID(){
		//Creates connection to database
		$connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

		$sql = "SELECT ID FROM orders";
		$result = $connection->query($sql);

		while($row = $result->fetch_assoc()){
			$uIDS[] = $row['ID'];
		}

		$newID = rand(0, 999999);

		while(TRUE){
			$flag = FALSE;
			foreach($uIDS as $check){
				if($newID == $check){
					$newID = rand(0, 999999);
					$flag = TRUE;
					break;
				}
			}
			if(!$flag){
				break;
			}
		}

        return $newID;
    }
} 
?>
