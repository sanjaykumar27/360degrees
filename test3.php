<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: Master for fees head and related processing
   * Updates here:
   */

//call the main config file, functions file and header

  require_once "./config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
?>

<?php
  $num = array(0,15,16,16.4,17.45,17.56,18,20);
  
  foreach ($num as $k => $value){
      $num[$k] = $value." -- ".roundOff($value);
  }
 
  echoThis($num);
  echoThis(formatCurrency('15000'));

  