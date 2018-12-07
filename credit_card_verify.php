<?php
include 'db_functions.php';

  $conn = dbConnect();

  $name = $_POST["nameOnCard"];
  $type = $_POST["cardType"];

  $query = "INSERT INTO Credit_Card (CCType, CCName, CCSecurityCode, CCNumber, CCMonth, CCYear)
  VALUES ('$type' , '$name' , " . $_POST["securityCode"] . ", " . $_POST["cardNumber"] . ", " . $_POST["monthExpire"] . ", " . $_POST["yearExpire"] . ")";

  //$result = mysqli_query($connection, $query);

  if(mysqli_query($conn, $query))
  {
    echo "<p>Credit Card Added Successfully!</p>";
    // Redirect to this page if successfully inserted data
    header('Location: credit_card_page.php');

  }
  else
  {
    echo "ERROR: Could not execute $query." . mysqli_error($conn);
  }
?>