<h3>Following</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
    $con = dbConnect();
    $fanId = $_SESSION['userID'];
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch Next 3 Closest Events
		$query = "SELECT * FROM Followed_By WHERE FanID = $fanId";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo "<table>";
                while( $row = mysqli_fetch_array($res)){
                $q2 = "SELECT * FROM Promoter WHERE PromoterID = {$row['PromoterID']}";
                    $result = $res = mysqli_query($con,$q2);
                    while(   $row2 = mysqli_fetch_array($result))
				    {
                        // Row 1: Name and Date
                        echo "<tr>";
                        echo "<td><b>" . $row2['Name'] . "</b></td>";
                        echo "<td>" . $row2['PromoterType'] . "</td>";
                        echo "</tr>";
                        // Row 2: Description
                        echo "<tr>";
                        echo "<td colspan='2'>" . $row2['Description'] . "</td>";
                        echo "</tr>";
                        // Row 3: Ticket Price and link to buy
                        echo "</td>";
                        ?> <td><a href ="<?php echo "buy_ticket.php?ID=" . $row['EventID'] . "&type=event";?>">See Events</a></td>
                        <?php
                    }
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