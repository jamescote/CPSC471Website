<?php

    session_start();

?>

<?php
include 'db_functions.php';

  $conn = dbConnect();
  $name = $_POST["select"];

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