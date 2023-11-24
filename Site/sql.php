


<div>
    <form method="get">
        <label for="search">Search By Keyword:</label>
        <input type='hidden' name="style" value= "<?php echo $style ?>">
        <input type="text" id="search" name="query" placeholder="What are you hungry for?">
        <button type="submit">Search</button>
        
    </form>

    <?php

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
    $searchTerm = $conn->real_escape_string($searchTerm);
    

    if (isset($_GET['sort'])) {
        $sortOrder = $_GET['sort'];
    }



    $sql = "SELECT Items.*
            FROM Items
            INNER JOIN Restaurant ON Items.RID = Restaurant.ID
            WHERE Restaurant.Rname = '$restaurantName' AND Items.Iname LIKE '%$searchTerm%'
            ORDER BY Items.Price $sortOrder";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Add each result to the searchResults array
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = [
                'name' => $row["Iname"],
                'price' => $row["Price"]
            ];
        }
    }
}
?>

<?php

$sql = "SELECT R.Rname, I.Iname AS ItemName, I.Price AS MaxPrice
        FROM Restaurant R
        JOIN Items I ON R.ID = I.RID
        WHERE R.Rname = '$restaurantName'
        AND I.Price = (SELECT MAX(Price) FROM Items WHERE RID = R.ID)";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data in a table
    echo "<table border='1'>
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




// Query 4) View 4: Uses a FULL JOIN 
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
        LEFT JOIN Users ON Orders.UID = Users.ID;
";

$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver and Orders Data</title>
</head>
<body>

<h2>Driver and Orders Data</h2>

<table border='1'>
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
    </tr>

    <?php
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
    ?>
</table>


<h2>User Data</h2>


// Query 6 Query for user funds
<?php
$localID = $_SESSION["uname"];

$sql = "SELECT Funds, Fname, Lname, UserName
        FROM Users
        WHERE UserName = '$localID'";

$result = $conn->query($sql);
?>

<table border='1'>
    <tr>
        <th>Funds</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>User Name/th>
    </tr>
    <?php
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
    ?>
</table>

<div>
    <p>Sort by Price:</p>
    <form method='get'>
    <input type='hidden' name="style" value="<?php echo $style ?>">
    <input type='hidden' name="query" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
    <button type='submit' name='sort' value='ASC'>Lowest to Highest</button>
    <button type='submit' name='sort' value='DESC'>Highest to Lowest</button>
    </form>


</div>

<table border='1'>
    <tr><th>Item</th><th>Price</th></tr>
    <?php foreach ($searchResults as $result) { ?>
        <tr>
            <td><?php echo $result['name']; ?></td>
            <td>$<?php echo $result['price']; ?></td>
        </tr>
    <?php } ?>
</table>
           <form method='get'>
            <input type='hidden' name='style' value= '<?php echo $style ?>'>
            <button type='submit'>Clear Results</button>
            </form>

</div>

<h2>Active Orders</h2>

<table border="1">
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
    <tbody id="orderTableBody">
    <?php
    // Check if $_SESSION["uname"] is set and not empty before using it
    if (isset($_SESSION["uname"]) && !empty($_SESSION["uname"])) {
        // Use the value stored in $_SESSION["uname"] as the username variable
        $usernameVariable = $_SESSION["uname"];

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

        // Check if there are results
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
    } else {
        // Handle the case where $_SESSION["uname"] is not set or empty
        echo '<tr><td colspan="5">Invalid username</td></tr>';
    }

    // Close the database connection

    ?>
</tbody>
</table>


<!-- You can add a button or link to trigger the addition of a new row -->
<button onclick="addNewRow()">Add Item to Oder</button>

<script>
function addNewRow() {
    // You can add logic here to fetch data or get input from the user
    var newRowData = [' ', ' ', '', 0,'',0];

    var tableBody = document.getElementById('orderTableBody');
    var newRow = document.createElement('tr');

    newRowData.forEach(function(value) {
        var cell = document.createElement('td');
        cell.textContent = value;
        newRow.appendChild(cell);
    });

    tableBody.appendChild(newRow);
}
</script>



</body>
</html>
