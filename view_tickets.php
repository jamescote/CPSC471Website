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
<?php $_SESSION["userType"] = "fan" ?>
<?php $_SESSION["userID"] = 1 ?>
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
        <?php /*include 'upcoming_events.php';*/ ?>
      </div>
      <div id="content">
        <!-- insert the page content here -->
		<?php 
			if( $_GET['result'] == 'success' )
				echo "<h2>Tickets Acquired! Enjoy your event!</h2>";
		?>
        <h1>Tickets</h1>
        <?php
			$con = dbConnect();
			
			if( !mysqli_connect_errno($con) )
			{
				echo "<h2>Event Tickets:</h2>"; // Display Event Tickets
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
						WHERE S.FanID = {$_SESSION['userID']}";
				
				
				if( ($res = mysqli_query($con, $eventQuery)) or die($eventQuery."<br/><br/>".mysql_error()) )
				{
					if( mysqli_num_rows($res) > 0 )
					{
						/* Test Table for all values.
						outputResultTable($res)//*/

						while( $row = mysqli_fetch_array($res) )
						{
							echo "<table align='center'>";
							echo "<tr><th colspan=3><b>{$row['Name']}</b></th>
									  <th><b>Price:</b> ";
									  outputCurrencyString($row['PriceSold']);
							echo "</th>";
							echo "<th><form action='sell_ticket?tix={$row['TicketNumber']}' method='get'>
										<input type='submit' value='Resell Ticket'></form></th></tr>";
							echo "<tr><td colspan=5><b>Description:</b></br>{$row['Description']}</td>";
							echo "<tr>";
							echo "<td><b>Number:</b> {$row['TicketNumber']}</td>";
							echo "<td><b>When:</b> {$row['Date']}</td>";
							echo "<td><b>Length:</b> {$row['Duration']}</td>";
							echo "<td colspan=2><b>Where:</b> {$row['VenueName']}</td>";
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
					echo "<p>Query: " . $ticketQuery . "</p>";
					echo "<b>ERROR:</b> Failed Query: " . mysqli_error($connection) . " {" . ($res ? "TRUE" : "FALSE") . "}</br>";
				}
				
				// Display Series Tickets
				echo "<h2>Series Tickets:</h2>"; 
				$seriesQuery = "SELECT 
									T.TicketNumber, 
									T.SeriesID, 
									T.PriceSold, 
									T.SaleID, 
									T.SeriesOrEvent, 
									Se.Name, 
									E1.EventTimestamp AS StartDate, 
									E2.EventTimestamp AS EndDate, 
									Se.Description, 
									Se.NumEvents,
									S.FanID
						FROM Ticket AS T 
						JOIN Series AS Se 
							ON T.SeriesOrEvent AND T.SeriesID = Se.SeriesID
						JOIN Sale AS S
							ON T.SaleID = S.SaleID
						JOIN Event AS E1
							ON Se.StartEventID = E1.EventID
						JOIN Event AS E2
							ON Se.EndEventID = E2.EventID
						WHERE S.FanID = {$_SESSION['userID']}";
				
				
				if( ($res = mysqli_query($con, $seriesQuery)) or die($seriesQuery."<br/><br/>".mysql_error()) )
				{
					if( mysqli_num_rows($res) > 0 )
					{
						/* Test Table for all values.
						outputResultTable($res)//*/
						
						while( $row = mysqli_fetch_array($res) )
						{
							echo "<table align='center'>";
							echo "<tr><th colspan=3><b>{$row['Name']}</b></th>
									  <th><b>Price:</b> ";
									  outputCurrencyString($row['PriceSold']);
							echo "</th>";
							echo "<th><form action='sell_ticket?tix={$row['TicketNumber']}' method='get'>
										<input type='submit' value='Resell Ticket'></form></th></tr>";
							echo "<tr><td colspan=5><b>Description:</b></br>{$row['Description']}</td>";
							echo "<tr>";
							echo "<td><b>Number:</b> {$row['TicketNumber']}</td>";
							echo "<td><b>From:</b> {$row['StartDate']}</td>";
							echo "<td><b>To:</b> {$row['EndDate']}</td>";
							echo "<td colspan=2><b>Number of Events:</b> {$row['NumEvents']}</td>";
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
					echo "<p>Query: " . $ticketQuery . "</p>";
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
