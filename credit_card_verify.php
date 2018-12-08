<?php
include 'db_functions.php';

  $conn = dbConnect();

  $name = $_POST["nameOnCard"];
  $type = $_POST["cardType"];

  $query = "INSERT INTO Credit_Card (CCType, CCName, CCSecurityCode, CCNumber, CCMonth, CCYear)
  VALUES ('$type' , '$name' , " . $_POST["securityCode"] . ", " . $_POST["cardNumber"] . ", " . $_POST["monthExpire"] . ", " . $_POST["yearExpire"] . ")";

if(mysqli_query($conn, $query))
{
  echo "<p>Credit Card Added Successfully!</p>";
  // Redirect to this page if successfully inserted data
  $ccid = mysqli_fetch_array(mysqli_query($conn, "SELECT LAST_INSERT_ID()"))[0];
  echo $ccid;
}
else
{
  echo "ERROR: Could not execute $query." . mysqli_error($conn);
}

  $queryTwo = "INSERT INTO Payment_Info (CCID, FanID, StreetNum, StreetName, City, Province)
          VALUES ({$ccid}, 1, '" . $_POST["piStreetNum"] . "', '" . $_POST["piStreetName"] . "', '" . $_POST["piCity"] . "', '" . $_POST["piProvince"] . "')";

  if(mysqli_query($conn, $queryTwo))
  {
    echo "<p>Credit Card Added Successfully!</p>";
    // Redirect to this page if successfully inserted data
    header('Location: account_fan.php');

  }
  else
  {
    echo "ERROR: Could not execute $query." . mysqli_error($conn);
  }
?>
