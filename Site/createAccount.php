<?php
include 'dlogin.php';

//Create object to link functionality
$account = new dlogin();
if(!empty($_POST)){
    $account->createUser();
}
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>MyKia</title>

    <link rel="stylesheet" href="login.css">

</head>
<body>

    <div class="header">
        <h2 class="headerTitle" style="padding-right: 20px;">Food Ferry</h2>
    </div>

    <div class="mainContainer">

        <h1 style="text-align: center;">Create an Account</h1>
        <!--Primary form to creates new users-->
        <form action="" method="post">
            <div class="imgContainer">
                <img src="loginIcon.png" alt="Avatar" class="avatar">
            </div>

            <div class="bodyContainer">

                <label for="fname"><b>First Name</b></label>
                <input type="text" placeholder="Enter First Name" name="Fname" required>

                <label for="lname"><b>Last Name</b></label>
                <input type="text" placeholder="Enter last Name" name="Lname" required>

                <label for="email"><b>Email Address</b></label>
                <input type="text" placeholder="Enter Email Address" name="Email" required>

                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="Uname" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="Upass" required>

                <label for="psw"><b>Address</b></label>
                <input type="text" placeholder="Enter Password" name="Address" required>

                <label for="psw"><b>Birthday</b></label>
                <input type="text" placeholder="Enter Password" name="Birthday" required>

                <button type="submit">Create Account</button>

            </div>

            <div class="bodyContainer" style="background-color: #f1f1f1; border-radius: 2%;">
                <a href="login.php"><button type="button" class="cancelbtn" >Cancel</button></a>
            </div>

        </form>

    </div>

    <div class="footerBar">
    <div class="insideFooterTitle">
                <h2>Find Page</h2>
        </div>
        <div class="insideFooter">
        <p><a href="index.php">Home</a></p>
        <p><a href="login.php">Login</a></p>
        <p><a href="contact.php">Contact Us</a></p>
        <p>Food Ferry &copy</p>
        <p>Copyright LRNJ 2023<span>&copy;</span></p>
        </div>
    </div>

</body>
</html>