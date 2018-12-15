<?php
	// Start Session
	session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>colour_blue - contact us</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
</head>

<body>
<?php $_SESSION["userType"] = "fan" ?>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
          <h2>Your Account</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
        <?php include 'user_fan_info.php'; ?>
      </div>
      <div id="content">

        <!-- insert the page content here -->

      <!-- View Tickets -->
      <form id="form_39437" class="appnitro"  method="post" action="view_tickets.php" method="post">
			<div class="form_description">
				<h2>View Tickets</h2>
		    </div>						
			<ul >	
			
			<div class="buttons">
				<input type="hidden" name="form_id" value="39439" />
				<input id="submit" class="button_text" type="submit" name="View" value="View" />
			</div>
			</ul>
			</form>

      <!-- View Credit Cards -->
      <form id="form_39437" class="appnitro"  method="post" action="credit_card_page.php" method="post">
			<div class="form_description">
				<h2>View Credit Cards</h2>
		    </div>						
			<ul >	
			
			<div class="buttons">
				<input type="hidden" name="form_id" value="39437" />
				<input id="submit" class="button_text" type="submit" name="View" value="View" />
			</div>
			</ul>
			</form>

      <!-- Add Credit Cards -->
			<form id="form_39438" class="appnitro"  method="post" action="credit_card_form.php" method="post">
			<div class="form_description">
				<h2>Add Credit Cards</h2>
		    </div>						
			<ul >	
			
			<div class="buttons">
				<input type="hidden" name="form_id" value="39438" />
				<input id="submit" class="button_text" type="submit" name="Add" value="Add" />
			</div>
			</ul>
			</form>

      <!-- Edit Account -->
      <form id="form_39437" class="appnitro"  method="post" action="edit_account.php" method="post">
			<div class="form_description">
				<h2>Account Info</h2>
		    </div>						
			<ul >	
			
			<div class="buttons">
				<input type="hidden" name="form_id" value="39439" />
				<input id="submit" class="button_text" type="submit" name="View" value="Edit" />
			</div>
			</ul>
			</form>

      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Pizza
    </div>
  </div>
</body>
</html>
