<?php
include 'config.php';
class dlogin extends config{

		public function login(){
			//Creates connection to database
			$connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
			if($connection->connect_error){
				die("Connection failed: ".$connection->connect_error);
			}

			$uname = $_POST['uname'];
			$upass = $_POST['upass'];
			//Creates session for session variables
			session_start();
			$_SESSION["err"] = false;
			
			//Select query for databse from user table
			$sql = "SELECT * FROM Users WHERE UserName = '$uname'";
			$result = $connection->query($sql);

			//Test weather user exists
			if($result->num_rows > 0){
				//exists
			}else{
				$_SESSION["err"] = true;
				return 0;
			}

			//Test if password is correct for user
			$row = $result->fetch_assoc();
			if($row['Upass'] == $upass){
				$_SESSION["uid"] = $row["ID"];
				$_SESSION["uname"] = $row["UserName"];
			}else{
				$_SESSION["err"] = true;
				return 0;
			}

			if(empty($_COOKIE["ferryLocation"])){
				$connection->close();
				header("Location: locationMap.php");
				return 0;
			} else {
				
			}

			$connection->close();
			//redirect to different page after login
			header("Location: main.php");
		}

		public function createUser(){
		//Creates connection to database
		$connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

		$sql = "SELECT ID FROM Users";
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

		//Insert query for database to user table
		$sql = "INSERT INTO Users (ID, Fname, Lname, Username, Upass, Funds, Address, Birthday, Email) VALUES (
			'{$connection->real_escape_string($newID)}',
			'{$connection->real_escape_string($_POST['Fname'])}',
			'{$connection->real_escape_string($_POST['Lname'])}',
			'{$connection->real_escape_string($_POST['Uname'])}',
			'{$connection->real_escape_string($_POST['Upass'])}',
			'{$connection->real_escape_string(0)}',
			'{$connection->real_escape_string($_POST['Address'])}',
			'{$connection->real_escape_string($_POST['Birthday'])}',
			'{$connection->real_escape_string($_POST['Email'])}')";
			$insert = $connection->query($sql);
			$connection->close();

			//redirect to login page after account is created
			header("Location: index.php");
		}
	}
?>