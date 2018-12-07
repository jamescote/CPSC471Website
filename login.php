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
          <h2>Login Page</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div id="content2">
        <!-- insert the page content here -->
        <h1>Login</h1>
        <p>Please enter you Login details below</p>
        <form action="loginVerify.php" method="post">
          <div class="form_settings">
            
            <p>
              <span>Username</span>
              <input class= "contact" type="text" name="user" id="username">
            </p>
            <p>
              <span>Password</span>
              <input class= "contact" type="password" name="password" id="password">
            </p>

            <p>
              <span>Account Type</span>
                <select name="accountType">
                  <option value="fan">Fan</option>
                  <option value="promoter">Promoter</option>
                </select>
            </p>
            
            <p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="contact_submitted" value="Sign in" /></p>
          </div>
        </form>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; Yummy Maple Syrup</a>
    </div>
  </div>
</body>
</html>
