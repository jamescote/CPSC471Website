<?php
	// Connect to Database
	function dbConnect()
	{
		$con = mysqli_connect("localhost","root","123456789","cpsc471");
		
		// Handle Connection Errors:
		if( mysqli_connect_errno($con) )
		{
			echo "ERROR: Unable to connect to database! " . mysqli_connect_error();
		}
		
		return $con;
	}
?>