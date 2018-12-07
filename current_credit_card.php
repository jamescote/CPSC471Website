<h3>Current Credit Cards</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
	$con = dbConnect();
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch All Fan's Credit Cards
		$query = "SELECT C.CCID, C.CCType, C.CCName, C.CCNumber, C.CCMonth, C.CCYear
		FROM Credit_Card as C, Fan as F,  Payment_Info as P
		WHERE F.FanID = P.FanID AND P.CCID = C.CCID ";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo '<table>';
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Type
					echo '<tr>';
					echo '<td><b>' . $row[CCName] . '</b></td>';
					echo '<td>' . $row[CCType] . '</td>';
					echo '</tr>';
					// Row 2: Description
					echo '<tr>';
					echo '<td colspan="2">' . $row[CCNumber] . '</td>';
                    echo '</tr>';
                    // Divider
                    echo '<tr>';
					echo '<td colspan="2">' . ' ' . '</td>';
                    echo '</tr>';
					?>
					<?php
				}
				echo '</table>';
				mysqli_free_result($res);
			}
			else // No results? 
			{
				echo "No matching records found!";
			}
		}
		else // Error in SQL Statment
		{
			echo "ERROR: could not execute $sql. " . mysqli_error($con);
		}
	}
?>
