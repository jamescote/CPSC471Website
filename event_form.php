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
<?php $_SESSION["userType"] = "promoter" ?>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
          <h2>Create an Event</h2>
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

        <form id="form_39437" class="appnitro"  method="post" action="events_verify.php" method="post">
        <div class="form_description">
			<h2>Create Event</h2>
			<p>Form description.</p>
        </div>						
        <ul >
			<!-- NAME FIELD-->
            <li id="li_1" >
                <label class="description" for="element_1">Name </label>
                <div>
                    <input id="eventName" name="eventName" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>		
            <!-- DESCRIPTION FIELD-->
            <li id="li_2" >
                <label class="description" for="element_2">Description </label>
                <div>
                    <input id="eventDescription" name="eventDescription" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>		
			<!-- DATE FIELD-->
            <li id="li_3" >
                <label class="description" for="element_3">Date </label>
                <span>
                    <input id="eventMonth" name="eventMonth" class="element text" size="2" maxlength="2" value="" type="text"> /
                    <label for="element_3_1">MM</label>
                </span>
                <span>
                    <input id="eventDay" name="eventDay" class="element text" size="2" maxlength="2" value="" type="text"> /
                    <label for="element_3_2">DD</label>
                </span>
                <span>
                    <input id="eventYear" name="eventYear" class="element text" size="4" maxlength="4" value="" type="text">
                    <label for="element_3_3">YYYY</label>
                </span>
		    </li>	
            <!-- START TIME FIELD-->
            <li id="li_4" >
                <label class="description" for="element_4">Start (24 hr) </label>
                <span>
                    <input id="eventStartHour" name="eventStartHour" class="element text " size="2" type="text" maxlength="2" value=""/> : 
                    <label>HH</label>
                </span>
                <span>
                    <input id="eventStartMinute" name="eventStartMinute" class="element text " size="2" type="text" maxlength="2" value=""/> : 
                    <label>MM</label>
                </span>
		    </li>
            <!-- END TIME FIELD-->		
            <li id="li_5" >
                <label class="description" for="element_5">End (24 hr) </label>
                <span>
                    <input id="eventEndHour" name="eventEndHour" class="element text " size="2" type="text" maxlength="2" value=""/> : 
                    <label>HH</label>
                </span>
                <span>
                    <input id="eventEndMinute" name="eventEndMinute" class="element text " size="2" type="text" maxlength="2" value=""/> : 
                    <label>MM</label>
                </span>
		    </li>	
            <!-- NUMBER OF TICKETS FIELD-->	
            <li id="li_6" >
                <label class="description" for="element_6">Number Of Tickets </label>
                <div>
                    <input id="eventNumTickets" name="eventNumTickets" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>		
            <!-- PRICE FIELD-->
            <li id="li_7" >
                <label class="description" for="element_7">Price </label>
                <div>
                    <input id="eventTicketPrice" name="eventTicketPrice" class="element text medium" type="text" maxlength="255" value=""/> 
                </div> 
            </li>
			
            <div class="buttons">
			    <input type="hidden" name="form_id" value="39437" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
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
