
<?php
    session_start();
    include_once 'db_functions.php';
    $con = dbConnect();

    $user_name = mysqli_real_escape_string($con,$_POST['user']);
    
	
    $sql = "SELECT * FROM `Fan` WHERE FLogin = '$user_name'";
    
        
	$result = mysqli_query($con, $sql) or die($sql."</br></br>".mysqli_error($con));

	if ((mysqli_num_rows($result))>=1){

    
        echo "<script type='text/javascript'>alert('user name already resgistered');</script>";
		//header("location:".$_SERVER['HTTP_REFERER']);
        echo "<meta http-equiv='Refresh' content='2; url={$_SERVER['HTTP_REFERER']}'>";
		exit;
    }

    $query = "INSERT INTO Fan (FLogin, FPassword, FName, FBirthDate)
  VALUES ('$user_name' , '" . $_POST["password"] . "', '" . $_POST["fname"] . "', '" . $_POST["bdate"] . "')";
  
  // Get the newly generated SaleID
  //$promoID = mysqli_fetch_array(mysqli_query($con, "SELECT LAST_INSERT_ID()"))[0];
  //echo($promoID);
  //$result = mysqli_query($connection, $query);

  if(mysqli_query($con, $query))
  {
    echo "<p>Account Successfully created!</p>";
    session_regenerate_id(true);
		$_SESSION['userType'] = "fan";
		$_SESSION['userID'] = mysqli_fetch_array(mysqli_query($con, "SELECT LAST_INSERT_ID()"))[0];
    // Redirect to this page if successfully inserted data
    header('Location: index.php');

  }
  else
  {
    echo "ERROR: Could not execute $query." . mysqli_error($con);
    //echo '<meta http-equiv="Refresh" content="2; url=promoterRegistration.php">';
  }
	

?>