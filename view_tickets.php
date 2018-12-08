<?php
	// Start Session
	session_start();
	
	include_once 'db_functions.php';
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
		  <h2>My Tickets</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
      </div>
      <div id="content">
        <!-- insert the page content here -->
		<?php 
		if( isset($_GET['result']))
		{
			if( 'buySuccess' == $_GET['result'] )
				echo "<h3 style='color:forestgreen'>Tickets Acquired! Enjoy your event!</h3>";
			elseif ('saleSuccess' == $_GET['result'])
				echo "<h3 style='color:forestgreen'>Ticket's on the Market! Let's hope it goes to a good home!</h3>";
			elseif ('cancelSuccess' == $_GET['result'])
				echo "<h3 style='color:forestgreen'>Your Ticket Sale was Cancelled with Success!</h3>";
		}	
		?>
        <h1>Tickets</h1>
        <?php
			$con = dbConnect();
			$sellTicketLink = "sellTicket.php?ticketNumber=";
			
			if( !mysqli_connect_errno($con) )
			{
				// Display Tickets for Sale:
				echo "<h2>Tickets For Sale, By You:</h2>";
				$ticketSaleQuery = "SELECT 
									T.TicketNumber, 
									T.EventID, 
									T.PriceSold,
									T.CurrentPrice,
									T.SeriesOrEvent, 
									E.Name, 
									E.EventTimestamp AS Date, 
									E.Description, 
									E.Duration,
									V.VenueName
						FROM Ticket AS T 
						JOIN Event AS E 
							ON NOT T.SeriesOrEvent AND T.EventID = E.EventID
						JOIN Event_Venues AS V
							ON V.EventID = E.EventID
						WHERE T.SellerID = {$_SESSION['userID']}
						UNION
						SELECT 
									T.TicketNumber, 
									T.SeriesID, 
									T.PriceSold, 
									T.CurrentPrice,
									T.SeriesOrEvent, 
									Se.Name, 
									E1.EventTimestamp AS Date, 
									Se.Description, 
									Se.NumEvents,
									E2.EventTimestamp AS EndDate
						FROM Ticket AS T 
						JOIN Series AS Se 
							ON T.SeriesOrEvent AND T.SeriesID = Se.SeriesID
						JOIN Event AS E1
							ON Se.StartEventID = E1.EventID
						JOIN Event AS E2
							ON Se.EndEventID = E2.EventID
						WHERE T.SellerID = {$_SESSION['userID']}
						ORDER BY Date";
				
				if( ($res = mysqli_query($con, $ticketSaleQuery)) or die($ticketSaleQuery."<br/><br/>".mysql_error()) )
				{
					if( mysqli_num_rows($res) > 0 )
					{
						/* Test Table for all values.
						outputResultTable($res); exit;//*/
						while( $row = mysqli_fetch_array($res) )
						{
							echo "<table style='width:825px'>";
							echo "<tr><th><b>{$row['Name']}</b></th>
									  <th style='text-align:center'><b>Type:</b> ".($row['SeriesOrEvent'] ? "SERIES" : "EVENT")."</th>
									  <th style='width:165px;text-align:center'><b>Price Bought:</b> ";
									  outputCurrencyString($row['PriceSold']);
  									  echo "</th>";
									  echo "<th style='width:165px;text-align:center'><b>Selling For:</b> ";
									  outputCurrencyString($row['CurrentPrice']);
									  echo "</th>";
								echo "<th  style='width:165px;text-align:center'><form action='processSale.php?cancelSale={$row['TicketNumber']}' method='post'>
										<input type='submit' value='Cancel Sale'></form></th></tr>";
							echo "<tr><td colspan=5><b>Description:</b></br>{$row['Description']}</td>";
							echo "<tr>";
							echo "<td style='width:165px'><b>Number:</b> {$row['TicketNumber']}</td>";
							switch($row['SeriesOrEvent'])
							{
								case TRUE: // Series
									echo "<td style='width:165px'><b>From:</b> {$row['Date']}</td>";
									echo "<td style='width:165px'><b>To:</b> {$row['VenueName']}</td>";
									echo "<td colspan=2  style='width:330px'><b>Number of Events:</b> {$row['Duration']}</td>";
									break;
								case FALSE: // Event
									echo "<td style='width:165px'><b>When:</b> {$row['Date']}</td>";
									echo "<td style='width:165px'><b>Length:</b> {$row['Duration']}</td>";
									echo "<td colspan=2 style='width:330px'><b>Where:</b> {$row['VenueName']}</td>";
									break;
							}
							echo "</tr>";
							echo "</table>";
						}
						
						// Clear Result
						mysqli_free_result($res);
					}
					else
						echo "<p>You aren't selling any Tickets at this time!</p>";
				}
				else
				{	
					echo "<p>Query: " . $ticketSaleQuery . "</p>";
					echo "<b>ERROR:</b> Failed Query: " . mysqli_error($connection) . " {" . ($res ? "TRUE" : "FALSE") . "}</br>";
				}
				
				// Display Owned Tickets
				echo "<h2>My Tickets:</h2>"; 
				$eventQuery = "SELECT 
									T.TicketNumber, 
									T.EventID, 
									T.PriceSold, 
									T.SaleID, 
									T.SeriesOrEvent, 
									E.Name, 
									E.EventTimestamp AS Date, 
									E.Description, 
									E.Duration,
									S.FanID,
									V.VenueName
						FROM Ticket AS T 
						JOIN Event AS E 
							ON NOT T.SeriesOrEvent AND T.EventID = E.EventID
						JOIN Sale AS S
							ON T.SaleID = S.SaleID
						JOIN Event_Venues AS V
							ON V.EventID = E.EventID
						WHERE S.FanID = {$_SESSION['userID']} AND T.SellerID IS NULL
						UNION
						SELECT 
									T.TicketNumber, 
									T.SeriesID, 
									T.PriceSold, 
									T.SaleID, 
									T.SeriesOrEvent, 
									Se.Name, 
									E1.EventTimestamp AS Date, 
									Se.Description, 
									Se.NumEvents,
									S.FanID,
									E2.EventTimestamp AS EndDate
						FROM Ticket AS T 
						JOIN Series AS Se 
							ON T.SeriesOrEvent AND T.SeriesID = Se.SeriesID
						JOIN Sale AS S
							ON T.SaleID = S.SaleID
						JOIN Event AS E1
							ON Se.StartEventID = E1.EventID
						JOIN Event AS E2
							ON Se.EndEventID = E2.EventID
						WHERE S.FanID = {$_SESSION['userID']} AND T.SellerID IS NULL
						ORDER BY Date";				
				
				if( ($res = mysqli_query($con, $eventQuery)) or die($eventQuery."<br/><br/>".mysql_error()) )
				{
					if( mysqli_num_rows($res) > 0 )
					{
						/* Test Table for all values.
						outputResultTable($res); exit;//*/
						
						while( $row = mysqli_fetch_array($res) )
						{
							echo "<table style='width:825px'>";
							echo "<tr><th colspan=2><b>{$row['Name']}</b></th>
									  <th style='text-align:center'><b>Type:</b> ".($row['SeriesOrEvent'] ? "SERIES" : "EVENT")."</th>
									  <th style='width:165px;text-align:center'><b>Price:</b> ";
									  outputCurrencyString($row['PriceSold']);
							echo "</th>";
							echo "<th  style='width:165px;text-align:center'><form action='{$sellTicketLink}{$row['TicketNumber']}' method='post'>
										<input type='submit'value='Resell Ticket'></form></th></tr>";
							echo "<tr><td colspan=5><b>Description:</b></br>{$row['Description']}</td>";
							echo "<tr>";
							echo "<td style='width:165px'><b>Number:</b> {$row['TicketNumber']}</td>";
							switch($row['SeriesOrEvent'])
							{
								case TRUE: // Series
									echo "<td style='width:165px'><b>From:</b> {$row['Date']}</td>";
									echo "<td style='width:165px'><b>To:</b> {$row['VenueName']}</td>";
									echo "<td colspan=2  style='width:330px'><b>Number of Events:</b> {$row['Duration']}</td>";
									break;
								case FALSE: // Event
									echo "<td style='width:165px'><b>When:</b> {$row['Date']}</td>";
									echo "<td style='width:165px'><b>Length:</b> {$row['Duration']}</td>";
									echo "<td colspan=2 style='width:330px'><b>Where:</b> {$row['VenueName']}</td>";
									break;
							}
							echo "</tr>";
							echo "</table>";
						}
						
						// Clear Result
						mysqli_free_result($res);
					}
					else
						echo "<p>No Tickets at this time. Get browsing!</p>";
				}
				else
				{	
					echo "<p>Query: " . $eventQuery . "</p>";
					echo "<b>ERROR:</b> Failed Query: " . mysqli_error($connection) . " {" . ($res ? "TRUE" : "FALSE") . "}</br>";
				}
			}
			
		?>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Popcorn
    </div>
  </div>
</body>
</html>
