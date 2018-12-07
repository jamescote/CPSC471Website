<h3>Current Created Events</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
	$con = dbConnect();
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch All Fan's Credit Cards
		$query = "SELECT E.EventID, E.PromoterID, E.Name, E.EventTimestamp, E.Description, E.TicketPrice, E.NumTicketsRemaining
		FROM Event as E, Promoter as P
        WHERE E.PromoterID = P.PromoterID AND E.EventTimestamp > NOW()
		ORDER BY E.EventTimestamp ASC";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo '<table>';
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Type
					echo '<tr>';
					echo '<td><b>' . $row[Name] . '</b></td>';
					echo '<td>' . $row[EventTimestamp] . '</td>';
					echo '</tr>';
					// Row 2: Description
					echo '<tr>';
					echo '<td colspan="2">' . $row[Description] . '</td>';
                    echo '</tr>';
                    // Row 3: 
                    echo '<tr>';
					echo '<td><b>' . 'Ticket Price: $' . $row[TicketPrice] . '</b></td>';
					echo '<td>' . 'Remaining Tickets: ' . $row[NumTicketsRemaining] . '</td>';
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