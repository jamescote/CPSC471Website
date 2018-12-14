<?php
    session_start();
  ?>
  <?php
    include_once 'db_functions.php';
    $con = dbConnect();

    $UID = $_SESSION['userID'];
    $pword = $_POST["password"];
    $desc = $_POST["description"];
    $ptype = $_POST["PromoterType"];

  if ($pword != NULL){
    $query = "UPDATE Promoter SET Password= '$pword' WHERE PromoterID = $UID ";
    mysqli_query($con, $query);
    echo "<p>Password Successfully Updated!</p>";
  }
  if ($desc != NULL){
    $query = "UPDATE Promoter SET Description= '$desc' WHERE PromoterID = $UID ";
    mysqli_query($con, $query);
    echo "<p>Description Successfully Updated!</p>";
  }
  if ($ptype != "placeHolder"){
    $query = "UPDATE Promoter SET PromoterType= '$ptype' WHERE PromoterID = $UID";
    mysqli_query($con, $query);
    echo "<p>Promoter Type Successfully Updated!</p>";
  }
  header('Location: edit_promoter.php');
?>