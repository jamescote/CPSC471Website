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
		echo "<script> location.href='newUser.php'; </script>";
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
					echo "<table width='100%'>";
					switch($_GET['type'])
					{
						case "event":
							$ticketsRemaining; $ticketPrice;
							displayEvent($connection, $_GET['ID'], $ticketsRemaining, $ticketPrice);
							// Ticket Information
							displayTicketInfo($con, $ticketsRemaining, $ticketPrice);
							break;
						case "series":
							$ticketsRemaining; $ticketPrice;
							displaySeries($connection, $_GET['ID'], $ticketsRemaining, $ticketPrice);
							// Ticket Information
							displayTicketInfo($con, $ticketsRemaining, $ticketPrice);
							break;
						case "resale":
							displayResale($connection, $ticketPrice);
							displayTicketInfo($con, 1, $ticketPrice, true);
							break;
						default:
							echo "<b>ERROR:</b> Incorrect Ticket Type";
							break;
					}
					echo "</table>";
				}
				
			}
			else
				echo "<b>ERROR:</b> No Ticket Specified.";
			
			// Function to display an Event using a given EventID
			function displayEvent($con, $EventID, &$ticketsRemaining, &$ticketPrice)
			{
				$eventQuery = "SELECT
									E.Name AS EventName,
									E.EventTimestamp AS Date,
									E.Duration,
									E.Description AS EventDescription,
									E.NumTicketsRemaining,
									E.TicketPrice,
									V.Name AS VenueName,
									V.StreetNum,
									V.StreetName,
									V.City,
									V.Province,
									V.Capacity,
									P.PromoterID,
									P.Name AS PromoterName,
									P.Description AS PromoterDescription,
									P.PromoterType
								FROM Event AS E
									JOIN Venue AS V 
										ON V.Name = (SELECT VenueName FROM Event_Venues AS EV WHERE EV.EventID = E.EventID)
									JOIN Promoter AS P 
										ON P.PromoterID = E.PromoterID
								WHERE E.EventID = {$EventID}";
				// Get Specified Event
				if( $event = mysqli_query($con, $eventQuery) or die($eventQuery."</br></br>".mysqli_error($con) ))
				{
					if( mysqli_num_rows($event) > 0 )
					{
						$eventRow = mysqli_fetch_array($event);
						echo "<h1>{$eventRow['EventName']}</h1>";
						echo "<tr><td><b>When:</b></td>";
						echo "<td>".date_format(date_create_from_format("Y-m-d H:i:s", $eventRow['Date']), 'D, M j, Y, G:i')."</td></tr>";
						echo "<tr><td><b>Duration:</b></td>";
						echo "<td>{$eventRow['Duration']} Minutes.</td></tr>";
						echo "<tr><td><b>Description:</b></td>";
						echo "<td>{$eventRow['EventDescription']}</td></tr>";
						
						// Display Venue information
						echo "<tr><td><b>Where:</b></td>";
						echo "<td><b>{$eventRow['VenueName']}</b><br>";
						echo "{$eventRow['StreetNum']} {$eventRow['StreetName']}<br>";
						echo "{$eventRow['City']}, {$eventRow['Province']}<br>";
						echo "Seating: {$eventRow['Capacity']}</td>";
						
						// Promoter Information:
						displayPromoterRow($con, $eventRow['PromoterName'], $eventRow['PromoterDescription'], $eventRow['PromoterType'], $eventRow['PromoterID']);
						
						$ticketsRemaining = $eventRow['NumTicketsRemaining'];
						$ticketPrice = $eventRow['TicketPrice'];
						
						// Display Retail Price
						displayTicketPriceRow($ticketPrice, $ticketsRemaining);
					}
					else
						echo "<b>ERROR:</b> Event Not Found.";
					
					// Free Results
					mysqli_free_result($event);
				}
				else
					echo "<b>ERROR:</b> Event display Query failed.";
			}
			
			// Function to display an Event using a given SeriesID
			function displaySeries($con, $SeriesID, &$ticketsRemaining, &$ticketPrice)
			{
				$seriesQuery = "SELECT
									S.Name AS SeriesName,
									E1.Name AS StartName,
									E1.EventTimestamp AS StartDate,
									E2.Name AS FinalName,
									E2.EventTimestamp AS FinalDate,
									S.NumEvents,
									S.Description AS SeriesDescription,
									S.NumTicketsRemaining,
									S.TicketPrice,
									V1.Name AS FirstVenueName,
									V1.City AS FirstCity,
									V1.Province AS FirstProvince,
									V2.Name AS FinalVenueName,
									V2.City AS FinalCity,
									V2.Province AS FinalProvince,
									P.PromoterID,
									P.Name AS PromoterName,
									P.Description AS PromoterDescription,
									P.PromoterType
								FROM Series AS S
									JOIN Event AS E1 
										ON S.StartEventID = E1.EventID
									JOIN Venue AS V1
										ON V1.Name = (SELECT VenueName FROM Event_Venues AS EV WHERE EV.EventID = E1.EventID)
									JOIN Event AS E2
										ON S.EndEventID = E2.EventID
									JOIN Venue AS V2
										ON V2.Name = (SELECT VenueName FROM Event_Venues AS EV WHERE EV.EventID = E2.EventID)
									JOIN Promoter AS P 
										ON P.PromoterID = S.PromoterID
								WHERE S.SeriesID = {$SeriesID}";
				// Get Specified Series
				if( $series = mysqli_query($con, $seriesQuery) or die($seriesQuery."</br></br>".mysqli_error($con) ))
				{
					if( mysqli_num_rows($series) > 0 )
					{
						$seriesRow = mysqli_fetch_array($series);
						// Create Date objects for the Start and End Dates
						$startDate = date_create_from_format("Y-m-d H:i:s", $seriesRow['StartDate']);
						$endDate = date_create_from_format("Y-m-d H:i:s", $seriesRow['FinalDate']);
						
						echo "<h1>{$seriesRow['SeriesName']}</h1>";
						echo "<tr><td><b>First Event:</b></td>";
							// Start Event
							echo "<td><font size=3>{$seriesRow['StartName']}</font></br><font size=2>{$seriesRow['FirstVenueName']}</font></br>
							<font size=1>{$seriesRow['FirstCity']}, {$seriesRow['FirstProvince']}</font></br>";
							echo "<font size=1>".date_format($startDate, 'D, M j, Y, G:i')."</font></td></tr>";
							
							// Final Event
							echo "<tr><td><b>Final Event:</b></td>";
							echo "<td><font size=3>{$seriesRow['FinalName']}</font></br><font size=2>{$seriesRow['FinalVenueName']}</font></br>
							<font size=1>{$seriesRow['FinalCity']}, {$seriesRow['FinalProvince']}</font></br>";
							echo "<font size=1>".date_format($endDate, 'D, M j, Y, G:i')."</font></td></tr>";
						echo "<tr><td><b>Number of Events:</b></td>";
						echo "<td>{$seriesRow['NumEvents']}</td></tr>";
						echo "<tr><td><b>Description:</b></td>";
						echo "<td>{$seriesRow['SeriesDescription']}</td></tr>";

						// Promoter Information:
						displayPromoterRow($con, $seriesRow['PromoterName'], $seriesRow['PromoterDescription'], $seriesRow['PromoterType'], $seriesRow['PromoterID']);
						
						$ticketsRemaining = $seriesRow['NumTicketsRemaining'];
						$ticketPrice = $seriesRow['TicketPrice'];
						
						// Display Retail Price
						displayTicketPriceRow($ticketPrice, $ticketsRemaining);
					}
					else
						echo "<b>ERROR:</b> Series Not Found.";
					
					// Free Results
					mysqli_free_result($series);
				}
			}
			
			// Function to display information of a ticket for resale.
			function displayResale($con, &$ticketPrice)
			{
				$resaleQuery = "SELECT Ticket.*, Fan.FName FROM Ticket JOIN Fan ON SellerID IS NOT NULL AND SellerID = FanID WHERE TicketNumber = {$_GET['ID']}";
				
				// Get Specified Ticket
				if( $res = mysqli_query($con, $resaleQuery) or die($seriesQuery."</br></br>".mysqli_error($con) ))
				{
					if( mysqli_num_rows($res) > 0 )
					{
						$ticketRow = mysqli_fetch_array($res);
						$ticketsRemaining; $ticketPrice;
						
						if( $ticketRow['SeriesOrEvent'] )
							displaySeries($con, $ticketRow['SeriesID'], $ticketsRemaining, $ticketPrice);
						else
							displayEvent($con, $ticketRow['EventID'],$ticketsRemaining, $ticketPrice);	

						$ticketPrice = $ticketRow['CurrentPrice'];
						
						// Display Sale Price
						echo "<tr><td><b>Sale Price:</b></td><td>";
						outputCurrencyString($ticketPrice);
						echo "</td></tr>";
						
						// Display Seller Name
						echo "<tr><td><b>Sold By:</b></td><td>{$ticketRow['FName']}</td></tr>";
					}
					else
						echo "ERROR: Ticket Not Found!";
					
					// Free Result
					mysqli_free_result($res);
				}
			}
			
			function displayPromoterRow($conn, $Name, $Description, $Type, $PromoterID)
			{
				echo "<tr><td><b>Promoter:</b></td>";
				echo "<td><b>{$Name}</b><br>";
				echo "{$Description}<br>";					
				// Additional Information for Music or Sports Promoters
				switch( $Type )
				{
					case 'Music':
						$musicQuery = "SELECT Artist, Genre FROM Music WHERE PromoterID = {$PromoterID}";
						if( ($musicRes = mysqli_query($conn, $musicQuery ) or die( $musicQuery."</br></br>".mysqli_error($conn))))
						{
							if( mysqli_num_rows($musicRes) > 0 )
							{
								$musicRow = mysqli_fetch_array($musicRes);
								echo "{$musicRow['Artist']}, {$musicRow['Genre']}";
							}
							
							// Clear results
							mysqli_free_result($musicRes);
						}
						break;
					case 'Sports':
						$sportsQuery = "SELECT League FROM Sports WHERE PromoterID = {$PromoterID}";
						if( ($sportsRes = mysqli_query($conn, $sportsQuery)) or die( $sportsQuery."</br></br>".mysqli_error($conn)))
						{
							if( mysqli_num_rows($sportsRes) > 0 )
							{
								$sportsRow = mysqli_fetch_array($sportsRes);
								echo "{$sportsRow['League']}";
							}
							
							// Clear Results
							mysqli_free_result($sportsRes);
						}
						break;
					default:
						break;
				}
				displayFollowing($conn, $PromoterID);
				
				// End the Table Row
				echo "</td></tr>";
			}
			
			function displayTicketPriceRow($price, $ticketsRemaining)
			{
				echo "<tr><td><b>Ticket Price:</b></td>";
				echo "<td>";
				outputCurrencyString($price);
				if( $ticketsRemaining <= 0 )
					echo "<font color='red'> SOLD OUT!</font>";
				echo "</td></tr>"; // End Ticket Price
			}
		
			function displayTicketInfo($conn, $ticketsRemaining, $price, $isResale = false)
			{				
				// Option to Buy:
				if( $ticketsRemaining > 0 )
				{
					//echo "ID: isset?".(isset($_GET['ID']) ? "TRUE" : "FALSE")."/Value: {$_GET['ID']} :: TYPE: isset?".(isset($_GET['type']) ? "TRUE" : "FALSE")."/Value: {$_GET['type']}</br>";
					echo "<form action='process_order.php?ID={$_GET['ID']}&type={$_GET['type']}' method='post'><tr><td><b>Payment Option:</b></td>";
					
					$payQuery = "SELECT CCID, CCNumber, CCType, CCMonth, CCYear FROM Credit_Card WHERE CCID IN (SELECT CCID FROM Payment_Info WHERE FanID=" . $_SESSION['userID'] . ")";
					if( ($payResult = mysqli_query($conn, $payQuery)) or die($payQuery."</br></br>".mysqli_error($conn)))
					{
						if( mysqli_num_rows($payResult) == 0)
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
						
						// Free Results
						mysqli_free_result($payResult);
					}
					
					if( !$isResale )
					{
						// Number of Tickets to purchase
						echo "<tr><td><b>Number of Tickets:</b></td>";
						?>
						<td>
						
							<select name="numTickets">
							<?php
								$num = 1; // Give options for a maximum of 5 tickets to buy limited by remaining amount.
								while( ($num <= 5) && ($num <= $ticketsRemaining) )
								{
									echo "<option value='" . $num . "'>" . $num . "</option>";
									$num++;
								}
							?>
							</select>
						</td></tr>
						<?php
					}
					
					// Ticket buy button
					echo "<tr><td colspan=2>";
					?>
					<INPUT TYPE="SUBMIT" VALUE="Get Tickets!">
					<?php
					echo "</td></tr></form>";
				}
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
