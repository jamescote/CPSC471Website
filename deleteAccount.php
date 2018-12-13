<?php
    session_start();

  ?>
  <?php
    include_once 'db_functions.php';
    $con = dbConnect();

    $UID = $_SESSION['userID'];
    $aType = $_SESSION['userType'];

  if ($aType == "fan" ){
    $query = "DELETE FROM Fan WHERE FanID = $UID ";
  }

  if ($aType == "promoter"){
    $query = "DELETE FROM Promoter WHERE PromoterID = $UID ";
  }

  if($res = mysqli_query($con,$query)){
    echo "<p>Account Successfully Deleted!</p>";
    session_start();
    session_regenerate_id();
    session_destroy();
    header('Location: index.php');
  }else{
    echo "ERROR: could not execute $sql. " . mysqli_error($con);
  }
?>