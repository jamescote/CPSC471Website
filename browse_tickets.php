<?php
	// Start Session
	session_start();
	
	include 'db_functions.php';
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>colour_blue - another page</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
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
          <h2>Browse Tickets</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div class="sidebar">
	  <h3>Search Parameters:</h3>
	  	<?php
			$conn = dbConnect();
		?>
	  <form action='browse_tickets.php' method='post'>
		<table style="bgcolor:white" width='190px'>
			<tr><td>
			<span style='display:inline-block'>
				<label for='keywords' style='display:block'>Keyword:</label>
				<input type="text" name="keyword" id='keywords'></br>
			</span></td></tr>
			<tr><td align='center'>
				<input type='radio' name='eventType' value='event'> Event
				<input type='radio' name='eventType' value='series'> Series
				<input type='radio' name='eventType' id='defaultType' value='both' checked> Both
			</td></tr>
			<tr><td><span style='display:inline-block'>
				<label for='venues' style='display:block'>Venue:</label>
			<?php  // Venues
				$venueResult = mysqli_query($conn, "SELECT Name FROM Venue");
				
				if( $venueResult ) // Successful Query
				{
					echo "<input id='venuesList' list='venues' name='venue'>";
					echo "<datalist id='venues'>";
					
					while( $venueRow = mysqli_fetch_array($venueResult) )
						echo "<option value='{$venueRow['Name']}'>";
					
					echo "</datalist>";
					
					// Clear Result
					mysqli_free_result( $venueResult );
				}
			?>
			</span></td></tr>
			<tr><td><span style='display:inline-block'>  
				<label for='promoters' style='display:block'>Promoter:</label>
				<?php  // Promoters
					$promoResult = mysqli_query($conn, "SELECT Name FROM Promoter");
					
					if( $promoResult ) // Successful Query
					{
						echo "<input id='promotersList' list='promoters' name='promoter'>";
						echo "<datalist id='promoters'>";
						
						while( $promoRow = mysqli_fetch_array($promoResult) )
							echo "<option value='{$promoRow['Name']}'>";
						
						echo "</datalist>";
						
						// Free Result
						mysqli_free_result( $promoResult );
					}
					?>
			</span></td></tr>
			<tr><td>
			<span style='display:inline-block'>
				<label for='promoterType' style='display:block'>Promoter Type:</label>
				<select id='promoterType' name='promoterType'>
					<option value='all' id='defaultPromoType' selected>All</option>
					<option value='general'>General</option>
					<option value='music'>Music</option>
					<option value='sports'>Sports</option>
				</select>
			</span></td></tr>
			
			<tr><td><span style='display:inline-block'>
				<label for='startDate' style='display:block'>Start Date:</label>
				<?php 
					$today = date('Y-m-d');
					echo "<input type='date' id='startDate' name='startDate' defaultValue='{$today}' min='{$today}' value='{$today}'></input>"; ?>
			</span></td></tr>
			<tr><td><span style='display:inline-block'>
				<label for='endDate' style='display:block'>End Date:</label>
				<?php echo "<input type='date' id='endDate' name='endDate' min='{$today}'></input>"; ?>
			</span></td></tr><tr><td>
				<input type='checkbox' id='followedOnly' name='followedOnly' value='true'> Followed Promoters Only</td></tr>
				
			<tr><td colspan=3 align='right'>
				<input style='float:right' type='button' name='clearBtn' value='Reset' onclick="clearParams()"/>
				<input style='float:right;margin-right:5px' type='submit' value='Search'>
				<script> // Script for followBtn -> Adds Follow Value to followed by table
				function clearParams()
				{
					document.getElementById('keywords').value = "";
					document.getElementById('venuesList').value = "";
					document.getElementById('defaultType').checked = true;
					document.getElementById('promotersList').value = "";
					document.getElementById('defaultPromoType').selected = true;
					document.getElementById('startDate').value = document.getElementById('startDate').defaultValue;
					document.getElementById('endDate').value = "";
					document.getElementById('followedOnly').checked = false;
				}
				</script></td></tr>
			</table>
		</form>
      </div>
      <div id="content">
        <!-- insert the page content here -->
        <h1>Search For Tickets:</h1>
		<?php
			// DEFAULT Search
			$eventQuery = "SELECT 
								E.EventTimestamp AS StartDate,
								E.Name AS EventName,
								E.Description,
								V.Name AS VenueName,
								V.City,
								V.Province,
								P.Name AS PromoterName,
								P.PromoterType
						FROM Event AS E 
							JOIN Venue AS V
								ON V.Name = (SELECT VenueName FROM Event_Venues AS EV WHERE E.EventID = EV.EventID)
							JOIN Promoter AS P
								ON P.PromoterID = E.PromoterID
						UNION
						SELECT 
								E1.EventTimestamp,
								S.Name AS EventName,
								S.Description,
								E2.EventTimestamp,
								S.NumEvents,
								S.NumTicketsRemaining,
								P.Name AS PromoterName,
								P.PromoterType
						FROM Series AS S
							JOIN Event AS E1
								ON S.StartEventID = E1.EventID
							JOIN Event AS E2
								ON S.EndEventID = E2.EventID
							JOIN Promoter AS P
								ON S.PromoterID = P.PromoterID
						ORDER BY StartDate";				
				
				if( ($res = mysqli_query($conn, $eventQuery)) or die($eventQuery."<br/><br/>".mysqli_error($conn)) )
				{
					if( mysqli_num_rows($res) > 0 )
					{
						/* Test Table for all values.
						outputResultTable($res); exit;//*/

						echo "<table width='100%'>";
						
						while( $row = mysqli_fetch_array($res) )
						{
							// Check if Series or Event
							$startDate = date_create_from_format("Y-m-d H:i:s", $row['StartDate']);
							$endDate = date_create_from_format("Y-m-d H:i:s", $row['VenueName']);
							$isSeries = ('object' == gettype($endDate) ? true : false);
							
							// First Row
							echo "<tr><th style='width:100px'>".formatDate($startDate, 2)."</th>";
							echo "<th>{$row['EventName']}</th>";
							echo "<th>{$row['PromoterName']}</th>";
							echo "<th style='text-align:center'>".($isSeries ? "Series" : "Event")."</th></tr>";
							
							// Second Row
							echo "<tr valign='top'><td>".($isSeries ? formatDate($endDate, 3) : "<b>{$row['VenueName']}</b>");
							echo (!$isSeries ? "</br>{$row['City']}, {$row['Province']}" : "")."</td>";
							echo "<td>{$row['Description']}</td>";
							echo "<td>{$row['PromoterType']}</td>";
							echo "<td style='width:60px;vertical-align:middle'><form action='seeTickets.php?number={$row['TicketNumber']}&type={$isSeries} method='post'>
										<input style='float:right;height:25px' type='submit'value='See Tickets'></form></td></tr>";
						}
						
						echo "</table>";
					}
				}
		?>
		
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Christmas Dinner</a>
    </div>
  </div>
</body>
</html>
