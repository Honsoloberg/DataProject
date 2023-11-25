<?php
//Creates session to utilize sesion variables
session_start();
if(isset($_GET['locality']) && $_GET['locality'] != "Oshawa"){
  $_SESSION["err"] = TRUE;
}

//Generates a cookie to store Users provided Address
if(!empty($_GET) && !$_SESSION['err']){
    $name = "ferryLocation";
    $value = "address=". $_GET['ship-address'] . "&" . "city=" . $_GET['locality'] . "&" . "state=" . $_GET['state'] . "&" . "postcode=" . $_GET['postcode'];
    $value = $_GET['ship-address'].":".$_GET['locality'].":".$_GET['state'].":".$_GET['postcode'];
    setcookie($name, $value, time() + (86400*30), "/");
    header("Location: main.php");
}
?>

<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <title>Food Ferry</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,500"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="nav.css">
    <link rel="stylesheet" type="text/css" href="./style.css" />
    <script type="module" src="./address.js"></script>
  </head>
  <body>
    <div>
      <img src="Food_Ferry.png"  alt="Food Ferry" width="175" height="210" style="display:block;margin-left:auto;margin-right:auto;">
      <?php
          //Generates a customised message for User
          if(!empty($_SESSION["uname"])){
            echo "<h3 style='text-align:center;padding-bottom:30px'> Welcome, ". $_SESSION["uname"]."</h3>";
          }else {
            echo "<h3 style='text-align:center;'> Welcome, User</h3>";
          }
      ?>
  </div>
  <div class="navBar">
    <ul class="navList">
        <li class="navItems"><a href="">Home</a></li>
        <li class="navItems"><a href="">Account</a></li>
        <li class="navItems"><a href="">Orders</a></li>
        <li class="navItems" id="loginBut"><a href="">Logout</a></li>
    </ul>
  </div>
  <div>
    <?php
      //Error messgae is displayed when the address provided is not in oshawa
      if($_SESSION["err"]){
        echo "<p style='color:red;text-align:center;'>Please Enter an Oshawa Address</p>";
        $_SESSION["err"] = FALSE;
      }
    ?>
  </div>
      <!--Below form is privided from google maps api and uses their api to generate and validate addresses-->
      <!--address.js provides primary functionality for form-->
    <form id="address-form" action="" method="get" autocomplete="off" style="display:block;margin-left:auto;margin-right:auto;">
      <p class="title">Address form</p>
      <p class="note"><em>* = required field</em></p>
      <label class="full-field">
        <!-- Avoid the word "address" in id, name, or label text to avoid browser autofill from conflicting with Place Autocomplete. Star or comment bug https://crbug.com/587466 to request Chromium to honor autocomplete="off" attribute. -->
        <span class="form-label">Deliver to: *</span>
        <input
          id="ship-address"
          name="ship-address"
          required
          autocomplete="off"
        />
      </label>
      <label class="full-field">
        <span class="form-label">Apartment, unit, suite, or floor #: </span>
        <input id="address2" name="address2" />
      </label>
      <label class="full-field">
        <span class="form-label">City: *</span>
        <input id="locality" name="locality" required />
      </label>
      <label class="slim-field-start">
        <span class="form-label">State/Province: *</span>
        <input id="state" name="state" required />
      </label>
      <label class="slim-field-end" for="postal_code">
        <span class="form-label">Postal code: *</span>
        <input id="postcode" name="postcode" required />
      </label>
      <label class="full-field">
        <span class="form-label">Country/Region: *</span>
        <input id="country" name="country" required />
      </label>
      <button type="submit" class="my-button">Save address</button>


      <input type="reset" value="Clear form" />
    </form>


    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFim3lC8GqJiC1ORCFQI61Ristum0BqQQ&callback=initAutocomplete&libraries=places&v=weekly"
      defer
    ></script>
  </body>
</html>