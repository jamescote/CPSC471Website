<?php
	// Start Session
	session_start();
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
<?php $_SESSION["userName"] = null ?>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
		  <h2>Welcome<?php if ($_SESSION["userName"] != null){
					echo( " " . $_SESSION["userName"] );
				} 
		  ?>!</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
        <h3>Upcoming Events</h3>
        <?php
			// Connect to Database
			$con = mysqli_connect("localhost","root","123456789","cpsc471");
			
			// Successfully connected? 
			//		Pull the nearest upcoming events
			if( mysqli_connect_errno($con) )
			{
				echo "ERROR: Unable to connect to database! " . mysqli_connect_error();
			}
			else // Connected
			{
				// Fetch Next 3 Closest Events
				$query = "SELECT E.Name, E.EventTimestamp, E.Description, E.TicketPrice
				FROM event as E
				WHERE E.EventTimestamp > NOW()
				ORDER BY E.EventTimestamp ASC
				LIMIT 3";
				if( $res = mysqli_query($con,$query) )
				{
					if( mysqli_num_rows($res) > 0 )
					{
						echo "<table>";
						while( $row = mysqli_fetch_array($res))
						{
							// Row 1: Name and Date
							echo "<tr>";
							echo "<td><b>" . $row[Name] . "</b></td>";
							echo "<td>" . $row[EventTimestamp] . "</td>";
							echo "</tr>";
							// Row 2: Description
							echo "<tr>";
							echo "<td colspan='2'>" . $row[Description] . "</td>";
							echo "</tr>";
							// Row 3: Ticket Price and link to buy
							echo "<tr>";
							echo "<td>" . $row[TicketPrice] . "</td>";
							?> <td><a href ="buy_ticket.php">Buy Tickets!</a></td>
							<?php
						}
						echo "</table>";
						mysqli_free_result($res);
					}
					else // No results? 
					{
						echo "No matching records found!";
					}
				}
				else // Error in SQL Statment
				{
					echo "ERROR: could not execute $sql. " . mysqli_error($con);
				}
			}
		?>
      </div>
      <div id="content">
        <!-- insert the page content here -->
        <h1>Welcome to Master Ticket!</h1>
        <p>This site is for finding Tickets to Events! Please create an account and get started!</p>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Popcorn
    </div>
  </div>
</body>
</html>
