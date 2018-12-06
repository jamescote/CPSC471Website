<h3>Upcoming Events</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
	$con = dbConnect();
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch Next 3 Closest Events
		$query = "SELECT E.EventID, E.Name, E.EventTimestamp, E.Description, E.TicketPrice
		FROM event as E
		WHERE E.EventTimestamp > NOW()
		ORDER BY E.EventTimestamp ASC
		LIMIT 3";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo "<table>";
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Date
					echo "<tr>";
					echo "<td><b>" . $row[Name] . "</b></td>";
					echo "<td>" . $row[EventTimestamp] . "</td>";
					echo "</tr>";
					// Row 2: Description
					echo "<tr>";
					echo "<td colspan='2'>" . $row[Description] . "</td>";
					echo "</tr>";
					// Row 3: Ticket Price and link to buy
					echo "<tr>";
					echo "<td>" . $row[TicketPrice] . "</td>";
					?> <td><a href ="<?php echo "buy_ticket.php?ID=" . $row[EventID] . "&type=event";?>">Buy Tickets!</a></td>
					<?php
				}
				echo "</table>";
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