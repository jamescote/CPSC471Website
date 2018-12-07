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
<?php $_SESSION["userID"] = 1 ?>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
          <h2>Add a Credit Card</h2>
        </div>
      </div>
      <?php include 'menu.php'; ?>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div class="sidebar">
        <!-- insert your sidebar items here -->
      </div>
      <div id="content">

        <!-- insert the page content here -->

        <form id="form_39437" class="appnitro"  method="post" action="credit_card_verify.php" method="post">
			<div class="form_description">
				<h2>Credit Card</h2>
				<p>Enter a credit card.</p>
		    </div>						
			<ul >
			
			<li id="li_7" >
		    	<label class="description" for="element_7">Credit Card Number </label>
        		<div>
          			<input id="cardNumber" name="cardNumber" class="element text medium" type="text" maxlength="255" value=""/> 
        		</div> 
		    </li>		
			<li id="li_11" >
				<label class="description" for="element_11">Type of Credit Card </label>
				<div>
					<select class="element select medium" id="cardType" name="cardType"> 
						<option value="" selected="selected"></option>
						<option value="VISA" >Visa</option>
						<option value="MasterCard" >MasterCard</option>
						<option value="American Express" >American Express</option>

					</select>
				</div> 
			</li>		
			<li id="li_1" >
				<label class="description" for="element_1">Name on Card </label>
				<div>
					<input id="nameOnCard" name="nameOnCard" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>		
			<li id="li_6" >
				<label class="description" for="element_6">Security Code </label>
				<div>
					<input id="securityCode" name="securityCode" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>			
			<li id="li_8" >
				<label class="description" for="element_8"> Expiration Month </label>
				<div>
					<input id="monthExpire" name="monthExpire" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>		
			<li id="li_9" >
				<label class="description" for="element_9"> Expiration Year </label>
				<div>
					<input id="yearExpire" name="yearExpire" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>
			
			<div class="buttons">
				<input type="hidden" name="form_id" value="39437" />
				<input id="submit" class="button_text" type="submit" name="submit" value="Submit" />
			</div>
			</ul>
			</form>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      Copyright &copy; colour_blue | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">design from HTML5webtemplates.co.uk</a>
    </div>
  </div>
</body>
</html>
