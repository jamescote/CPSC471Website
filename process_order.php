<?php
	// using session
	session_start();
	
	include_once 'db_functions.php';
	date_default_timezone_set('America/Edmonton'); // Need to set default timezone to avoid warning.

	echo "PaymentID: " . $_POST['paymentChoice'] . "; Number of Tickets: " . $_POST['numTickets'] . "; ID: " . $_GET['ID'] . "; type: " . $_GET['type'];
	
	// Connect to Database
	$connection = dbConnect();
	
	if( !mysqli_connect_errno($connection) )
	{
		$SalePrice; $IDType; $SeriesOrEvent;
		// Fetch Event Information
		switch( $_GET['type'] )
		{
			case 'event':
				if( $eventPrice = mysqli_query( $connection, "SELECT TicketPrice FROM event WHERE EventID = " . $_GET['ID'] ) )
					$SalePrice = mysqli_fetch_array($eventPrice)['TicketPrice'];
				$IDType = "EventID";
				$SeriesOrEvent = FALSE;
				echo "Series or Event: " . $SeriesOrEvent . "</br>";
				break;
			case 'series':
				if( $seriesPrice = mysqli_query( $connection, "SELECT TicketPrice FROM series WHERE SeriesID = " . $_GET['ID'] ) )
					$SalePrice = mysqli_fetch_array($seriesPrice)['TicketPrice'];
				$IDType = "SeriesID";
				$SeriesOrEvent = TRUE;
				break;
			default:
				echo "<b>ERROR:</b> Incorrect Ticket Type Specified!</br>";
				break;
		}
		// Generate Sale
		$saleQuery = "INSERT INTO sale (FanID, DollarAmount, SaleDate) VALUE (" . $_SESSION['userID'] . ", " . $SalePrice . ", DATE '" . date('Y-m-d') . "');";
		if( !mysqli_query($connection, $saleQuery) )
			echo "<b>ERROR:</b> Failed Query: " . $saleQuery . "</br>";
		
		// Get the newly generated SaleID
		$saleID = mysqli_fetch_array(mysqli_query($connection, "SELECT LAST_INSERT_ID()"))[0];
		
		// Generate Ticket(s)
		for( $i = 0; $i < $_POST['numTickets']; $i++ )
		{
			$ticketQuery = "INSERT INTO ticket (" . $IDType . ", SaleID, PriceSold, SeriesOrEvent) VALUE (" . $_GET['ID'] . ", " . $saleID . ", " . $SalePrice . ", " . ($SeriesOrEvent ? "TRUE" : "FALSE" ) . ");";
			if( !mysqli_query($connection, $ticketQuery) )
				echo "<b>ERROR:</b> Failed Query: " . $ticketQuery . "</br>";
		}
		// Generate Sold_by entry
		
	}
?>