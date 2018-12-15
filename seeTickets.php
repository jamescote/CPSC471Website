<?php
	/*********************************************
	 * Written by: James Coté
	 * For: CPSC 471 - Databases
	 * Description: Shows information about the upcoming
	 *		event or series and shows available ticket
	 *		options for the event. The ticket options show
	 *		tickets for sale by the promoter or tickets that
	 *		are for sale by other fans. 
	 *************************************************/
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
          <h2>See Tickets</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
      </div>
      <div id="content">
        <!-- insert the page content here -->
		<h1><?php echo ($_GET['type'] ? "Series" : "Event")." Information:";?></h1>
		<?php
			$conn = dbConnect();
			
			switch( $_GET['type'] )
			{
				case true:
					generateSeries($conn, $_GET['number']);
					break;
				case false:
					generateEvent($conn, $_GET['number']);
					break;
			}

			// Fetch and display information on a series
			function generateSeries($conn, $ID)
			{
				$seriesQuery = "SELECT
									S.SeriesID,
									S.Name AS SeriesName,
									S.Description AS SeriesDescription,
									S.NumEvents,
									S.NumTicketsRemaining,
									S.TicketPrice,
									E1.Name AS FirstEventName,
									E1.EventTimestamp AS StartDate,
									V1.Name AS FirstVenueName,
									V1.City AS FirstVenueCity,
									V1.Province AS FirstVenueProvince,
									E2.Name AS FinalEventName,
									E2.EventTimestamp AS EndDate,
									V2.Name AS FinalVenueName,
									V2.City AS FinalVenueCity,
									V2.Province AS FinalVenueProvince,
									S.PromoterID,
									P.Name AS PromoterName,
									P.Description AS PromoterDescription,
									P.PromoterType
								FROM Series AS S
									JOIN Event As E1
										ON S.StartEventID = E1.EventID
									JOIN Venue AS V1
										ON V1.Name = (SELECT VenueName 
														FROM Event_Venues AS EV1
														WHERE EV1.EventID = S.StartEventID)
									JOIN Event AS E2
										ON S.EndEventID = E2.EventID
									JOIN Venue AS V2
										ON V2.Name = (SELECT VenueName 
														FROM Event_Venues AS EV2
														WHERE EV2.EventID = S.EndEventID)
									JOIN Promoter AS P
										ON S.PromoterID = P.PromoterID
								WHERE S.SeriesID = {$ID}";
				if( ($res = mysqli_query($conn, $seriesQuery) ) or die($seriesQuery."</br></br>".mysqli_error($conn)))
				{
					if( mysqli_num_rows($res) > 0 )
					{ // Display Series Information
						
						// Fetch Result from Query
						$seriesRow = mysqli_fetch_array($res);
						
						// Create Date objects for the Start and End Dates
						$startDate = date_create_from_format("Y-m-d H:i:s", $seriesRow['StartDate']);
						$endDate = date_create_from_format("Y-m-d H:i:s", $seriesRow['EndDate']);
						
						// Table with Series Details
						echo "<table width='100%'>";
						
						// First Row: Series Name and Number of Events
						echo "<tr><td colspan=3><font size='5'>{$seriesRow['SeriesName']}</font></td><td><font size=3>{$seriesRow['NumEvents']} Events</font></td></tr>";
						
						// Second Row: Series Description
						echo "<tr><td><b>Description:</b></td><td colspan=3>{$seriesRow['SeriesDescription']}</td></tr>";
						
						// Third Row: Start and End Event Details
						echo "<tr valign='top'><td><b>First Event:</b></td>";
							// Start Event
							echo "<td><font size=3>{$seriesRow['FirstEventName']}</font></br><font size=2>{$seriesRow['FirstVenueName']}</font></br>
							<font size=1>{$seriesRow['FirstVenueCity']}, {$seriesRow['FirstVenueProvince']}</font></br>";
							echo "<font size=1>".date_format($startDate, 'D G:i, M j, Y')."</font>";
							
							// Final Event
							echo "</td><td><b>Final Event:</b></td>";
							echo "<td><font size=3>{$seriesRow['FinalEventName']}</font></br><font size=2>{$seriesRow['FinalVenueName']}</font></br>
							<font size=1>{$seriesRow['FinalVenueCity']}, {$seriesRow['FinalVenueProvince']}</font></br>";
							echo "<font size=1>".date_format($endDate, 'D G:i, M j, Y')."</font></td></tr>";
							
						// Fourth Row: Promoter Information
						echo "<tr><td><b>Promoter:</b></td><td colspan=3><u><font size=2.5>{$seriesRow['PromoterName']}</font></u></br>{$seriesRow['PromoterDescription']}</br>";
						
							// Additional Information for Music and Sports Promoters
							switch( $seriesRow['PromoterType'] )
							{
								case 'Music':
									$musicQuery = "SELECT Artist, Genre FROM Music WHERE PromoterID = {$seriesRow['PromoterID']}";
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
									$sportsQuery = "SELECT League FROM Sports WHERE PromoterID = {$seriesRow['PromoterID']}";
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
						echo "</td></tr>";
						echo "</table>";
						
						// Display Ticket Options
						echo "<h1>Tickets:</h1>";
						
						// Query to find Tickets for resale by other fans
						$resaleQuery = "SELECT 
											Ticket.*, 
											Fan.FName, 
											COUNT(*) AS NumTix
										FROM Ticket
											JOIN Fan ON SellerID IS NOT NULL AND SellerID = FanID
										WHERE SeriesOrEvent = TRUE 
											AND SellerID IS NOT NULL AND SeriesID = {$seriesRow['SeriesID']} AND SellerID != {$_SESSION['userID']}
										GROUP BY Ticket.CurrentPrice";
						
						// Table for Ticket Options
						echo "<table width='100%'>";
						
						// First Row: Titles
						echo "<tr><th>Seller</th><th>Price</th><th width='100px'>Tickets Remaining</th><th width='70px'>Link to Buy</th></tr>";
						
						// Second Row: Tickets from Promoter
						echo "<tr><td>{$seriesRow['PromoterName']}</td><td>";
						outputCurrencyString($seriesRow['TicketPrice']);
						echo "</td><td>{$seriesRow['NumTicketsRemaining']}</td><td>";
						
						// Display a button if tickets available, otherwise, sold out.
						if( $seriesRow['NumTicketsRemaining'] > 0)
						{
							echo "<form action='buy_ticket.php?ID={$seriesRow['SeriesID']}&type=series' method='post'>
										<input style='float:middle;height:25px' type='submit'value='Buy Ticket'></form>";
						}
						else
							echo "Sold Out!";
						
						echo "</td></tr>";
						
						// Query for Resale Tickets
						if( ($ticketRes = mysqli_query($conn, $resaleQuery)) or die($resaleQuery."</br></br>".mysqli_error($conn)))
						{
							// Loop through results
							while( $ticketRow = mysqli_fetch_array($ticketRes) )
							{
								// Display Seller's Name
								echo "<tr><td>{$ticketRow['FName']}</td><td>";
								
								// Display Sale Price
								outputCurrencyString($ticketRow['CurrentPrice']);
								
								// Only 1 ticket per resale ticket
								echo "</td><td>{$ticketRow['NumTix']}</td>";
								
								// Not able to be sold out, display buy button.
								echo "<td><form action='buy_ticket.php?ID={$seriesRow['SeriesID']}&type=resale&isseries=true&price={$ticketRow['CurrentPrice']}' method='post'>
										<input style='float:middle;height:25px' type='submit'value='Buy Ticket'></form></td></tr>";
							}
							
							// Clear Results
							mysqli_free_result($ticketRes);
						}
						
						echo "</table>";
	
						// Clear Results
						mysqli_free_result($res);
					}
				}
			}			
		
			// Fetch and display information on an event
			function generateEvent($conn, $ID)
			{
				// Event Details
				$eventQuery = "SELECT
									E.EventID,
									E.Name AS EventName,
									E.Description AS EventDescription,
									E.Duration,
									E.NumTicketsRemaining,
									E.TicketPrice,
									E.EventTimestamp AS Date,
									V.Name AS VenueName,
									V.City,
									V.Province,
									V.StreetNum,
									V.StreetName,
									V.Capacity,
									E.PromoterID,
									P.Name AS PromoterName,
									P.Description AS PromoterDescription,
									P.PromoterType
								FROM Event AS E
									JOIN Venue AS V
										ON V.Name = (SELECT VenueName FROM Event_Venues AS EV WHERE EV.EventID = E.EventID)
									JOIN Promoter AS P
										ON P.PromoterID = E.PromoterID
								WHERE E.EventID = {$ID}";
								
				// Query DB
				if( ($res = mysqli_query($conn, $eventQuery) ) or die($eventQuery."</br></br>".mysqli_error($conn)))
				{
					// Results Found? Display Event Information
					if( mysqli_num_rows($res) > 0 )
					{ // Display event Information
						// Fetch Results
						$eventRow = mysqli_fetch_array($res);
						
						// Create Date Object for Event Date
						$date = date_create_from_format("Y-m-d H:i:s", $eventRow['Date']);
						
						// Table for Displaying Event Information
						echo "<table width='100%'>";
						
						// Row 1: Display Event Name and Duration
						echo "<tr><td colspan=3><font size='5'>{$eventRow['EventName']}</font></td><td><font size=3>{$eventRow['Duration']} Minutes</font></td></tr>";
						
						// Row 2: Description
						echo "<tr><td><b>Description:</b></td><td colspan=3>{$eventRow['EventDescription']}</td></tr>";
						
						// Row 3: When and Where the event will happen.
						echo "<tr valign='top'><td><b>When:</b></td>";
							echo "<td>".formatDate($date, 1)."</td>";
							echo "<td><b>Where:</b></td>";
							echo "<td><font size=2>{$eventRow['VenueName']}</font></br>
							<font size=1>{$eventRow['StreetNum']} {$eventRow['StreetName']}</br>{$eventRow['City']}, {$eventRow['Province']}</br>Capacity: {$eventRow['Capacity']}</font></td></tr>";
							
						// Row 4: Promoter Information
						echo "<tr><td><b>Promoter:</b></td><td colspan=3><u><font size=2.5>{$eventRow['PromoterName']}</font></u></br>{$eventRow['PromoterDescription']}</br>";
						
						// Additional Information for Music or Sports Promoters
							switch( $eventRow['PromoterType'] )
							{
								case 'Music':
									$musicQuery = "SELECT Artist, Genre FROM Music WHERE PromoterID = {$eventRow['PromoterID']}";
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
									$sportsQuery = "SELECT League FROM Sports WHERE PromoterID = {$eventRow['PromoterID']}";
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
						echo "</td></tr>";
						echo "</table>";
						
						// Display Ticket Options
						echo "<h1>Tickets:</h1>";
						
						// Query for getting Resale ticket options
						$resaleQuery = "SELECT 
											Ticket.*, 
											Fan.FName,
											COUNT(*) AS NumTix
										FROM Ticket
											JOIN Fan ON SellerID IS NOT NULL AND SellerID = FanID
										WHERE SeriesOrEvent = FALSE 
											AND SellerID IS NOT NULL AND EventID = {$eventRow['EventID']}
											AND SellerID != {$_SESSION['userID']}
										GROUP BY Ticket.CurrentPrice";
						
						// Table to display Ticket Options
						echo "<table width='100%'>";
						
						// Row 1: Titles
						echo "<tr><th>Seller</th><th>Price</th><th width='100px'>Tickets Remaining</th><th width='70px'>Link to Buy</th></tr>";
						
						// Row 2: Tickets for Sale by Promoter
						echo "<tr><td>{$eventRow['PromoterName']}</td><td>";
						outputCurrencyString($eventRow['TicketPrice']);
						echo "</td><td>{$eventRow['NumTicketsRemaining']}</td><td>";
						
						if( $eventRow['NumTicketsRemaining'] > 0)
						{
							echo "<form action='buy_ticket.php?ID={$eventRow['EventID']}&type=event' method='post'>
										<input style='float:middle;height:25px' type='submit'value='Buy Ticket'></form>";
						}
						else
							echo "Sold Out!";

						echo "</td></tr>";
						
						// Query for resale Tickets
						if( ($ticketRes = mysqli_query($conn, $resaleQuery)) or die($resaleQuery."</br></br>".mysqli_error($conn)))
						{
							// Loop through results and display
							while( $ticketRow = mysqli_fetch_array($ticketRes) )
							{
								// Seller Name
								echo "<tr><td>{$ticketRow['FName']}</td><td>";
								// Sale Price
								outputCurrencyString($ticketRow['CurrentPrice']);
								
								// Only 1 Ticket available per resale ticket
								echo "</td><td>{$ticketRow['NumTix']}</td>";
								
								// Buy Button
								echo "<td><form action='buy_ticket.php?ID={$eventRow['EventID']}&type=resale&isseries=false&price={$ticketRow['CurrentPrice']}' method='post'>
										<input style='float:middle;height:25px' type='submit'value='Buy Ticket'></form></td></tr>";
							}
							
							// Clear Results
							mysqli_free_result($ticketRes);
						}
						
						echo "</table>";
	
						// Clear Results
						mysqli_free_result($res);
					}
				}
			}
		?>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Perogies
    </div>
  </div>
</body>
</html>
