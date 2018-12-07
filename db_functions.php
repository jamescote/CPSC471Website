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
	
	// Add Followed By value to Database
	// Params: connection -> connection to Database
	//			PromoterID, UserID -> database values
	// Return: returns result of query.
	function addFollowedBy( $connection, $PromoterID, $UserID )
	{
		if( !mysqli_connect_errno($connection) )
		{
			return mysqli_query($connection, "INSERT INTO followed_by (FanID, PromoterID) VALUES (" . $UserID . ", " . $PromoterID . ")");
		}
		else
			echo "<b>ERROR:</b> Unable to connect to database to add Followed By Relation; PromoterID: " . $PromoterID . "; UserID: " . $UserID . ".</br>";
	}
	
	function outputCurrencyString( $priceValue )
	{
		?>
		<script>// Display Ticket Price as Currency.
			document.write(new Intl.NumberFormat('en-US', {style: 'currency', currency: 'CAD', minimumFractionDigits: 2}).format( <?php echo $priceValue; ?> ));
		</script>
		<?php
	}
?>