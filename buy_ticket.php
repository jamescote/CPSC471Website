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
						?>
						<form action="process_order.php?ID=<?php echo $EventID; ?>&type=<?php echo $_GET['type'];?>" method="post">
						<?php
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
										?>
										<button onclick="updateFollowBtn()" id="followBtn">Follow Promoter</button>
										
										<script> // Script for followBtn -> Adds Follow Value to followed by table
										function updateFollowBtn()
										{
											var followBtn = document.getElementById("followBtn");
											followBtn.innerText = "Followed!";
											followBtn.disabled = true;
											<?php // Add Value to Followed_by table.
												if( !addFollowedBy($con, $promoterRow['PromoterID'], $_SESSION['userID'] ) )
												{
													echo "followBtn.innerText = 'ERROR: Follow Failed!';";
												}
											?>
										}
										</script>
										<?php
										
									}
								}
								else
									echo "<b>ERROR:</b> Unable to form button: " . $promoterRow['PromoterID'] . "->" . $_SESSION['userID'];
								
								// End the Table Row
								echo "</td></tr>";
							}
							else
								echo "<td>No Promoter Found!</td></tr>";
						}
						else
							echo "<td>No Promoter Found!</td></tr>";
						
						// Ticket Information
						echo "<tr><td><b>Ticket Price:</b></td>";
						echo "<td>";
						outputCurrencyString($eventRow['TicketPrice']);
						if( $eventRow['NumTicketsRemaining'] <= 0 )
							echo "<font color='red'>SOLD OUT!</font>";
						echo "</td></tr>"; // End Ticket Price
						
						// Option to Buy:
						if( $eventRow['NumTicketsRemaining'] > 0 )
						{
							echo "<tr><td><b>Payment Option:</b></td>";
							
							$payQuery = "SELECT CCID, CCNumber, CCType, CCMonth, CCYear FROM credit_card WHERE CCID IN (SELECT CCID FROM payment_info WHERE FanID=" . $_SESSION['userID'] . ")";
							$payResult = mysqli_query($con, $payQuery);
							
							if( !$payResult )
								echo "<td><b>ERROR:</b> Query Failed! '" . $payQuery . "'.";
							elseif( mysqli_num_rows($payResult) == 0)
							{
								echo "<td>Oops! You have no Payment Options set up yet! :(</td></tr>";
							}
							else
							{
								// Drop Down Selection for Payment Options
								?>
								<td>
								<select name="paymentChoice">
									<?php
										while( $payRow = mysqli_fetch_array($payResult))
										{
											$expDate = date_create_from_format('d-m-y', '31-' . $payRow['CCMonth'] . '-' . $payRow['CCYear']);
											if( date_format($expDate, "Y-m-d") >= date("Y-m-d") )
												echo "<option value='" . $payRow['CCID'] . "'>" . $payRow['CCNumber'] . " (exp: " . $payRow['CCMonth'] . "/" . $payRow['CCYear'] . " - " . $payRow['CCType'] . ")</option>";
										}
									?>
								</select>
								</td>
								<?php
							}
							
							// Number of Tickets to purchase
							echo "<tr><td><b>Number of Tickets:</b></td>";
							?>
							<td>
							
								<select name="numTickets">
								<?php
									$num = 1; // Give options for a maximum of 5 tickets to buy limited by remaining amount.
									while( ($num <= 5) && ($num <= $eventRow['NumTicketsRemaining']) )
									{
										echo "<option value='" . $num . "'>" . $num . "</option>";
										$num++;
									}
								?>
								</select>
							</td></tr>
							<?php
							
							// Ticket buy button
							echo "<tr><td colspan=2>";
							?>
							<INPUT TYPE="SUBMIT" VALUE="Get Tickets!">
							<?php
							echo "</td></tr>";
						}
						
						echo "</table>";
						echo "</form>";
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
