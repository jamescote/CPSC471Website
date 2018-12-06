
<div id="menubar">
<ul id="menu">
<?php // Dynamic Menu
	if( $_SESSION["userType"] == "fan" ): ?>
  <li><a href="index.php">Home</a></li>
  <li><a href="browse_tickets.php">Browse Tickets</a></li>
  <li><a href="manage_account.php">Account</a></li>
    <li><a href="index.php">Sign Out</a></li>
<?php elseif( $_SESSION["userType"] == "promoter"): ?>
  <li><a href="index.php">Home</a></li>
  <li><a href="manage_events.php">Manage Events/Series</a></li>
  <li><a href="manage_account.php">Account</a></li>
  <li><a href="index.php">Sign Out</a></li>
<?php else: ?>
  <li><a href="index.php">Home</a></li>
  <li><a href="create_account.php">Create Account</a></li>
  <li><a href="login.php">Sign In</a></li>
<?php endif; ?>
</ul>
</div>
