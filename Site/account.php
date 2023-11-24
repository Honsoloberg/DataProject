<?php
include("config.php");

class accountInfo extends config{

    private $funds = 0.0;
    private $address = "";

    public function pullInitial(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

        $sql = "SELECT U.Funds, U.Address FROM users as U WHERE U.ID ='" . $_SESSION['uid'] . "'"; 
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        $this->funds = $row['Funds'];

        $this->address = $row['Address'];

        $conn->close();
    }

    public function getFunds(){

        echo "<p style='padding-bottom:10px;'>Current Funds: $" . $this->funds . "</p>";
    }

    public function addFunds(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        
        $sql = "UPDATE users SET Funds = Funds +". $_POST['funds'];

        @$conn->query($sql);

        if($conn->error == ""){
            $this->funds += $_POST['funds'];
        }

        $conn->close();

        header("Location: accountPage.php");
    }

    public function getAddress(){
        echo "<p>Current Delivery Address: " . $this->address . "</p>";
    }
}
?>