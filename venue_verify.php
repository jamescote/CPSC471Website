<?php

    session_start();

?>

<?php
include 'db_functions.php';

  $conn = dbConnect();

  $name = $_POST["venName"];
  $streetNum = $_POST["venStreetNum"];
  $streetName = $_POST["venStreetName"];
  $city = $_POST["venCity"];
  $province = $_POST["venProvince"];
  $capacity = $_POST["venCapacity"];
  
  $query = "INSERT INTO Venue (Name, StreetNum, StreetName, City, Province, Capacity)
  VALUES ('$name' , '$streetNum' , '$streetName' , '$city', '$province', '$capacity')";

  //$result = mysqli_query($connection, $query);

  if(mysqli_query($conn, $query))
  {
    echo "<p>Venue Added Successfully!</p>";

  }
  else
  {
    echo "ERROR: Could not execute $query." . mysqli_error($conn);
  }

  $eID = $_POST["eventID"];
  
  $queryTwo = "INSERT INTO Event_Venues (VenueName, EventID)
  VALUES ('$name' , '$eID')";

  //$result = mysqli_query($connection, $query);

  if(mysqli_query($conn, $queryTwo))
  {
    echo "<p>Event_Venues Added Successfully!</p>";
    // Redirect to this page if successfully inserted data
    header('Location: event_page.php');

  }
  else
  {
    echo "event ID: $eID ERROR: Could not execute $queryTwo." . mysqli_error($conn);
  }


?>