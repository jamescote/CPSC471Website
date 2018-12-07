
<?php
    session_start();
    include_once 'db_functions.php';
    $con = dbConnect();

    $user_name = mysqli_real_escape_string($con,$_POST['user']);
    $promoType = $_POST["PromoterType"];
	
    $sql = "SELECT * FROM Promoter WHERE Login = '{$user_name}' ";
        
	$result = mysqli_query($con, $sql);

	echo "Num Rows: " . mysqli_num_rows($result) . "; Query: {$sql}</br>";
	if (mysqli_num_rows($result)>=1){

        echo "<script type='text/javascript'>alert('user name already resgistered');</script>";
		header('//history(-1)');
		exit;
        echo '<meta http-equiv="Refresh" content="2; url=promoterRegistration.php">';
    }

    $query = "INSERT INTO Promoter ( Name, Login, Password, Description, PromoterType)
  VALUES ('{$_POST["pname"]}', '{$user_name}', '{$_POST["password"]}', '{$_POST["description"]}', '{$promoType}')";

  //$result = mysqli_query($connection, $query);

  if(mysqli_query($con, $query))
  {
    echo "<p>Account Successfully created!</p>";
    session_regenerate_id(true);
		$_SESSION['userType'] = "promoter";
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