<?php
	// Start Session
	session_start();
	
	// Includes
	include 'db_functions.php';
?>
<!DOCTYPE html>
<html>

<head>
  <title>MasterTicket</title>
  <meta name="description" content="Getting Grades in 471 Final Projects" />
  <meta name="keywords" content="CPSC471 Project" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
		  <h2>Sell Ticket</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
        <?php include 'upcoming_events.php'; ?>
      </div>
      <div id="content">
        <!-- insert the page content here -->
        <h1>Your Ticket to Sell:</h1>
		<?php
			// Validate Information
			$valid = isset($_GET['ticketNumber']);
			if($valid)
				$TicketNumber = $_GET['ticketNumber'];
			else // TicketNumber not found
			{
				echo "ERROR: Invalid Ticket Number";
				exit;
			}
			
			// Establish Database Connection
			$conn = dbConnect();
			
			// Query for Ticket Information; Verify Ticket belongs to this fan.
			$TicketQuery = "SELECT 
								T.TicketNumber, 
								T.EventID, 
								T.SeriesID, 
								T.SaleID,
								T.PriceSold,
								T.CurrentPrice,
								T.SeriesOrEvent
							FROM Ticket AS T
							WHERE T.TicketNumber = {$TicketNumber}
								AND {$_SESSION['userID']} = (SELECT S.FanID
																FROM Sale AS S
																WHERE T.SaleID = S.SaleID)";
			$result = mysqli_query( $conn, $TicketQuery );
			
			// Query Server, check if succeeded
			if( !$result )
			{
				echo "ERROR: Query Failed: ".mysqli_error($conn)."; Query: {$TicketQuery}";
				exit;
			}
			
			// Verify response.
			if ( !($valid = (mysqli_num_rows($result) > 0)) )
			{
				echo "ERROR: Couldn't find ticket.";
				exit;
			}
			
			
			// All checks passed? Display form
			if( $valid )
			{
				$ticketInfo = mysqli_fetch_array($result);
				
				// Form Init
				echo "<form action='processSale.php?ticketNumber={$TicketNumber}' method='post'>";
				
				switch ($ticketInfo['SeriesOrEvent'])
				{
					case TRUE: // Series
						break;
					case FALSE: // event
						$eventQuery = "SELECT 
										E.Name AS EventName,
										E.EventTimestamp,
										E.Description AS EventDesc,
										E.Duration,
										P.PromoterID,
										P.Name AS PromoterName,
										P.Description AS PromoterDesc,
										P.PromoterType,
										V.Name AS VenueName,
										V.StreetNum,
										V.StreetName,
										V.City,
										V.Province,
										V.Capacity
										FROM Event AS E
											JOIN Promoter AS P
												ON E.PromoterID = P.PromoterID
											JOIN Venue AS V 
												ON V.Name = (SELECT VenueName
																FROM Event_Venues
																WHERE EventID = {$ticketInfo['EventID']})
										WHERE E.EventID = {$ticketInfo['EventID']}";
										
						
						
						// Perform Query and handle errors
						if( !($eventResult = mysqli_query( $conn, $eventQuery )) )
						{
							echo "ERROR: Event Query failed: ".mysqli_error($conn)."; Query: <br>{$eventQuery}</br>";
							exit;
						}
						
						// Check that there was a result found
						if( mysqli_num_rows($eventResult) == 0 )
						{
							echo "ERROR: No results found for event query: {$eventQuery}";
							exit;
						}
						else
						{	// Display Table
							$eventInfo = mysqli_fetch_array($eventResult);
							
							echo "<h2>{$eventInfo['EventName']}</h2>";
							echo "<table>";
							echo "<tr>"; 		// When
								echo "<td><b>When:</b></td>";
								echo "<td>{$eventInfo['EventTimestamp']}</td>";
							echo "</tr><tr>";	// Duration
								echo "<td><b>Duration:</b></td>";
								echo "<td>{$eventInfo['Duration']}</td>";
							echo "</tr><tr>";	// Description
								echo "<td><b>Description:</b></td>";
								echo "<td>{$eventInfo['EventDesc']}</td>";
							echo "</tr><tr>";	// Venue
								echo "<td><b>Where:</b></td>";
								echo "<td><b>{$eventInfo['VenueName']}</b></br>";
									echo "{$eventInfo['StreetNum']} {$eventInfo['StreetName']}</br>";
									echo "{$eventInfo['City']}, {$eventInfo['Province']}</br>";
									echo "Seating: {$eventInfo['Capacity']}</td>";
							echo "</tr><tr>"; // Promoter
								echo "<td><b>Promoter:</b></td>";
								echo "<td>";
									echo "<b>{$eventInfo['PromoterName']}</b></br>";
									echo "{$eventInfo['PromoterDesc']}</br>";
									echo "Type: {$eventInfo['PromoterType']}</br>";
									displayFollowing($conn, $eventInfo['PromoterID']);
								echo "</td>";
							echo "</tr><tr>";	// What Ticket was purchased for.
								echo "<td><b>You Paid:</b></td>";
								echo "<td>";
									outputCurrencyString( $ticketInfo['PriceSold'] );
								echo "</td>";
							echo "</tr><tr>";	// Price to Sell Ticket at.
								echo "<td><b>How Much are you Asking for this Ticket?</b></td>";
								echo "<td><input type='number' name='salePrice' min='0.0' max='{$ticketInfo['PriceSold']}' step='any'> <em>Max Price: ";
									outputCurrencyString($ticketInfo['PriceSold']);
								echo "</em></td>";
							echo "</tr><tr>";
								echo "<td colspan=2><input type='submit' value='Sell Tickets!'></td>";
							echo "</tr></table></form>";							
						}
						
						// Clear Event Result
						mysqli_free_result($eventResult);
						break;
					default:
						echo "<script type='text/javascript'>alert('ERROR: Could not determine the type of ticket.');</script>";
						header('//history(-1)');
						exit;
						break;
				}
				
				// Close Form
				echo "</form>";
				
				// Clear Ticket Result
				mysqli_free_result($result);
			}
		?>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Peanut Brittle
    </div>
  </div>
</body>
</html>