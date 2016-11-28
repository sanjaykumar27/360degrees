<?php
/**
 * 360 - School Empowerment System.
 * Developer: Sanjay Kumar | www.ebizneeds.com.au
 * Page details here: Dashboard for the system, admin panel for the user.
 * Updated on: 21/10/2016.
 * */
// Call the includes file: config, functions, header.

require_once '../config/config.php';
require DIR_FUNCTIONS;
require_once VIEW_HEADER;

$browser = $_GET['browser'];
if($browser == "Firefox"){
    $link = "https://www.mozilla.org/en-US/firefox/new/";
}
if($browser == "Chrome"){
    $link = "https://www.google.co.in/chrome/browser/desktop/";
}
?>
<link href="../asset/css/bootstrap-3.2.0.min.css" rel="stylesheet" type="text/css"/>
<div class="container">
  <div class="row">
    <div class="span12">
      <div class="hero-unit center">
          <h1>Please Update your browser to latest Version<small><font face="Tahoma" color="red"> Error </font></small></h1>
          <br/>
          <p>You are using <strong style="color: Red"><?php echo $browser?></strong></p>
          <p class="h3">Recommended Browser <b>[ Google Chrome ]</b><img height="50" src="<?php echo DIR_ASSET?>/images/chrome.jpg">
          Above Version 50</p>
          <a href="<?php echo $link?>" class="btn btn-success"><?php echo $browser?> Download Page</a>
      </div>
        <br/>
    </div>
  </div>
</div>