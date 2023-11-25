<?php
include("config.php");

class accountInfo extends config{

    private $funds = 0.0;
    private $address = "";

    //Pull address and funds for your account
    public function pullInitial(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        
        //Query to pull The values of Funds and Address for the current user
        $sql = "SELECT U.Funds, U.Address FROM users as U WHERE U.ID ='" . $_SESSION['uid'] . "'"; 
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        $this->funds = $row['Funds'];

        $this->address = $row['Address'];

        $conn->close();
    }

    //Output an HTML element with the users funds
    public function getFunds(){

        echo "<p style='padding-bottom:10px;text-align:center;'>Current Funds: $" . $this->funds . "</p>";
    }

    //Add funds from user input
    public function addFunds(){

        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        
        //Add the funds to the users account in the database
        $sql = "UPDATE users SET Funds = Funds +". $_POST['funds'];

        @$conn->query($sql);

        //If no error is thrown, display the change to the users funds
        if($conn->error == ""){
            $this->funds += $_POST['funds'];
        }

        $conn->close();

        header("Location: accountPage.php");
    }

    //Output an HTML element with the users address
    public function getAddress(){
        echo "<p style='text-align:center;'>Current Delivery Address: href='locationMap.php'" . $this->address . "</p>";
    }
}
?>