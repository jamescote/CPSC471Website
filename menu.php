
<div id="menubar">
<ul id="menu">
<?php // Dynamic Menu
	if( $_SESSION["userType"] == "fan" ): ?>
  <li><a href="index.php">Home</a></li>
  <li><a href="browse_tickets.php">Browse Tickets</a></li>
  <!-- <li><a href="manage_account.php">Account</a></li> -->
    <li><a href="index.php?logout=true">Sign Out</a></li>
   <!-- 
    <li><a href="credit_card_form.php">Credit Card Form</a></li>
    <li><a href="credit_card_page.php">Credit Card Page</a></li> 
    -->
  <li><a href="account_fan.php">Account</a></li> 
<?php elseif( $_SESSION["userType"] == "promoter"): ?>
  <li><a href="index.php">Home</a></li>
  <li><a href="manage_events.php">Manage Events/Series</a></li>
  <!-- <li><a href="manage_account.php">Account</a></li> -->
  <li><a href="index.php?logout=true">Sign Out</a></li>
<!--
  <li><a href="event_form.php">Event Form</a></li>
  <li><a href="event_page.php">Event Page</a></li>
  -->
  <li><a href="account_promoter.php"> Account </a></li>
<?php else: ?>
  <li><a href="index.php">Home</a></li>
  <li><a href="newUser.php">Create Account</a></li>
  <li><a href="login.php">Sign In</a></li>
<?php endif; ?>
</ul>
</div>
