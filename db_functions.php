<?php
	date_default_timezone_set('America/Edmonton'); // Need to set default timezone to avoid date warnings.
	
	// Connect to Database
	function dbConnect()
	{
		$con = mysqli_connect("localhost","root","password","cpsc471");
		
		// Handle Connection Errors:
		if( mysqli_connect_errno($con) )
		{
			echo "<script type='text/javascript'>alert('Failed to Connect to the Database');</script>";
			header('//history(-1)');
			exit;
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
		if( $priceValue != 0.0 )
		{
			?>
			<script>// Display Ticket Price as Currency.
				document.write(new Intl.NumberFormat('en-US', {style: 'currency', currency: 'CAD', minimumFractionDigits: 2}).format( <?php echo $priceValue; ?> ));
			</script>
			<?php
		}
		else
			echo "<em>Free!</em>";
	}
	
	// Will output all fields and results from a query.
	function outputResultTable( $res )
	{
		echo "<table>";
		// Display all available tickets
		echo "<tr>";
		for( $i = 0; $i < mysqli_num_fields($res); $i++)
		{
			$field_info = mysqli_fetch_field($res);
			echo "<th>{$field_info->name}</th>";
		}
		echo "</tr>";
		while( $row = mysqli_fetch_array($res) )
		{
			echo "<tr>";
			for( $i = 0; $i < mysqli_num_fields($res); $i++)
			{
				echo "<td>{$row[$i]}</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	
	// Follow Promoter Button
	function displayFollowing($dbConnection, $promoterID)
	{
		// Check to see if user follows promoter
		if( $dbConnection && $follow = mysqli_query($dbConnection, "SELECT * FROM followed_by WHERE PromoterID={$promoterID} AND FanID={$_SESSION['userID']}"))
		{
			if ( mysqli_num_rows($follow) > 0 )
				echo "<em>Followed</em>";
			else
			{
				?>
				<button onclick="updateFollowBtn()" id="followBtn">Follow Promoter</button>
				
				<script> // Script for followBtn -> Adds Follow Value to followed by table
				function updateFollowBtn()
				{
					var followBtn = document.getElementById("followBtn");
					followBtn.innerText = "Followed!";
					followBtn.disabled = true;
					<?php // Add Value to Followed_by table.
						if( !addFollowedBy($dbConnection, $promoterID, $_SESSION['userID'] ) )
						{
							echo "followBtn.innerText = 'ERROR: Follow Failed!';";
						}
					?>
				}
				</script>
				<?php
				
			}
		}
		else
			echo "<b>ERROR:</b> Unable to form button: {$promoterID}->{$_SESSION['userID']}";
	}
	
	// Returns an html formatted string to display date.
	function formatDate($dateObj, $fontSize)
	{
		$biggerFont = $fontSize + 1;
		if( 'object' == gettype($dateObj) )
			return "<font size='{$biggerFont}'>".date_format($dateObj, 'M j, Y')."</font></br><font size='{$fontSize}'>".date_format($dateObj, 'D G:i')."</font>";
		else
			return $dateObj;
	}
?>