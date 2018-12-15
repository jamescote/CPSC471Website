<h3>Promoters</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
	$con = dbConnect();
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch All Fan's Credit Cards
		$query = "SELECT * FROM Promoter";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo "<table width='100%'>";
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Type
					echo '<tr>';
					echo '<td><b>Name: ' . $row['Name'] . '</b></td>';
					echo '<td>Type: ' . $row['PromoterType'] . '</td>';
					echo '</tr>';
					// Row 2: Description
					echo '<tr>';
					echo '<td colspan="2">Description: ' . $row['Description'] . '</td>';
                    echo '</tr>';
                    // Delete button
					echo "<tr>";
					?> 
					<td colspan=2 class="follow">
						<form action="follow.php" method="post">
							<input type="hidden" name="name" value="<?php echo $row['PromoterID']; ?>">
							<input type="submit" name="submit" value="Follow">
						</form>
					</td>
			
					<?php
				}
				echo '</tr></table>';
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
