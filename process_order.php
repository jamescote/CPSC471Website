<?php
	// using session
	session_start();
	
	include_once 'db_functions.php';
	date_default_timezone_set('America/Edmonton'); // Need to set default timezone to avoid warning.

	echo "PaymentID: " . $_POST['paymentChoice'] . "; Number of Tickets: " . $_POST['numTickets'] . "; ID: " . $_GET['ID'] . "; type: " . $_GET['type'];
	
	// Connect to Database
	$connection = dbConnect();
	$Success = TRUE;
	
	if( !mysqli_connect_errno($connection) )
	{
		$SalePrice; $IDType; $SeriesOrEvent; $NumTicketsRemaining; $FanID;
		// Fetch Event Information
		switch( $_GET['type'] )
		{
			case 'event':
				if( $eventPrice = mysqli_query( $connection, "SELECT TicketPrice, NumTicketsRemaining FROM event WHERE EventID = " . $_GET['ID'] ) )
				{
					$row = mysqli_fetch_array($eventPrice);
					$SalePrice = $row['TicketPrice'];
					$NumTicketsRemaining = $row['NumTicketsRemaining'];
					echo "TicketPrice: {$SalePrice}; Remaining: {$NumTicketsRemaining}</br>";
					
					// Clear Result
					mysqli_free_result($eventPrice);
				}
				$IDType = "EventID";
				$SeriesOrEvent = FALSE;
				echo "Series or Event: " . $SeriesOrEvent . "</br>";
				break;
			case 'series':
				if( $seriesPrice = mysqli_query( $connection, "SELECT TicketPrice, NumTicketsRemaining FROM series WHERE SeriesID = " . $_GET['ID'] ) )
				{
					$row = mysqli_fetch_array($seriesPrice);
					$SalePrice = $row['TicketPrice'];
					$NumTicketsRemaining = $row['NumTicketsRemaining'];
					
					// Clear Result
					mysqli_free_result($seriesPrice);
				}
				$IDType = "SeriesID";
				$SeriesOrEvent = TRUE;
				break;
			case 'resale':
				if( ($resaleRes = mysqli_query( $connection, "SELECT * FROM Ticket WHERE TicketNumber={$_GET['ID']}")) or die("Could Not Find Ticket</br></br>".mysqli_error($connection)))
				{
					if( mysqli_num_rows($resaleRes) > 0 )
					{
						$ticketRow = mysqli_fetch_array($resaleRes);
						$SalePrice = $ticketRow['CurrentPrice'];
						$FanID = $ticketRow['SellerID'];
					}
					else
						echo "<b>ERROR:</b> Unable to find Ticket.";
					
					// Clear Result
					mysqli_free_result($resaleRes);
				}
				break;
			default:
				echo "<b>ERROR:</b> Incorrect Ticket Type Specified!</br>";
				$Success = FALSE;
				break;
		}
		// Generate Sale
		$saleDate = date('Y-m-d');
		$saleQuery = "INSERT INTO sale (FanID, DollarAmount, SaleDate) VALUE ({$_SESSION['userID']}, {$SalePrice}, DATE '{$saleDate}');";
		if( !mysqli_query($connection, $saleQuery) )
		{
			echo "<b>ERROR:</b> Failed Sale Query: " . mysqli_error($connection) . "; Query: '{$saleQuery}'</br>";
			$Success = FALSE;
		}
		
		// Get the newly generated SaleID
		$saleID = mysqli_fetch_array(mysqli_query($connection, "SELECT LAST_INSERT_ID()"))[0];
		
		if( 'resale' != $_GET['type'] )
		{
			// Generate Ticket(s)
			for( $i = 0; $i < $_POST['numTickets']; $i++ )
			{
				$ticketQuery = "INSERT INTO Ticket (" . $IDType . ", SaleID, PriceSold, SeriesOrEvent) 
					VALUE (" . $_GET['ID'] . ", " . $saleID . ", " . $SalePrice . ", " . ($SeriesOrEvent ? "TRUE" : "FALSE" ) . ");";
				if( !mysqli_query($connection, $ticketQuery) )
				{
					echo "<b>ERROR:</b> Failed Ticket Query: " . mysqli_error($connection) . "</br>";
					$Success = FALSE;
				}
			}
			
			// Generate Sold_by entry
			$soldQuery = "INSERT INTO Sold_By (SaleID, PromoterID, FanOrPromoterSale) VALUE 
				(" . $saleID . ", (SELECT PromoterID FROM Event WHERE EventID=" . $_GET['ID'] . "), TRUE);";
			if( !mysqli_query($connection, $soldQuery) )
			{
				echo "<b>ERROR:</b> Failed Sold By Query: " . mysqli_error($connection) . "; Query: '" . $soldQuery . "'</br>";
				$Success = FALSE;
			}
			
			if( $Success )
			{
				$NumTicketsRemaining -= $_POST['numTickets'];
				
				// Update Tickets Remaining
				$updateQuery = "UPDATE Event 
									SET NumTicketsRemaining={$NumTicketsRemaining}
									WHERE EventID={$_GET['ID']}";
				if( !mysqli_query($connection, $updateQuery) )
				{
					echo "<b>ERROR:</b> Failed Update Query: " . mysqli_error($connection) . "; Query: '" . $updateQuery . "'</br>";
					$Success = FALSE;
				}
			}
		}
		else
		{
			// Update Ticket Information
			$updateQuery = "UPDATE Ticket
								SET SellerID=NULL, SaleID={$saleID}, PriceSold={$SalePrice}, CurrentPrice=NULL
								WHERE TicketNumber={$_GET['ID']}";
								
			if( !mysqli_query($connection, $updateQuery) )
			{
				echo "<b>ERROR:</b> Failed to Update Ticket Information. Error Result: ".mysqli_error($connection)."</br>Query: {$updateQuery}";
				$Success = FALSE;
			}
			
			// Create Sold By
			$soldQuery = "INSERT INTO Sold_By (SaleID, FanID, FanOrPromoterSale) VALUE 
				({$saleID}, {$FanID}, FALSE);";
			if( !mysqli_query($connection, $soldQuery) )
			{
				echo "<b>ERROR:</b> Failed Sold By Query: " . mysqli_error($connection) . "; Query: '" . $soldQuery . "'</br>";
				$Success = FALSE;
			}
		}
		
		// Finished? Close Connection
		mysqli_close($connection);
		
		///* No Errors -> Redirect to Ticket Screen
		if( $Success )
			header('Location: view_tickets.php?result=buySuccess');//*/
		
	}
?>