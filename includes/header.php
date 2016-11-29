<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au 
   * Page details here: header page for the app
   * Updates here: 
   */
  ob_start();
  validUser(); // chech the user is valid or not

  /*
    if(isset($_SESSION['userGroup'])){
    checkRole($_SESSION['userGroup'], basename($_SERVER['PHP_SELF']));
    } */
  if (isset($_SESSION['userGroup']) && $_SESSION['userGroup'] == 3) {
      $userDetails = getInstituteDetails();
      $userName = $userDetails['institutename'];
      $userEmail = $userDetails['username'];
  }

  if (isset($_SESSION['userGroup']) && is_numeric($_SESSION['userGroup'])) {
      $userGroup = $_SESSION['userGroup'];
  } else {
      $userGroup = 1;
  }
//-----
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>360 | School Empowerment System | <?php echo bcPage(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <noscript><meta http-equiv="refresh" content="1" url=javascripterror.php"> </noscript>
        <!-- Bootstrap -->
        <link href="<?php echo DIR_ASSET; ?>/css/bootstrap-3.2.0.min.css" rel="stylesheet"> 
        <link href="<?php echo DIR_ASSET; ?>/css/flat-ui.min.css" rel="stylesheet">
        <link href="<?php echo DIR_ASSET; ?>/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo DIR_ASSET; ?>/css/style.css" rel="stylesheet"> 
        <link href="<?php echo DIR_ASSET; ?>/css/bootstrap-tour-standalone.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo DIR_ASSET; ?>/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>

        <!-- Fav icons -->
        <link rel="shortcut icon" href="<?php echo DIR_ASSET; ?>/ico/favicon.ico" type="text/css"/>

        <!-- Java script -->

        <script src="<?php echo DIR_ASSET; ?>/js/jquery-3.1.1.js"></script>
        <script src="<?php echo DIR_ASSET; ?>/js/bootstrap.js"></script>
        <script src="<?php echo DIR_ASSET; ?>/js/bootstrap-multiselect.js" type="text/javascript"></script>
        <script src="<?php echo DIR_LIB; ?>/common.js" type="text/javascript"></script>

        <?php if (isset($loadSelectize) && ($loadSelectize !== "")) : ?>
              <script type="text/javascript" src="<?php echo DIR_ASSET; ?>/js/selectize.js"></script>
              <link rel="stylesheet" href="<?php echo DIR_ASSET; ?>/css/selectize.bootstrap3.css" type="text/css"/>  
              <?php
              initSelectize($loadSelectize);
          endif;
        ?> 
    </head>
    <body>
        <?php
          if (!isset($_GET["pop-up"])) :

              /*  if (isset($_GET['mod'])) {
                $_SESSION['module'] = $_GET['mod'];
                }
                if (isset($_SESSION['module'])) {
                if ($_SESSION['module'] == 'Student') {
                $menu = renderHeaderLinks($userGroup);
                $modulename = "Student Selected";
                }
                if ($_SESSION['module'] == 'Employee') {
                $menu = empMenu($userGroup);
                $modulename = "Employee Selected";
                }
                }

                if (empty($_SESSION['module'])) {
                $menu = "<br>Please Select Module";
                } */
              ?>

              <nav class="navbar navbar-default  navbar-fixed-top navbar-form" role="navigation">
                  <div class="container-fluid">
                      <div class="navbar-header" >
                          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                              <span class="sr-only">Toggle navigation</span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                          </button>
                          <a class="navbar-brand" href="<?php echo DIR_FILES; ?>/dashboard.php"><img class="img-responsive" src="<?php echo DIR_ASSET; ?>/images/view_logo_top.png" alt="360 | School Empowerment System" /></a>
                      </div>   
                      <!--<table align="right"><tr><td>

                                  <span class="clearfix"><br></span>
                      <?php if (validUser()) { ?>
                          <!--        <div class="dropdown">
                                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                          Select Module &nbsp;<span class="caret"></span>
                                      </button><?php ?>
                                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                          <li><a href="<?php //echo DIR_FILES     ?>/dashboard.php?mod=Student">Student</a></li>
                                          <li><a href="<?php //echo DIR_FILES     ?>/dashboard.php?mod=Employee">Employee</a></li>
                                      </ul>
                                  </div> --><?php } ?>
                      <!--   </td></tr>
                 </table>-->
                      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="float: right">
                          <?php if (validUser()) echo renderHeaderLinks($userGroup) ?>
                      </div>
                  </div>

              </nav>    
              <?php breadCrumb(); ?> 
              <script>
                  /*$(function () {
                   // single keys
                   //Mousetrap.bind(['h+k', 'ctrl+f'], function(e) {
                   // popUp();
                   //return false;
                   //});
                   //});
                   function popUp(url, w, h) {
                   var left = (screen.width / 1.2) - (w / 2);
                   var top = (screen.height / 1.1) - (h / 2);
                   var sw = (screen.width * .70);
                   var sh = (screen.height * .70);
                   window.open("<?= DIR_BASE ?>reports/dailyReportPDF.php?action=pdf", 'pop-up',"width=800,height=500");
                   }
                   */
              </script>
          <?php endif; ?>
        <?php

          function getInstituteDetails() {
              if (isset($_SESSION['instsessassocid']) && !empty(isset($_SESSION['instsessassocid']))) {
                  $sqlInstitute = " SELECT UPPER(institutename) as institutename,instituteabbrevation,institutelogo,instituteaddress1,username FROM tblinstsessassoc as t1 , tblinstitute as t2 , tbluser as t3
                          WHERE t1.instituteid=t2.instituteid AND  t1.instsessassocid= $_SESSION[instsessassocid]
                          AND t3.userid=$_SESSION[userid] AND t3.roleid=$_SESSION[userGroup]";
                  $res = dbSelect($sqlInstitute);
                  $row = mysqli_fetch_assoc($res);
                  return $row;
              }
          }
        ?>