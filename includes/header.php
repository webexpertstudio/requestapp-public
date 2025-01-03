<?php
date_default_timezone_set('America/New_York'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Dodaj namerno grešku
//echo $undefined_variable;
error_reporting(E_ALL & ~E_NOTICE);

// Proverite da li je 'sess_agent_name' definisan pre nego što mu pristupite
if (isset($_SESSION['sess_agent_name'])) {
    $sess_agent_name = $_SESSION['sess_agent_name'];
} else {
    $sess_agent_name = null; // ili neka podrazumevana vrednost
}

// Proverite da li je 'sess_agent_status' definisan pre nego što mu pristupite
if (isset($_SESSION['sess_agent_status'])) {
    $sess_agent_status = $_SESSION['sess_agent_status'];
} else {
    $sess_agent_status = null; // ili neka podrazumevana vrednost
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"[]>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Alpine Adventures Luxury Ski Vacation Travel Package – Resort Hotel and Lodging</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" href="css/style.ie6.css" type="text/css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" href="css/style.ie7.css" type="text/css" media="screen" /><![endif]-->
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <script type="text/javascript" src="includes/js/jquery.js"></script>
    <!--<script type="text/javascript" src="js/script.js"></script>-->
 <!--<script src="includes/js/jquery.min.js"></script>-->
 <script src="includes/js/jquery.1.8.2.min.js"></script>
<script src="includes/js/jquery-ui.js"></script>
<script src="includes/js/datepicker.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	       
                      <style>
                            body{
                                  font-size: 12px; font-family: Arial;
                            }
                      </style>          
	 	<script type="text/javascript">
			$(function() {
			$( "#tabs,#tabsagent,#tabpagination" ).tabs();
			});
		</script>
		<script type="text/javascript">	
			function toggleMessage(id) {
			    var imgId = 'toggle_img_'+id;
			    var msgId = 'msg_'+ id;
			    if ( $( "#"+msgId ).is( ":hidden" ) ) {
			    	$( "#"+msgId ).slideDown();
			    	document.getElementById(imgId).src='http://'+window.location.host+'/images/minus.gif';
			    } else {
			    	$( "#"+msgId ).slideUp();
			    	document.getElementById(imgId).src='http://'+window.location.host+'/images/plus.gif';
			    }
			}
		</script>			
	  
        </head>
        
    <body>
  <div id="art-page-background-glare-wrapper">
    <div id="art-page-background-glare"></div>
</div>
<div id="art-main">
  <div class="art-box art-sheet">
    <div class="art-box-body art-sheet-body">
      <div id="art-main2">
        <div class="cleared reset-box"></div>
        <div class="art-header">
          <div class="art-header-position">
            <div class="art-header-wrapper">
              <div class="cleared reset-box"></div>
              <div class="art-header-inner">
                <a href="http://www.alpineadventures.net" target="_blank"><div class="art-headerobject"></div></a>
                <div class="art-logo">
				<div class="logout"><h2>Welcome, <?php echo $_SESSION["sess_agent_name"] ?></h2></div>
				</div>
              </div>
            </div>
          </div>
          <div class="art-bar art-nav">
            <div class="art-nav-outer">
              <div class="art-nav-wrapper">
                <div class="art-nav-inner">
                  <ul class="art-hmenu">
                    <li><a href="http://alpineadventures.net/destinations/?TID=1234567890" target="_blank">Destinations</a> </li>
                    <li><a href="http://alpineadventures.net/special-deals/?TID=1234567890" target="_blank">Special Deals</a></li>
                    <li><a href="http://alpineadventures.net/group-trips/?TID=1234567890" target="_blank">Group Trips</a> </li>
                    <li><a href="http://alpineadventures.net/adventure-trips/?TID=1234567890" target="_blank">Adventure Trips</a> </li>
					<li><a class="menumenager" href="index.php" >App Home</a></li>
                   <?php 
	            	if($_SESSION["sess_agent_status"]=="admin") { ?> 
				<li><a class="menumenager" href="index.php?page=reporting">Reporting</a></li>
				<li><a class="menumenager" href="index.php?page=tracking" >Tracking</a></li>
				<li><a class="menumenager" href="index.php?page=users" >Users</a></li>
				<?php 
				} 
				else { ?>
				<li><a href="http://alpineadventures.net/contact-us/" target="_blank">Contact</a></li><?php
				} ?>
                  </ul>
                </div>	
			<div class="logout"><button class="backbutton" type="button" onclick="history.back();">Back</button><button type="button" onClick="location.href='index.php?page=logout'">Logout</button></div>
	</div>
              </div>
            </div>
          </div>
          <div class="cleared reset-box"></div>
        </div>
        <div class="cleared reset-box"></div>
        <div class="art-box art-sheet">
          <div class="art-box-body art-sheet-body">
            <div class="art-layout-wrapper">
              <div class="art-content-layout">
                <div class="art-content-layout-row">
                  <div class="art-layout-cell art-content">
                    <div class="art-box art-post">
                      <div class="art-box-body art-post-body">
                        <div class="art-post-inner art-article">
                          <h2 class="art-postheader"></h2>
                          <div class="art-postcontent">
                            <div class="art-content-layout">
                              <div class="art-content-layout-row">
                                <div class="art-layout-cell layout-item-0" style="width: 100%;">
