<h3>User Info</h3>
<?php
	include_once 'db_functions.php';
	
	// Connect to Database
	$con = dbConnect();
	
	// Connected? Query for Upcoming Events
	if( !mysqli_connect_errno($con) )
	{
		// Fetch Next 3 Closest Events
		$query = "SELECT *
		FROM Promoter as P
		WHERE P.PromoterID='{$_SESSION['userID']}' ";
		if( $res = mysqli_query($con,$query) )
		{
			if( mysqli_num_rows($res) > 0 )
			{
				echo "<table>";
				while( $row = mysqli_fetch_array($res))
				{
					// Row 1: Name and Date
					echo "<tr>";
					echo "<td><b>Name: " . $row['Name'] . "</b></td>";
					echo "<td>Type: " . $row['PromoterType'] . "</td>";
					echo "</tr>";
					// Row 2: Description
					echo "<tr>";
					echo "<td colspan='2'>Login: " . $row['Login'] . "</td>";
                    echo "</tr>";
                    echo "<tr>";
					echo "<td colspan='2'>Description: " . $row['Description'] . "</td>";
                    echo "</tr>";
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