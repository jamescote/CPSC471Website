<h3>Current Created Events</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
	$con = dbConnect();
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch All Events
		$query = "SELECT E.EventID, E.PromoterID, E.Name, E.EventTimestamp, E.Description, E.TicketPrice, E.NumTicketsRemaining
		FROM Event as E, Promoter as P
        WHERE E.PromoterID = P.PromoterID AND E.PromoterID = {$_SESSION["userID"]} AND E.EventTimestamp > NOW()
		ORDER BY E.EventTimestamp ASC";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo '<table width="100%">';
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Type
					echo '<tr>';
					echo '<td><b>' . $row['Name'] . '</b></td>';
					echo '<td>' . $row['EventTimestamp'] . '</td>';
					echo '</tr>';
					// Row 2: Description
					echo '<tr>';
					echo '<td colspan="2">' . $row['Description'] . '</td>';
                    echo '</tr>';
                    // Row 3: Num and Price of Tickets
                    echo '<tr>';
					echo '<td><b>' . 'Ticket Price: $' . $row['TicketPrice'] . '</b></td>';
					echo '<td>' . 'Remaining Tickets: ' . $row['NumTicketsRemaining'] . '</td>';
					echo '</tr>';
					// Delete button
					echo "<tr>";
					?> 
					<td colspan=2 class="event-delete">
						<form action="delete_event.php" method="post">
							<input type="hidden" name="name" value="<?php echo $row['EventID']; ?>">
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
				echo "'{$_SESSION['userID']}'";
			}
		}
		else // Error in SQL Statment
		{
			echo "ERROR: could not execute $sql. " . mysqli_error($con);
		}
	}
?>
