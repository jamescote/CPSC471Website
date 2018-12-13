<?php
	/*******************************************************\
	 * Written by: James Coté							   *
	 * For: CPSC 471 - Databases                           *
	 * Description: This page processes two type of sale   *
	 *		Processes, either selling tickets owned by the *
	 *		current user or cancelling tickets that are    *
	 *		already for sale by the current user. 		   *
	 * Queries Used: Just update queries to update the     *
	 *		seller ID and sale price of the tickets to show*
	 *		that the ticket is up for sale or that the sale*
	 *		was cancelled and the tickets are no longer for*
	 *		sale.										   *
	\*******************************************************/
	
	// using session
	session_start();
	
	include_once 'db_functions.php';
	
	// Connect to Database
	$connection = dbConnect();

	// Verify Connection
	if( !mysqli_connect_errno($connection) && isset($_GET['price']) )
	{
		$saleQuery; $returnHeader;
		if( isset($_GET['ID']) )
		{
			// Update Query
			$saleQuery = "UPDATE Ticket 
						SET SellerID={$_SESSION['userID']}, CurrentPrice={$_POST['salePrice']}
						WHERE SeriesOrEvent = ".($_GET['type'] == "series" ? "TRUE" : "FALSE")."
							AND (EventID = {$_GET['ID']} OR SeriesID = {$_GET['ID']})
							AND PriceSold = {$_GET['price']}
						LIMIT {$_POST['numTickets']}";
			
			// Return Header:
			$returnHeader = 'Location: view_tickets.php?result=saleSuccess';
		}
		elseif( isset($_GET['cancelSale']) )
		{
			// Update Query
			$saleQuery = "UPDATE Ticket 
						SET SellerID=NULL, CurrentPrice=0.0
						WHERE SeriesOrEvent = ".($_GET['type'] == "series" ? "TRUE" : "FALSE")."
							AND (EventID = {$_GET['cancelSale']} OR SeriesID = {$_GET['cancelSale']})
							AND SellerID = {$_SESSION['userID']}
							AND PriceSold = {$_GET['price']}";
				
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