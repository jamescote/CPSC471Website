# CPSC471Website
Github for Final Database Project

Note: change server, username, and password in dbConnect() function in db_functions.php to your own username and password  
```
function dbConnect() {  
	// Change "root" and "123456789" to your own database username and password
	$con = mysqli_connect("localhost","root","123456789","cpsc471");
		
	// Handle Connection Errors:
	if( mysqli_connect_errno($con) ) {
		echo "<script type='text/javascript'>alert('Failed to Connect to the Database');</script>";
		header('//history(-1)');
		exit;
	}
		
	return $con;
}
```
