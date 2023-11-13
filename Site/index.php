<?php
if(!empty($_SESSION["uname"])){
    header("Location: ???????"); //Fill with the primary page
}
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <title>Food Ferry</title>

    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="header">
        <h2 class="headerTitle">Food Ferry</h2>
    </div>
    <div class="mainContainer">

        <h1 style="text-align: center;">Login</h1>

        <form action="" method="post">
            <div class="imgContainer">
                <img src="loginIcon.png" alt="Avatar" class="avatar">
            </div>

            <div class="bodyContainer">
                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="uname" required>

                <label for="upass"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="upass" required>
                
                <p id="usererror" style="text-color: red;"></p>
            
                <?php
                if(!empty($_POST)){
                    if($_SESSION["err"]){
                        echo '<script type="text/javascript"> document.getElementById("usererror").innerHTML = "Username or Password is not correct." </script>';
                        echo '<script type="text/javascript"> document.getElementById("usererror").style.color = "red" </script>';
                    }
                }
                ?>

                <button type="submit">Login</button>
            </div>

            <div class="bodyContainer" style="background-color: #f1f1f1; border-radius: 2%;">
                <a href="index.php"><button type="button" class="cancelbtn" >Cancel</button></a>
                <span class="psw" style="padding-top: 6px;">Don't Have an Account? <a href="createAccount.php">Create One!</a></span>
            </div>

        </form>
    </div>
</body>
</html>