<?php

    session_start();

?>

<?php
include 'db_functions.php';

  $conn = dbConnect();
  $userID = $_SESSION["userID"];

  $name = $_POST["eventName"];
  $description = $_POST["eventDescription"];
  $timeStamp = $_POST["eventYear"] . "-" .  $_POST["eventMonth"] . "-" . $_POST["eventDay"] . " " . $_POST["eventStartHour"] . ":" . $_POST["eventStartMinute"] . ":00";
  $duration = $_POST["eventEndHour"] - $_POST["eventStartHour"] - ($_POST["eventEndMinute"] < $_POST["eventStartMinute"]);

  $query = "INSERT INTO Event (SeriesID, PromoterID, Name, EventTimestamp, Description, Duration, NumTicketsRemaining, TicketPrice)
  VALUES ( NULL, {$userID}, '$name' , '$timeStamp' , '$description' , '$duration', " . $_POST["eventNumTickets"] . ", " . $_POST["eventTicketPrice"] . ")";

  //$result = mysqli_query($connection, $query);

  if(mysqli_query($conn, $query))
  {
    echo "<p>Event Added Successfully!</p>";
    // Redirect to this page if successfully inserted data
    $eventID = mysqli_fetch_array(mysqli_query($conn, "SELECT LAST_INSERT_ID()"))[0];
    //header('Location: event_page.php');
    header("Location: add_venue.php?eventID=$eventID");

  }
  else
  {
    echo "$userID ERROR: Could not execute $query." . mysqli_error($conn);
  }
?>
