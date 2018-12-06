<?php
	// Start Session
	session_start();
	
	// Functions for Database connection
	include 'db_functions.php';
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>colour_blue - contact us</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
</head>

<body>
<?php // Ensure that user is logged in first.
	if( $_SESSION['userType'] != "fan" )
	{
		// Redirect to Sign up
		echo "<script> location.href='create_account.php'; </script>";
		exit;
	}
?>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
          <h2>Buy Ticket</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
        <?php include 'upcoming_events.php' ?>
      </div>
      <div id="content">
        <!-- insert the page content here -->
		<?php
			if( isset($_GET['ID'], $_GET['type']) && !empty($_GET['ID']) )
			{
				$connection = dbConnect();
				
				// Connected successfully? Display Ticket Information
				if( !mysqli_connect_errno($connection) )
				{
					switch($_GET['type'])
					{
						case "event":
							displayEvent($connection, $_GET['ID']);
							break;
						case "series":
							displaySeries($connection, $_GET['ID']);
							break;
						default:
							echo "<b>ERROR:</b> Incorrect Ticket Type";
							break;
					}
				}
				
			}
			else
				echo "<b>ERROR:</b> No Ticket Specified.";
			
			// Function to display an Event using a given EventID
			function displayEvent($con, $EventID)
			{
				// Get Specified Event
				if( $event = mysqli_query($con, "SELECT * FROM event WHERE EventID=" . $EventID) )
				{
					if( mysqli_num_rows($event) > 0 )
					{
						$eventRow = mysqli_fetch_array($event);
						echo "<h1>" . $eventRow['Name'] . "</h1>";
						echo "<table>";
						echo "<tr><td><b>When:</b></td>";
						echo "<td>" . $eventRow['EventTimestamp'] . "</td></tr>";
						echo "<tr><td><b>Duration:</b></td>";
						echo "<td>" . $eventRow['Duration'] . " Minutes.</td></tr>";
						echo "<tr><td><b>Description:</b></td>";
						echo "<td>" . $eventRow['Description'] . "</td></tr>";
						// Display Venue information
						echo "<tr><td><b>Where:</b></td>";
						if( $venue = mysqli_query($con, "SELECT * FROM venue WHERE Name = (SELECT Name FROM event_venues WHERE EventID=" . $EventID . ")") )
						{	// Found Venue:
							if( mysqli_num_rows($venue) > 0 )
							{
								$venueRow = mysqli_fetch_array($venue);
								echo "<td><b>" . $venueRow['Name'] . "</b><br>";
								echo $venueRow['StreetNum'] . " " . $venueRow['StreetName'] . "<br>";
								echo $venueRow['City'] . ", " . $venueRow['Province'] . "<br>";
								echo "Seating: " . $venueRow['Capacity'] . "</td>";
							}
						}
						else
							echo "<td>No Venue Found!</td>";
						// Promoter Information:
						echo "<tr><td><b>Promoter:</b></td>";
						if( $promoter = mysqli_query($con, "SELECT * FROM promoter WHERE PromoterID=" . $eventRow['PromoterID']))
						{	// Found Promoter:
							if( mysqli_num_rows($promoter) > 0 )
							{
								$promoterRow = mysqli_fetch_array($promoter);
								echo "<td><b>" . $promoterRow['Name'] . "</b><br>";
								echo $promoterRow['Description'] . "<br>";
								echo "Type: " . $promoterRow['PromoterType'] . "<br>";
								
								// Check to see if user follows promoter
								if( $follow = mysqli_query($con, "SELECT * FROM followed_by WHERE PromoterID=" . $promoterRow['PromoterID'] . " AND FanID=" . $_SESSION['userID']))
								{
									if ( mysqli_num_rows($follow) > 0 )
										echo "<em>Followed</em>";
									else
									{
										echo "<form action='' method='post'>
										<input type='submit' name'followBtn' value='Follow Promoter' />
										</form>";
										
										// Handle Button Logic
										if( isset($_POST['followBtn']) )
										{
											echo "followBtn.disabled = true";
											echo "<em style='background-color:MediumSeaGreen;'>Followed!</em>";
										}
									}
								}
								else
									echo "<b>ERROR:</b> Unable to form button: " . $promoterRow['PromoterID'] . "->" . $_SESSION['userID'];
								
								// End the Table Row
								echo "</td></tr>";
							}
						}
						else
							echo "<td>No Promoter Found!</td>";
						
						echo "</table>";
					}
					else
						echo "<b>ERROR:</b> Ticket Not Found.";
				}
				else
					echo "<b>ERROR:</b> Event display Query failed.";
			}
			
			// Function to display an Event using a given EventID
			function displaySeries($con, $SeriesID)
			{
				
			}
		?>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; colour_blue | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">design from HTML5webtemplates.co.uk</a>
    </div>
  </div>
</body>
</html>
