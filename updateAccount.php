<?php
    session_start();

  ?>
  <?php
    include_once 'db_functions.php';
    $con = dbConnect();

    $UID = $_SESSION['userID'];
    $name = $_POST["user"];
    $pword = $_POST["password"];
    $bdate = $_POST["bdate"];

  if ($name != NULL){
    $query = "UPDATE Fan SET FName= '$name' WHERE FanID = $UID ";
    mysqli_query($con, $query);
    echo "<p>Name Successfully Updated!</p>";
  }
  if ($pword != NULL){
    $query = "UPDATE Fan SET FPassword = '$pword' WHERE FanID = $UID ";
    mysqli_query($con, $query);
    echo "<p>Password Successfully Updated!</p>";
  }
  if ($bdate != NULL){
    $query = "UPDATE Fan SET FBirthDate = '$bdate' WHERE FanID = $UID ";
    mysqli_query($con, $query);
    echo "<p>Birth Date Successfully Updated!</p>";
  }
  header('Location: edit_account.php');
?>