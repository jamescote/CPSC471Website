# CPSC471Website
Master Ticket  
Final Database Project

Note: change server, username, and password in dbConnect() function in db_functions.php to your own username and password  

```
function dbConnect() {  
	// Change "localhost", "root", and "123456789" to own database specifications
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
