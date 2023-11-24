<?php
include "config.php";
class rDetailsPop extends config{

// Query 1) Computes a join of at least 3 tables:
public function popOrderList($usernameVariable){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    $sql = "SELECT
    O_Items.O_ID AS Order_Number,
    O_Items.Quant AS Quantity,
    Items.Iname AS ItemName,
    Items.Decript AS Descriptions,
    Items.Price,
    Restaurant.Rname AS RestaurantName

    FROM
        O_Items
    JOIN
        Items ON O_Items.Item_ID = Items.ID
    JOIN
        Orders ON O_Items.O_ID = Orders.ID
    JOIN
        Restaurant ON Orders.RID = Restaurant.ID
    JOIN
        Users ON Orders.UID = Users.ID
    WHERE
        Users.UserName = '$usernameVariable'";

    $result = $conn->query($sql);
    $conn->close();
    
    echo "<h2 style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%;'></h2>";
    echo "<table border='1'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>
    <thead>
        <tr>

            <th>Order Number</th>
            <th>Restaurant</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody id='orderTableBody'style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['Order_Number'] . '</td>';
            echo '<td>' . $row['RestaurantName'] . '</td>';
            echo '<td>' . $row['ItemName'] . '</td>';
            echo '<td>' . $row['Quantity'] . '</td>';
            echo '<td>' . $row['Descriptions'] . '</td>';
            echo '<td>' . $row['Price'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No active orders found</td></tr>';
    }
    }



 

 // Query 5) Uses nested queries with any of the set operations UNION, to combine items form different restuarants to compare items from to restaurants
 public function popCompareList($restaurantName1,$restaurantName2){
    $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    
    
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
    
    public function popSearch($searchTerm, $restaurantName) {
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        $sql = "SELECT Items.*
            FROM Items
            INNER JOIN Restaurant ON Items.RID = Restaurant.ID
            WHERE Restaurant.Rname = '$restaurantName' AND Items.Iname LIKE '$searchTerm'";
        
        $result = $conn->query($sql);
    
        $row = $result->fetch_assoc();
        // Initialize $searchResults as an empty array
        $searchResult = array("First");

        if(isset($_GET['Clear'])){
            $_SESSION['search'] = NULL;
        }
        if(isset($_SESSION['search'])){
            $searchResult = $_SESSION['search'];
        }

        if(!array_search($row['Iname'], $searchResult)){
            array_push($searchResult, $row['Iname']);
            $_SESSION['search'] = $searchResult;
        }


        if ($result->num_rows > 0) {

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
                $sql = "UPDATE building_my_order
                        SET Quant = Quant + 1
                        WHERE UID = '" . $_SESSION['uid'] . "' AND Rname = '" . $restaurantName . "' AND Iname = '" . $row['Iname'] . "'";
                $conn->query($sql);
            }
            
        
        $quant[] = NULL;

        if(isset($_SESSION['quant'])){
            $quant = $_SESSION['quant'];
        }
        foreach ($searchResult as $value){
            $sql = "SELECT count(Iname)
                    FROM building_my_order
                    WHERE UID = '" . $_SESSION['uid'] ."' AND Iname = '" . $value . "'";

            $result = $conn->query($sql);
            $items = $result->fetch_assoc();

            foreach($items as $var){
                if(isset($quant[$value])){
                    $quant[$value] += $var;
                } else {
                    $quant[$value] = $var;
                }
            }

        }
        $_SESSION['quant'] = $quant;

        $sql = "SELECT * FROM items AS I, Restaurant AS R WHERE I.RID = R.ID AND R.Rname = '" . $restaurantName . "'";

        $result = $conn->query($sql);

        for($i = 0; $i<count($searchResult); $i++){
            while($row = $result->fetch_assoc()){
                if(array_search($row['Iname'], $searchResult) >= 0 && array_search($row['Iname'], $searchResult) != FALSE){
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
        }
        $conn->close();
    }
}

    public function genOrder($restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT ID FROM driver ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $driver = $row['ID'];

        $sql = "SELECT * FROM building_my_order WHERE UID = '" . $_SESSION['uid'] . "'";
        $items = $conn->query($sql);
        $BUILDitems = array();

        while($row = $items->fetch_assoc()){
            array_push($BUILDitems, $row);
        }

        $totalPrice = 0;

        while($row = $items->fetch_assoc()){
            $var = (double)$row['Price'] * (int)$rowp['Quant'];
            $totalPrice += $var;
        }

        $sql = "SELECT ID FROM restaurant WHERE Rname = '" . $restaurantName ."'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $RID = $row['ID'];

        $newID = $this->createOrderID();

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

        $sql = "SELECT ID FROM orders
                WHERE TotalPrice = '" . $totalPrice . "' AND RID = '" . $RID . "' AND DID = '" . $driver . "' AND UID = '" . $_SESSION['uid'] . "'";
        
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $OID = $row['ID'];

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
        foreach($BUILDitems as $BI){
            $sql = "INSERT INTO O_Items (O_ID, Item_ID, Quant) VALUES (
                '{$conn->real_escape_string($OID)}',
                '{$conn->real_escape_string($IID[$i])}',
                '{$conn->real_escape_string($BI['Quant'])}')";
            $conn->query($sql);
            $i++;
        }
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
