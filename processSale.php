<?php
	// using session
	session_start();
	
	include_once 'db_functions.php';

	echo "TicketNumber: " . $_GET['ticketNumber'] . "; sale Price" . $_POST['salePrice'];
	
	// Connect to Database
	$connection = dbConnect();

	// Verify Connection
	if( !mysqli_connect_errno($connection) )
	{
		$saleQuery; $returnHeader;
		if( isset($_GET['ticketNumber']) )
		{
			// Update Query
			$saleQuery = "UPDATE Ticket 
						SET SellerID={$_SESSION['userID']}, CurrentPrice={$_POST['salePrice']}
						WHERE TicketNumber={$_GET['ticketNumber']}";
			
			// Return Header:
			$returnHeader = 'Location: view_tickets.php?result=saleSuccess';
		}
		elseif( isset($_GET['cancelSale']) )
		{
			// Update Query
			$saleQuery = "UPDATE Ticket 
						SET SellerID=NULL, CurrentPrice=0.0
						WHERE TicketNumber={$_GET['cancelSale']}";
				
			// Return Header:
			$returnHeader = 'Location: view_tickets.php?result=cancelSuccess';
		}
		// Run Query
		if( !($result = mysqli_query($connection, $saleQuery)) )
		{
			echo "ERROR: Update Query Failed: ".mysqli_error($connection)."; </br></br><b>Query:</b></br>{$saleQuery}";
			exit;
		}			
		else
		{
			// Finished? Close Connection
			mysqli_close($connection);
			
			///* No Errors -> Redirect to Ticket Screen
			header($returnHeader);//*/
		}		
	}
?>