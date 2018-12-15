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
		$query = "SELECT P.PromoterID, P.Name, P.PromoterType, P.Description FROM cpsc471.Followed_By as F, cpsc471.Promoter as P WHERE F.FanID = $fanId AND F.PromoterID = P.PromoterID ";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo "<table>";
                while( $row = mysqli_fetch_array($res))
				{
                        // Row 1: Name and Date
                        echo "<tr>";
                        echo "<td><b>" . $row['Name'] . "</b></td>";
                        echo "<td>" . $row['PromoterType'] . "</td>";
                        echo "</tr>";
                        // Row 2: Description
                        echo "<tr>";
                        echo "<td colspan='2'>" . $row['Description'] . "</td>";
                        echo "</tr>";
                        // Row 3: Ticket Price and link to buy
						echo "</td>";
                        ?> 
						<td class="seeEvents">
						<form action="promoterEvents.php" method="post">
							<input type="hidden" name="pID" value="<?php echo $row['PromoterID']; ?>">
							<input type="submit" name="seeEvents" value="See Events">
						</form>
					</td>
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
			echo "ERROR: could not execute $query. " . mysqli_error($con);
		}
	}
?>