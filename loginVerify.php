
<?php
    session_start();
    include_once 'db_functions.php';
    $con = dbConnect();

	$user_name = mysqli_real_escape_string($con,$_POST['user']);
	$user_password = mysqli_real_escape_string($con,$_POST['password']);
	$accountType = $_REQUEST['accountType'];
	
	if ($accountType == "Fan"){
		$sql = "SELECT `FanID`, `FLogin`, `FPassword`, `Fname`, `FBirthDate` FROM `Fan` WHERE FLogin = '$user_name'";
	}elseif ($accountType == "Promoter"){
		$sql = "SELECT `PromoterID`, `Login`, `Password` FROM `Promoter` WHERE Login = '$user_name'";
	}
	
	if(!($result = mysqli_query($con, $sql))){
	}


	while($row = mysqli_fetch_array($result)){
		if ($accountType == "Fan"){
			
			$userName = $row['FLogin'];
			$password = $row['FPassword'];
			//$user_type = $row['type'];
			$userID = $row['FanID'];
			//$userFname = $row['Fname'];
			//$userBirthday = $row['FBirthDate'];
		}elseif($accountType == "Promoter"){
			$userName = $row['Login'];
			$password = $row['Password'];
			$userID = $row['PromoterID'];

		}
	}
	echo $accountType;
	if($user_password == $password && strcasecmp($user_name, $userName) == 0){
		echo "Login Successful!";
		session_regenerate_id(true);
		$_SESSION['userType'] = $accountType;
		$_SESSION['userID'] = $userID;
		
		
		//$_SESSION['accessLevel'] = $user_type;
	}else{
		echo "Login Failed!";
	}echo "<p>Redirecting to homepage </p>";
	echo '<meta http-equiv="Refresh" content="2; url=index.php">';

?>