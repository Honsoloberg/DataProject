<?php
class clearOrder extends config{
    public function clearOrder(){
        $conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        
        //Deletes temparary row used to create orders
        $sql = "DELETE FROM building_my_order WHERE UID = '" . $_SESSION['uid'] . "'";

        $conn->query($sql);
    }
}

?>