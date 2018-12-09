<?php

    session_start();

?>

<?php
include 'db_functions.php';

	$conn = dbConnect();
	$eventID = $_POST['name'];
	$query = "DELETE FROM Event WHERE EventID = {$eventID}";    

  if(mysqli_query($conn, $query))
  {
    echo "<p>Event Deleted Successfully!</p>";
    // Redirect to this page if successfully inserted data
    header('Location: event_page.php');
    //header('Location: add_venue.php');

  }
  else
  {
    echo $_POST['name'] . " ERROR: Could not execute $query." . mysqli_error($conn);
  }
?>