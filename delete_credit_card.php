<?php

    session_start();

?>

<?php
include 'db_functions.php';

	$conn = dbConnect();
	$ccID = $_POST['name'];
	$query = "DELETE FROM Credit_Card WHERE CCID = {$ccID}";    

  if(mysqli_query($conn, $query))
  {
    echo "<p>Credit Card Deleted Successfully!</p>";
    // Redirect to this page if successfully inserted data
    header('Location: credit_card_page.php');

  }
  else
  {
    echo $_POST['name'] . " ERROR: Could not execute $query." . mysqli_error($conn);
  }
?>