<?php
	// Start Session
    session_start();
    
    include 'db_functions.php';
    
    $conn = dbConnect();
    $eventID = $_GET['eventID'];
    // echo $eventID;
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>colour_blue - contact us</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
  <script type="text/javascript" src="view.js"></script>
<script type="text/javascript" src="calendar.js"></script>
</head>

<body>
<?php $_SESSION["userType"] = "promoter" ?>
<?php $_SESSION["userID"] = 12 ?>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Master<span class="logo_colour">Ticket</span></a></h1>
		  <!-- Make sure you put the proper page name here -->
          <h2>Venue</h2>
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
        <h1>VENUE</h1>

        <!-- USE PRE-EXISTING VENUE FOR EVENT -->
        <form method="post" action="assign_venue.php">
            <div class="form_description">
				<h2>Select Existing Venue</h2>
		    </div>
            <?php 
            if( !mysqli_connect_errno($conn) )
            {
                $query="SELECT * FROM Venue";
                if( $res = mysqli_query($conn,$query) )
		        {
                    if( mysqli_num_rows($res) > 0 )
                    {
                        
                        $select= '<select name="select">';
                        while($row=mysqli_fetch_array($res)){
                                $select.='<option value="'.$row['Name'].'">'.$row['Name'].'</option>';
                            }
                        }
                        $select.='</select>';
                        echo $select;
                    }
                }

            ?>
            
            <input type="hidden" name="eventID" value='<?php echo "$eventID";?>' />
			<input type="submit" name="btnSubmit" value="Select">
		</form>

        <h4> <br> OR </br> </h4>

        <!-- ADDS VENUE TO LIST AND USES IT FOR EVENT -->
        <form id="form_39438" class="appnitro"  method="post" action="venue_verify.php" method="post">
			<div class="form_description">
				<h2>Add Venue</h2>
		    </div>	

			<ul >	
				<p>Enter a venue name, address, and capcity.</p>
            <li id="li_19" >
				<label class="description" for="element_19"> Name </label>
				<div>
					<input id="piStreetNum" name="venName" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>
			<li id="li_10" >
				<label class="description" for="element_10"> Street Number </label>
				<div>
					<input id="piStreetNum" name="venStreetNum" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>
			<li id="li_11" >
				<label class="description" for="element_11"> Street Name </label>
				<div>
					<input id="piStreetName" name="venStreetName" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>
			<li id="li_12" >
				<label class="description" for="element_12"> City </label>
				<div>
					<input id="piCity" name="venCity" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>
			<li id="li_13" >
				<label class="description" for="element_12"> Province </label>
				<div>
					<input id="piProvince" name="venProvince" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>
            <li id="li_14" >
				<label class="description" for="element_14"> Capcity </label>
				<div>
					<input id="piProvince" name="venCapacity" class="element text medium" type="text" maxlength="255" value=""/> 
				</div> 
			</li>

			<li class="section_break"> </li>

                <div class="buttons">
                    <input type="hidden" name="eventID" value='<?php echo "$eventID";?>' />
                    <input id="submit" class="button_text" type="submit" name="Add" value="Add" />
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
