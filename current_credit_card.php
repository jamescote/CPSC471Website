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
		FROM cpsc471.Credit_Card as C, cpsc471.Fan as F, cpsc471.Payment_Info as P
		WHERE C.CCID=P.CCID AND P.FanID=F.FanID AND F.FanID = {$_SESSION["userID"]} ";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo '<table>';
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Type
					echo '<tr>';
					echo '<td><b>Name: ' . $row['CCName'] . '</b></td>';
					echo '<td>Type: ' . $row['CCType'] . '</td>';
					echo '</tr>';
					// Row 2: Description
					echo '<tr>';
					echo '<td colspan="2">Number: ' . $row['CCNumber'] . '</td>';
                    echo '</tr>';
                    // Delete button
					echo "<tr>";
					?> 
					<td class="card-delete">
						<form action="delete_credit_card.php" method="post">
							<input type="hidden" name="name" value="<?php echo $row['CCID']; ?>">
							<input type="submit" name="submit" value="Delete">
						</form>
					</td>
					<?php
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
