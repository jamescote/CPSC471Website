<?php
	/*********************************************
	 * Written by: James Coté
	 * For: CPSC 471 - Databases
	 * Description: Displays information for all upcoming
	 *		events or series with an option to buy tickets
	 *		if there are any tickets available to purchase.
	 *		There are adjustable search parameters that
	 *		filter the database queries.
	 *************************************************/
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
			
			$startDate = date_create_from_format("Y-m-d", $_POST['startDate']);
			$endDate = date_create_from_format("Y-m-d", $_POST['endDate']);
			
			if( $startDate && $endDate && $endDate < $startDate )
			{
				$tempDate = $startDate;
				$startDate = $endDate;
				$endDate = $tempDate;
			}
		?>
	  <form action='browse_tickets.php' method='post'>
		<table style="bgcolor:white" width='190px'>
			<tr><td>
			<span style='display:inline-block'>
				<label for='keywords' style='display:block'>Keyword:</label>
				<input type="text" name="keyword" id='keywords'value='<?php echo (isset($_POST['keyword']) ? "{$_POST['keyword']}" : "");?>'></br>
			</span></td></tr>
			<tr><td align='center'>
				<input type='radio' name='eventType' value='event'<?php echo ('event' == $_POST['eventType'] ? " checked" : ""); ?>> Event
				<input type='radio' name='eventType' value='series'<?php echo ('series' == $_POST['eventType'] ? " checked" : ""); ?>> Series
				<input type='radio' name='eventType' id='defaultType' value='both'<?php echo ('both' == $_POST['eventType'] || !isset($_POST['eventType']) ? " checked" : ""); ?>> Both
			</td></tr>
			<tr><td><span style='display:inline-block'>
				<label for='venues' style='display:block'>Venue:</label>
			<?php  // Venues
				$venueResult = mysqli_query($conn, "SELECT Name FROM Venue");
				
				if( $venueResult ) // Successful Query
				{
					echo "<input id='venuesList' list='venues' name='venue' value='".(isset($_POST['venue']) ? "{$_POST['venue']}" : "")."'>";
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
						echo "<input id='promotersList' list='promoters' name='promoter' value='".(isset($_POST['promoter']) ? "{$_POST['promoter']}" : "")."'>";
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
					<option value='all' id='defaultPromoType'<?php echo ('all' == $_POST['promoterType'] || !isset($_POST['promoterType']) ? " selected" : ""); ?>>All</option>
					<option value='general'<?php echo ('general' == $_POST['promoterType'] ? " selected" : ""); ?>>General</option>
					<option value='music'<?php echo ('music' == $_POST['promoterType'] ? " selected" : ""); ?>>Music</option>
					<option value='sports'<?php echo ('sports' == $_POST['promoterType'] ? " selected" : ""); ?>>Sports</option>
				</select>
			</span></td></tr>
			<tr><td><span style='display:inline-block'>
				<label for='startDate' style='display:block'>Start Date:</label>
				<?php 
					$today = date('Y-m-d');
					echo "<input type='date' id='startDate' name='startDate' defaultValue='{$today}' min='{$today}' value='".(!isset($_POST['startDate']) || '' == $_POST['startDate'] ? "{$today}" : date_format($startDate, "Y-m-d"))."'></input>"; ?>
			</span></td></tr>
			<tr><td><span style='display:inline-block'>
				<label for='endDate' style='display:block'>End Date:</label>
				<?php echo "<input type='date' id='endDate' name='endDate' min='{$today}' value='".(!isset($_POST['endDate']) || '' == $_POST['endDate'] ? "" : date_format($endDate, "Y-m-d"))."'></input>"; ?>
			</span></td></tr><tr><td>
				<input type='checkbox' id='followedOnly' name='followedOnly' value='true'<?php echo (isset($_POST['followedOnly']) ? " checked" : "");?>> Followed Promoters Only</td></tr>
				
			<tr><td colspan=3 align='right'>
				<input style='float:right' type='button' name='clearBtn' value='Reset' onclick="clearParams()"/>
				<input style='float:right;margin-right:5px' type='submit' value='Search'>
				<script> // Script for followBtn -> Adds Follow Value to followed by table
				function clearParams()
				{
					var today = new Date();
					document.getElementById('keywords').value = "";
					document.getElementById('venuesList').value = "";
					document.getElementById('defaultType').checked = true;
					document.getElementById('promotersList').value = "";
					document.getElementById('defaultPromoType').selected = true;
					document.getElementById('startDate').value = "<?php echo date('Y-m-d'); ?>";
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
			// Apply Keyword filter
			$keywordQuery = "";
			if( isset($_POST['keyword']) && '' != $_POST['keyword'] )
			{
				$keyArray = explode(' ', $_POST['keyword']);
				$keywordQuery = "EventName like '%{$keyArray[0]}%' OR Description like '%{$keyArray[0]}%' OR PromoterName like '%{$keyArray[0]}%'";
				for( $i = 1; $i < count($keyArray); $i++)
					$keywordQuery .= " OR EventName like '%{$keyArray[$i]}%' OR Description like '%{$keyArray[$i]}%' OR PromoterName like '%{$keyArray[$i]}%'";
			}
			// Venue Filter
			$venueFilter = (isset($_POST['venue']) && '' != $_POST['venue'] ? "VenueName = '{$_POST['venue']}'" : "");
			// Promoter Filter
			$promoterFilter = (isset($_POST['promoter']) && '' != $_POST['promoter'] ? "PromoterName = '{$_POST['promoter']}'" : "");
			// Promoter Type Filter
			$promoterTypeFilter = (isset($_POST['promoterType']) && 'all' != $_POST['promoterType'] ? "PromoterType = '{$_POST['promoterType']}'" : "");
			// Start Date Filter
			$startDateFilter = (isset($_POST['startDate']) && "" != $_POST['startDate'] ? "DATE_FORMAT(StartDate, '%Y-%m-%d') >= '".date_format($startDate, 'Y-m-d')."'" : ""); 
			// End Date Filter
			$endDateFilter = (isset($_POST['endDate']) && "" != $_POST['endDate'] ? "DATE_FORMAT(StartDate, '%Y-%m-%d') <= '".date_format($endDate, 'Y-m-d')."'" : "");
			// Followed Promoters Filter
			$followedFilter = (isset($_POST['followedOnly']) ? " AND P.PromoterID IN (SELECT PromoterID FROM Followed_By WHERE FanID = {$_SESSION['userID']})" : "");
			
			// Flag to apply Filters
			$applyFilters = ("" != $keywordQuery) ||
							("" != $venueFilter) ||
							("" != $promoterFilter) ||
							("" != $promoterTypeFilter) ||
							("" != $startDateFilter) ||
							("" != $endDateFilter);
			
			$eventQuery = "SELECT 
								E.EventID AS ID,
								E.EventTimestamp AS StartDate,
								E.Name AS EventName,
								E.Description,
								E.NumTicketsRemaining,
								V.Name AS VenueName,
								V.City,
								V.Province,
								P.Name AS PromoterName,
								P.PromoterType
						FROM Event AS E 
							JOIN Venue AS V
								ON V.Name = (SELECT VenueName FROM Event_Venues AS EV WHERE E.EventID = EV.EventID)
							JOIN Promoter AS P
								ON P.PromoterID = E.PromoterID{$followedFilter}";
			
			$seriesQuery = "SELECT 
								S.SeriesID as ID,
								E1.EventTimestamp AS StartDate,
								S.Name AS EventName,
								S.Description,
								S.NumTicketsRemaining,
								E2.EventTimestamp AS VenueName,
								S.NumEvents AS City,
								S.TicketPrice AS Province,
								P.Name AS PromoterName,
								P.PromoterType
						FROM Series AS S
							JOIN Event AS E1
								ON S.StartEventID = E1.EventID
							JOIN Event AS E2
								ON S.EndEventID = E2.EventID
							JOIN Promoter AS P
								ON S.PromoterID = P.PromoterID{$followedFilter}
						ORDER BY StartDate";
						
			// Apply Event Type Filter
			$unionQuery = "";
			$unionQuery = (!isset($_POST['eventType']) || 'series' != $_POST['eventType'] ? "{$eventQuery}" : "").(!isset($_POST['eventType']) || 'both' == $_POST['eventType'] ? " UNION " : "").(!isset($_POST['eventType']) || 'event' != $_POST['eventType'] ? "{$seriesQuery}" : "");
			
			// DEFAULT Search
			$defaultQuery = "SELECT 
								*
							FROM ({$unionQuery}) AS Combined ";

			if( $applyFilters )
			{	// Apply Filters
				$andBoolean = ("" != $keywordQuery);
				$defaultQuery .= " WHERE ".$keywordQuery;
				appendFilter( $andBoolean, $venueFilter, $defaultQuery );
				appendFilter( $andBoolean, $promoterFilter, $defaultQuery );
				appendFilter( $andBoolean, $promoterTypeFilter, $defaultQuery );		
				appendFilter( $andBoolean, $startDateFilter, $defaultQuery );
				appendFilter( $andBoolean, $endDateFilter, $defaultQuery );
			}
			
			// Function to append a filter and update andBoolean
			function appendFilter( &$andBoolean, $filter, &$query )
			{
				$query .= ($andBoolean && ("" != $filter) ?  " AND " : "").$filter;				
				$andBoolean = $andBoolean || ("" != $filter);
			}
			
			if( ($res = mysqli_query($conn, $defaultQuery)) or die($defaultQuery."<br/><br/>".mysqli_error($conn)) )
			{
				if( mysqli_num_rows($res) > 0 )
				{
					/* Test Table for all values.
					outputResultTable($res); exit;//*/

					echo "<table width='100%'>";
					
					while( $row = mysqli_fetch_array($res) )
					{
						// Check if Series or Event
						$resultStartDate = date_create_from_format("Y-m-d H:i:s", $row['StartDate']);
						$resultEndDate = date_create_from_format("Y-m-d H:i:s", $row['VenueName']);
						$isSeries = ('object' == gettype($resultEndDate) ? true : false);
						$resaleQuery = "SELECT * FROM Ticket WHERE (SellerID IS NOT NULL) AND ((SeriesOrEvent = TRUE AND SeriesID = {$row['ID']}) OR (SeriesOrEvent = FALSE AND EventID = {$row['ID']}))";
						$ticketsRemaining = $row['NumTicketsRemaining'];
						if( ($resaleResult = mysqli_query($conn, $resaleQuery)) or die($resaleQuery."</br></br>".mysqli_error($conn)))
						{
							/* Test Table for all values.
							outputResultTable($resaleResult); exit;//*/
							$ticketsRemaining += mysqli_num_rows($resaleResult);
							
							mysqli_free_result($resaleResult);
						}
						
						// First Row
						echo "<tr><th style='width:100px'>".formatDate($resultStartDate, 2)."</th>";
						echo "<th>{$row['EventName']}</th>";
						echo "<th>{$row['PromoterName']}</th>";
						echo "<th style='text-align:center'>".($isSeries ? "Series" : "Event")."</th></tr>";
						
						// Second Row
						echo "<tr valign='top'><td>".($isSeries ? formatDate($resultEndDate, 3) : "<b>{$row['VenueName']}</b>");
						echo (!$isSeries ? "</br>{$row['City']}, {$row['Province']}" : "")."</td>";
						echo "<td>{$row['Description']}</td>";
						echo "<td>{$row['PromoterType']}</td>";
						echo "<td style='width:60px;vertical-align:middle'>";
						if( $ticketsRemaining > 0 )
							echo "<form action='seeTickets.php?number={$row['ID']}&type={$isSeries}' method='post'>
									<input style='float:right;height:25px' type='submit'value='See Tickets'></form>";
						else
							echo "Sold Out!";
						echo "</td></tr>";
					}
					
					echo "</table>";
				}
				else
					echo "<p>No Results found!</p>";
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
