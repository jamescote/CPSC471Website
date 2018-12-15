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
  <div id="main">
  
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
          <h2>Registration Page</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="site_content">
      <div id="contents">
        <!-- insert the page content here -->
        <h1>Account Registration</h1>
        <p>What type of account do you wish to create</p>
        
        <form action="fanRegistration.php" method="post">
          <div class="form_settings">
            <p style="padding-top: 0px"><span>&nbsp;</span><input class="submit" type="submit" name="contact_submitted" value="Fan" /></p>
          </div>
        </form>

        <form action="promoterRegistration.php" method="post">
            <div class="form_settings">
            <p style="padding-top: 0px"><span>&nbsp;</span><input class="submit" type="submit" name="contact_submitted" value="Promoter" /></p>
          </div>
        </form>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Yummy Maple Syrup
    </div>
  </div>
</body>
</html>
