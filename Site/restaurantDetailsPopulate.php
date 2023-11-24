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
    
    public function popSearch($searchTerm,$restaurantName){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        $sql = "SELECT Items.*
        FROM Items
        INNER JOIN Restaurant ON Items.RID = Restaurant.ID
        WHERE Restaurant.Rname = '$restaurantName' AND Items.Iname LIKE '$searchTerm'";
        
        $result = $conn->query($sql);
        $conn->close();
        if ($result->num_rows > 0) {
            // Add each result to the searchResults array
            if(!empty($_GET["Clear"])){
                unset($_SESSION['search']);
                echo "qwerqwer";
            }
            if(isset($_SESSION['search'])){
                $searchResults = $_SESSION['search'];
            }
            if($_GET['query'] != ""){
                while ($row = $result->fetch_assoc()) {
                    $searchResults[] = [
                        'name' => $row["Iname"],
                        'price' => $row["Price"]
                    ];
                }
                $_SESSION['search'] = $searchResults;
            }
        }

        
        echo "<table border='1' style='margin: 0 auto; text-align: center; border-collapse: collapse; width: 30%; border: 1px solid black;'>";
        echo "<tr><th>Item</th><th>Price</th></tr>";
        foreach ($searchResults as $result) { 
            echo"<tr>
                <td>" .$result['name']."</td>
                <td>". $result['price']."</td>
            </tr>
        }
    </table>";
    
    
  
    }
 }

}
   


  ?>
