<?php

    session_start();

?>

<?php
include 'db_functions.php';

  $conn = dbConnect();

  $name = $_POST["eventName"];
  $description = $_POST["eventDescription"];
  $timeStamp = $_POST["eventYear"] . "-" .  $_POST["eventMonth"] . "-" . $_POST["eventDay"] . " " . $_POST["eventStartHour"] . ":" . $_POST["eventStartMinute"] . ":00";
  $duration = $_POST["eventEndHour"] - $_POST["eventStartHour"] - ($_POST["eventEndMinute"] < $_POST["eventStartMinute"]);

  $query = "INSERT INTO Event (SeriesID, PromoterID, Name, EventTimestamp, Description, Duration, NumTicketsRemaining, TicketPrice)
  VALUES ( NULL, " . $_SESSION["userID"] . ", '$name' , '$timeStamp' , '$description' , '$duration', " . $_POST["eventNumTickets"] . ", " . $_POST["eventTicketPrice"] . ")";

  //$result = mysqli_query($connection, $query);

  if(mysqli_query($conn, $query))
  {
    echo "<p>Credit Card Added Successfully!</p>";
    // Redirect to this page if successfully inserted data
    header('Location: event_page.php');

  }
  else
  {
    echo "ERROR: Could not execute $query." . mysqli_error($conn);
  }
?>